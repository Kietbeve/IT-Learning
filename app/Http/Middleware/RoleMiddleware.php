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
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Support multiple roles separated by | (OR logic) as well as comma-separated (variadic)
        $parsedRoles = [];
        foreach ($roles as $r) {
            if (is_string($r) && str_contains($r, '|')) {
                $parsedRoles = array_merge($parsedRoles, explode('|', $r));
            } else {
                $parsedRoles[] = $r;
            }
        }
        $parsedRoles = array_map('trim', $parsedRoles);
        
        if (!$request->user()) {
            abort(403, 'Bạn không có quyền truy cập vào trang này.');
        }
        
        // Check if user has ANY of the required roles
        $hasRole = collect($parsedRoles)->contains(fn($r) => $request->user()->hasRole($r));
        
        if (!$hasRole) {
            abort(403, 'Bạn không có quyền truy cập vào trang này.');
        }

        return $next($request);
    }
}
