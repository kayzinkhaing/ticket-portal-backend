<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and is an admin
        if (Auth::check() && Auth::user()->name === "Admin") {
            return $next($request); // Proceed to the next middleware or the controller
        }

        // If the user is not an admin, redirect them to the homepage or another page
        return redirect()->route('dashboard')->with('error', 'You do not have admin access.');
    }
}
