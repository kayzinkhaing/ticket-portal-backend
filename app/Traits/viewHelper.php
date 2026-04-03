<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Route;

trait viewHelper
{
    /**
     * Determine if this request is for backend (admin/instructor/editor/staff)
     */
    protected function getBladePath(?string $override = null): string
    {
        $routeName = $override ?? Route::currentRouteName();

        // Remove duplicate "backend." if exists
        $routeName = preg_replace('/^backend\.backend\./', 'backend.', $routeName);

        // If backend request and route does NOT start with backend., prepend it
        if (is_backend() && ! str_starts_with($routeName, 'backend.')) {
            $routeName = 'backend.' . $routeName;
        }

        return $routeName;
    }


    protected function indexRouteName(): string
    {
        // backend.technologies.edit
        $routeName = request()->route()->getName();

        // ['backend', 'technologies', 'edit']
        $parts = explode('.', $routeName);

        // backend.technologies.index
        return "{$parts[0]}.{$parts[1]}.index";
    }

    protected function indexRouteParams(): array
    {
        $params = ['page' => request()->get('page', 1)];

        if (is_backend()) {
            $params['role'] = current_role();
        }

        return $params;
    }

    public function getClassBaseName($className)
    {
        return strtolower(class_basename($className));
    }

    protected function resolveModelClass($key)
    {
        $modelNamespace = 'App\\Models\\';
        $className = ucfirst($key);
        return $modelNamespace . $className;
    }
    protected function buildDropdownData(): array
    {
        if (!$this->service) {
            return [];
        }

        $relations = $this->service->getRelations();
        $model     = $this->service->getModelInstance();

        $dropdowns = [];

        foreach ($relations as $relation => $meta) {

            if (!method_exists($model, $relation)) {
                continue;
            }

            $relationInstance = $model->$relation();
            $relatedModel     = $relationInstance->getRelated();
            $query            = $relatedModel->newQuery();

            /* ---------------------------------
            | 1. Resolve eager loads
            |----------------------------------*/
            $eagerLoads = [];

            if (!empty($meta['label']) && str_contains($meta['label'], '.')) {
                $eagerLoads[] = explode('.', $meta['label'])[0];
            }

            if (!empty($meta['eager_load'])) {
                $eagerLoads = array_merge($eagerLoads, $meta['eager_load']);
            }

            if ($eagerLoads) {
                $query->with(array_unique($eagerLoads));
            }

            /* ---------------------------------
            | 2. Resolve SELECT columns (only real columns)
            |----------------------------------*/
            $selects = [$relatedModel->getKeyName()]; // always select PK

            if (!empty($meta['label'])) {
                $labelParts = explode('.', $meta['label']);

                // label = single column (like 'name')
                if (count($labelParts) === 1 && $relatedModel->getConnection()->getSchemaBuilder()->hasColumn($relatedModel->getTable(), $labelParts[0])) {
                    $selects[] = $labelParts[0];
                }
                // label = relation.column (like 'user.name')
                else {
                    $relationName = $labelParts[0];
                    if (method_exists($relatedModel, $relationName)) {
                        $rel = $relatedModel->$relationName();
                        if (method_exists($rel, 'getForeignKeyName')) {
                            $selects[] = $rel->getForeignKeyName();
                        }
                    }
                }
            }

            $query->select(array_unique($selects));

            /* ---------------------------------
            | 3. Build dropdown (resolve accessor or nested label in PHP)
            |----------------------------------*/
            $dropdowns[$relation . 'Dropdown'] = $query->get()->mapWithKeys(function ($item) use ($meta) {
                // data_get works for relationships and accessors
                return [
                    (string) $item->getKey() => data_get($item, $meta['label']),
                ];
            })->toArray();
        }

        return $dropdowns;
    }



    /**
     * Notify all admins after store
     */
    
}
