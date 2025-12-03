<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is authenticated and is an admin, redirect to admin dashboard
        if (auth()->check() && auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard')->with('info', 'Admins can only access the admin panel.');
        }
        
        return $next($request);
    }
}
