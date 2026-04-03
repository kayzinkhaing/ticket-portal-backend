<?php

namespace App\Models\Pivots;

use App\Traits\CacheHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BasePivot extends Pivot
{
    use CacheHelper;

    protected static function booted()
    {
        // fires whenever pivot row is created, updated, or deleted
        static::created(fn($pivot) => $pivot->clearRelatedCache());
        static::updated(fn($pivot) => $pivot->clearRelatedCache());
        static::deleted(fn($pivot) => $pivot->clearRelatedCache());
    }

    protected function clearRelatedCache(): void
    {
        foreach ($this->getRelatedModels() as $model) {
            if (!$model) continue;

            // flush all role-based cache for this model
            Cache::tags(
                'model:' . str_replace('\\', '_', get_class($model))
            )->flush();
        }
    }



    protected function getRelatedModels(): array
    {
        $related = [];

        // get dynamic foreign keys
        $table = $this->getTable();
        $columns = $this->getAttributes();

        foreach ($columns as $column => $value) {
            if (str_ends_with($column, '_id') && $value) {
                // get model class dynamically
                $modelClass = $this->guessModelFromColumn($column);
                if ($modelClass && class_exists($modelClass)) {
                    $related[] = $modelClass::find($value);
                }
            }
        }

        return $related;
    }


    protected function guessModelFromColumn(string $column): ?string
    {
        // e.g., role_id -> App\Models\Role
        $base = ucfirst(str_replace('_id', '', $column));
        $modelClass = "App\\Models\\{$base}";
        return class_exists($modelClass) ? $modelClass : null;
    }
}
