<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class HydrateUserAcl
{
    public function handle($request, Closure $next)
    {
        $userId = Auth::id();
        if (!$userId) {
            return $next($request);
        }

        $user = Cache::remember("auth_user_{$userId}", now()->addHour(), function () use ($userId) {
            return User::with([
                'roles.permissions',
                'media',
            ])->find($userId);
        });

        // ✅ Cached roles
        $user->cachedRoleNames = $user->roles
            ->pluck('name')
            ->map(fn($r) => strtolower($r))
            ->toArray();
            // dd($user->cachedRoleNames);//admin

        // ✅ Cached permissions
        $user->cachedPermissionNames = $user->roles
            ->flatMap->permissions
            ->pluck('name')
            ->map(fn($p) => strtolower($p))
            ->unique()
            ->values()
            ->toArray();

        $media = $user->profileImage();

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        $user->image = $media
            ? $disk->url($media->url)
            : asset('images/default-avatar.png');

        Auth::setUser($user);

        return $next($request);
    }
}
