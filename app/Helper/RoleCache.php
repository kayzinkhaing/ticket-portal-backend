<?php
namespace App\Helper;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class RoleCache
{
    /**
     * Generate a cache key for a model + role
     */
    public static function key(string $model, ?string $role = null): string
    {
        $role ??= Auth::check() ? Auth::user()->roles->first() : 'guest';
        return "cache:role:{$role}:model:" . str_replace('\\', '_', $model);
    }

    /**
     * Remember role-based cache
     */
    public static function remember(string $model, \Closure $callback, ?string $role = null, int $ttl = 3600)
    {
        $key = self::key($model, $role);

        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Forget role-based cache
     */
    public static function forget(string $model, ?string $role = null)
    {
        $key = self::key($model, $role);
        Cache::forget($key);
    }

    /**
     * Forget cache for all roles for a model
     */
    public static function forgetAllRoles(string $model)
    {
        $roles = array_keys(Config::get('roles.list', ['admin','editor','staff','instructor','student','guest']));
        foreach ($roles as $role) {
            self::forget($model, $role);
        }
    }
}
