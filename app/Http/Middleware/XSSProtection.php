<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use HTMLPurifier;
use HTMLPurifier_Config;

class XSSProtection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get all input
        $input = $request->all();

        // Create a new HTMLPurifier instance
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        // Sanitize each input field
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                $input[$key] = $purifier->purify($value);
            }
        }

        // Merge sanitized input back into the request
        $request->merge($input);
        return $next($request);
    }
}
