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
        
        // If user is an admin, redirect to admin dashboard
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard')->with('info', 'Admins should use the admin panel.');
        }
        
        return $next($request);
    }
}
