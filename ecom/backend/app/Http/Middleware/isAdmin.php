<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $type = 'auth'): Response
    {

        // For login page
        if ($type === 'guest') {

            // Admin already logged in
            if (
                auth()->check() &&
                auth()->user()->role === 'admin'
            ) {
                return redirect()->route('admin.dashboard');
            }

            return $next($request);
        }

        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        if (auth()->user()->role !== 'admin') {
            auth()->logout();

            return redirect()->route('admin.login');
        }
        return $next($request);
    }
}
