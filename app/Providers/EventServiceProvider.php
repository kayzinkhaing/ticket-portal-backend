<?php
// app/Providers/EventServiceProvider.php

namespace App\Providers;

use App\Events\CacheInvalidated;
use App\Listeners\CacheInvalidationListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\UserRegistered;
use App\Listeners\SendOtpEmail;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CacheInvalidated::class => [
            CacheInvalidationListener::class,
        ],
        // UserRegistered::class => [
        //     SendOtpEmail::class,
        // ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
