<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class CacheInvalidationListener
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        // Define a list of cache suffixes you want to invalidate
        $cacheSuffixes = ['index_data', 'dropdown_data']; // Add more suffixes if needed
        foreach ($cacheSuffixes as $suffix) {
            // Dynamically generate the cache key based on the model name and suffix
            $cacheKey = strtolower($event->modelName) . '_' . $suffix;
            // Forget the cache associated with that key
            Cache::forget($cacheKey);
        }
    }
}
