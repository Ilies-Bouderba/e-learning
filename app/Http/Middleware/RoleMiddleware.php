<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        if (empty($roles)) {
            return $next($request);
        }
        if (auth()->user()->role !== $role) {
            abort(403, 'Unauthorized – ' . ucfirst($role) . ' access only.');
        }

        return $next($request);
    }
}
