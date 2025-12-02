<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Redirect to login if not authenticated
        if (!auth()->check()) {
            return redirect('/')->with('error', 'Please log in to access this page.');
        }

        // Allow admins to access user routes - they'll see user interface but can return to admin
        // This allows admins to preview/test the user experience
        return $next($request);
    }
}
