<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait serviceHelper
{
    protected function fetchResource($id)
    {
        return $this->service->findById((int) $id);
    }


    protected function getValidatedData(Request $request)
    {
        // dd($request->all());
        $requestClass = $this->resolveRequestClass();
        // dd($requestClass);
        if (!class_exists($requestClass)) {
            throw new \LogicException("Missing FormRequest: $requestClass");
        }

        return app($requestClass)->validated();
    }
    protected function getResource(): string
    {
        $routeName = request()->route()->getName();
        return explode('.', $routeName)[1];
    }

    protected function createResource(array $data)
    {
        return $this->service->create($data);
    }

    protected function updateResource($model, array $data)
    {
        return $this->service->update($model->id, $data);
    }


    protected function destroyResource($id)
    {
        $this->service->delete((int) $id);
    }

    protected function resolveRequestClass()
    {
        // dd($this->bladeFolder);
        $requestClass = 'App\\Http\\Requests\\' . $this->bladeFolder . 'Request';
        // dd($this->bladeFolder);
        return class_exists($requestClass) ? $requestClass : null;
    }
}
