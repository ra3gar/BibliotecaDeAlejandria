<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if ($user->role !== $role) {
            return $user->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('catalogo');
        }

        return $next($request);
    }
}
