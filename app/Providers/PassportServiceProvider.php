<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class PassportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Enable password grant
        Passport::tokensCan([
            'view' => 'View content',
            'create' => 'Create content',
        ]);

        // Enable password grant for OAuth
        Passport::enableImplicitGrant(); // This will ensure that password grant is enabled
        Passport::tokensExpireIn(now()->addMinutes(30));
    }
}
