<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class dropdownRepository
{
    /**
     * Get the dropdown options for a given model.
     *
     * @param  string  $modelClass
     * @param  string  $valueField
     * @param  string  $labelField
     * @return array
     */
    public function getOptions(string $modelClass, string $valueField = 'id', string $labelField = 'name'): array
    {

        $cacheKey = strtolower(class_basename($modelClass)) . '_dropdown';

        return Cache::remember($cacheKey, 60, function () use ($modelClass, $valueField, $labelField) {
            // Fetch data from the model
            $model = app($modelClass); // Resolve the model class from the container
            $items = $model::all(); // Get all records for the model
            // Return the dropdown options in a key-value format
            return $items->pluck($labelField, $valueField)->toArray();
        });
    }
}
