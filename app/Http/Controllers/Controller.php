<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\CustomMessages;
use App\Helper\CustomVariables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Traits\{
    apiResponse,
    serviceHelper,
    configFileHandler,
    viewHelper,
    CacheHelper
};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;
    use apiResponse, serviceHelper, configFileHandler, viewHelper, CacheHelper;

    protected $service;
    protected CustomVariables $customVariables;
    protected CustomMessages $customMessages;
    protected string $bladeFolder = '';

    public function __construct($service = null)
    {
        $this->customVariables = app(CustomVariables::class);
        $this->customMessages  = app(CustomMessages::class);
        $this->service         = $service;
        $this->bladeFolder     = $service ? class_basename($service) : '';
    }

    // --------------------
    // INDEX
    // --------------------
    public function index()
    {
        $modelClass = $this->service->getModelInstance()::class;
        $this->authorize('viewAny', $modelClass);

        $role = current_role() ?? 'guest';

        $page = request()->get('page', 1);

        $items = $this->rememberCache(
            $modelClass,
            fn() => $this->service->allByRole($role),
            $role . '_page_' . $page,  // <-- include page
            60
        );

        // Extra data from service (like stats, statuses)
        $extraData = method_exists($this->service, 'getIndexData')
            ? $this->service->getIndexData()
            : [];

        $serviceName = strtolower(class_basename($this->service));

        $viewData = array_merge(
            [$serviceName => $this->formatModelData($items)],
            $extraData
        );
        // dd($viewData);

        return $this->isApiRequest()
            ? $this->apiResponse($viewData)
            : view($this->getBladePath(), $viewData);
    }

    // --------------------
    // SHOW
    // --------------------
    public function show($id)
    {
        $model = $this->fetchResource($id);
        $this->authorize('view', $model);

        return $this->isApiRequest()
            ? $this->apiResponse($this->formatModelData($model))
            : view($this->getBladePath(), ['data' => $model]);
    }

    // --------------------
    // STORE
    // --------------------
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $this->getValidatedData($request);
        $resource = $this->createResource($data);

        if (!$resource) {
            return $this->apiErrorResponse($this->customMessages->getMessage($this->customVariables->get('ACT_FAIL')), 500);
        }

        if ($this->shouldHandleConfigFile()) {
            $this->handleConfigFile($resource->toArray());
        }

        $successMessage = $this->customMessages->getMessage($this->customVariables->get('CR_SUCC'));

        return $this->isApiRequest()
            ? $this->apiResponse($this->formatModelData($resource), 200, $successMessage)
            : $this->generateResponse($resource->toArray(), $successMessage);
    }

    // --------------------
    // UPDATE
    // --------------------
    public function update(Request $request)
    {
        $id = (int) array_values($request->route()->parameters())[0];
        $model = $this->service->findById($id);

        $this->authorize('update', $model);

        $data = $this->getValidatedData($request);
        $updated = $this->updateResource($model, $data);

        if ($this->shouldHandleConfigFile()) {
            $oldName = $model->name;
            if ($oldName !== $updated->name) {
                $this->removeConfigFileKey($oldName);
                $this->handleConfigFile($updated->toArray());
            }
        }

        $successMessage = $this->customMessages->getMessage($this->customVariables->get('UD_SUCC'));

        return $this->isApiRequest()
            ? $this->apiResponse($this->formatModelData($updated), 200, $successMessage)
            : $this->generateResponse($updated->toArray(), $successMessage);
    }

    // --------------------
    // DESTROY
    // --------------------
    public function destroy($id = null)
    {
        $id = (int) array_values(request()->route()->parameters())[0];
        $model = $this->fetchResource($id);

        $this->authorize('delete', $model);

        $this->destroyResource($id);

        if ($this->shouldHandleConfigFile()) {
            $this->removeConfigFileKey($model->name);
        }

        $successMessage = $this->customMessages->getMessage($this->customVariables->get('DE_SUCC'));

        return $this->isApiRequest()
            ? $this->apiResponse([], 200, $successMessage)
            : $this->generateResponse([], $successMessage);
    }

    // --------------------
    // Helper: Format Models to Arrays Recursively
    // --------------------
    protected function formatModelData($items)
    {
        if ($items instanceof Collection) {
            return $items->map(fn($item) => $this->formatModelData($item))->all();
        } elseif ($items instanceof Model) {
            return $items->toArray();
        } elseif (is_array($items)) {
            return array_map(fn($item) => $this->formatModelData($item), $items);
        }

        return $items;
    }
}
