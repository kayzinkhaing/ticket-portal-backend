<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

/**
 * Trait CacheHelper
 *
 * Provides role-based cache handling for models using cache tags and dropdowns.
 */
trait CacheHelper
{
    /**
     * Generate a cache tag name for a model.
     */
    protected function getCacheTag(string $modelClass): string
    {
        return 'model:' . str_replace('\\', '_', $modelClass);
    }

    /**
     * Generate a cache key for a specific role.
     */
    protected function getCacheKey(string $modelClass, string $role): string
    {
        return strtolower(class_basename($modelClass)) . ':role:' . strtolower($role);
    }


    /**
     * Remember cache for a model per role.
     *
     * @param string $modelClass
     * @param callable $callback
     * @param string $role
     * @param int $minutes
     */
    protected function rememberCache(
        string $modelClass,
        callable $callback,
        string $role,
        int $minutes = 60
    ) {
        $tag = $this->getCacheTag($modelClass);
        $key = $this->getCacheKey($modelClass, $role);

        return Cache::tags($tag)->remember($key, now()->addMinutes($minutes), $callback);
    }


    /**
     * Forget cache for a specific role, or all roles for a model.
     *
     * @param string $modelClass
     * @param string|null $role
     */
    protected function forgetCache(string $modelClass, ?string $role = null): void
    {
        $tag = $this->getCacheTag($modelClass);

        if ($role) {
            Cache::tags($tag)->forget(
                $this->getCacheKey($modelClass, $role)
            );
        } else {
            Cache::tags($tag)->flush();
        }

        $this->forgetDropdownCache($modelClass);
    }


    /**
     * Forget dropdown cache for a model.
     */
    protected function forgetDropdownCache(string $modelClass, ?string $role = null): void
    {
        $tag = 'dropdowns';

        if ($role) {
            Cache::tags([$tag])->forget(
                'dropdowns:' . class_basename($modelClass) . ':role:' . strtolower($role)
            );
        } else {
            // Option 1: flush all dropdowns for all roles
            Cache::tags([$tag])->flush();
        }
    }
}
