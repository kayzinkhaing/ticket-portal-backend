<?php
// app/Observers/RoleObserver.php

namespace App\Observers;

use App\Events\CacheInvalidated;
use App\Models\Role; // Import the Role model

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function created(Role $role)
    {
        event(new CacheInvalidated('Role'));
    }

    /**
     * Handle the Role "updated" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function updated(Role $role)
    {
        event(new CacheInvalidated('Role'));
    }

    /**
     * Handle the Role "deleted" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function deleted(Role $role)
    {
        event(new CacheInvalidated('Role'));
    }
}
