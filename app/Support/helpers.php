<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

if (! function_exists('current_role')) {
    /**
     * Get the primary role of the currently authenticated user
     *
     * @return string|null
     */
    function current_role(): ?string
    {
        // dd('helper');
        // dd(Auth::user()->cachedRoleNames[0]);
        return Auth::check() ? strtolower(Auth::user()->cachedRoleNames[0] ?? null) : null;
    }
}

if (! function_exists('is_backend')) {
    /**
     * Determine if the current request is for a backend route (role-prefixed)
     *
     * @return bool
     */
    function is_backend(): bool
    {
        return request()->route('role') !== null;
    }
}


if (! function_exists('dual_route')) {
    /**
     * Generate a URL for frontend or backend based on context
     *
     * Example:
     * - frontend: route('frontend.courses.index')
     * - backend: route('backend.courses.index', ['role' => current_role()])
     *
     * @param string $resource
     * @param string $action
     * @param array  $params
     * @return string
     */
    function dual_route(string $resource, ?string $action = null, array $params = []): string
    {
        if (is_backend()) {
            // dd($params);

            if ($resource === 'roles' && isset($params['role_id'])) {
                $params['id'] = $params['role_id'];
                unset($params['role_id']);
            }


            // ✅ role FIRST, then id
            $params = array_merge(
                ['role' => current_role()],
                $params
            );

            $routeName = str_contains($resource, '.')
                ? "backend.$resource"
                : "backend.$resource." . ($action ?? 'index');
            // dd($routeName);//"backend.courses.create"

            return route($routeName, $params);
        }

        // frontend
        $routeName = str_contains($resource, '.')
            ? "frontend.$resource"
            : "frontend.$resource." . ($action ?? 'index');

        return route($routeName, $params);
    }
}

if (! function_exists('dual_resource')) {
    /**
     * Register both frontend and backend resource routes for a given controller
     * Automatically avoids conflicts with {role} prefix.
     *
     * @param string $uri        URI segment, e.g., 'courses'
     * @param string $controller Fully qualified controller class
     * @return void
     */
    function dual_resource(string $uri, string $controller): void
    {
        // dd($uri, $controller);
        // FRONTEND
        Route::get($uri, [$controller, 'index'])->name("frontend.$uri.index");
        Route::get("$uri/{id}", [$controller, 'show'])->name("frontend.$uri.show");

        // BACKEND
        Route::prefix('{role}')
            ->middleware(['auth', 'check.role.prefix'])
            ->group(function () use ($uri, $controller) {

                Route::resource($uri, $controller)
                    ->parameters([$uri => 'id']) // ✅ ALWAYS {id}
                    ->names([
                        'index'   => "backend.$uri.index",
                        'create'  => "backend.$uri.create",
                        'store'   => "backend.$uri.store",
                        'show'    => "backend.$uri.show",
                        'edit'    => "backend.$uri.edit",
                        'update'  => "backend.$uri.update",
                        'destroy' => "backend.$uri.destroy",
                    ]);
            });
    }
}
