<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CacheHelper;
use Illuminate\Support\Facades\Cache;

/**
 * Automatically refreshes role-based cache for models when they are created, updated, or deleted.
 */
class CacheObserver
{
    use CacheHelper;

    /**
     * Refresh cache for all roles + guest
     */
    protected function refreshCacheFor(Model $model): void
    {
        $modelClass = get_class($model);

        // one flush per model
        $this->forgetCache($modelClass);
        Cache::tags(['dropdowns'])->flush();
    }

    public function created(Model $model)
    {
        $this->refreshCacheFor($model);
    }
    public function updated(Model $model)
    {
        // dd('ok');
        $this->refreshCacheFor($model);
    }
    public function deleted(Model $model)
    {
        $this->refreshCacheFor($model);
    }
}
