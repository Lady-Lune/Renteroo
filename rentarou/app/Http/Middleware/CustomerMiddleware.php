<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is customer
        if (auth()->check() && auth()->user()->role === 'customer') {
            return $next($request);
        }

        // Redirect or abort if not customer
        abort(403, 'Unauthorized. Customer access only.');
    }
}