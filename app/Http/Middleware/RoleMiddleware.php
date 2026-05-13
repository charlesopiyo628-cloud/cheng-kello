<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated.');
        }

        switch ($role) {
            case 'admin':
                if (!$user->isAdmin()) {
                    abort(403, 'Unauthorized action.');
                }
                break;
            case 'director':
                if (!$user->isDirector() && !$user->isAdmin()) {
                    abort(403, 'Unauthorized action.');
                }
                break;
            case 'cashier':
                if (!$user->isCashier() && !$user->isAdmin() && !$user->isDirector()) {
                    abort(403, 'Unauthorized action.');
                }
                break;
        }

        return $next($request);
    }
}
