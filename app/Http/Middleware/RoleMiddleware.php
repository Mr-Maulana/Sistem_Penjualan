<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        $allowed = collect($roles)->filter(fn ($r) => $r !== null && $r !== '')->map(fn ($r) => strtolower(trim($r)))->values();

        if ($allowed->isEmpty()) {
            return $next($request);
        }

        $role = strtolower((string) ($user->role ?? ''));

        if (!$allowed->contains($role)) {
            abort(403);
        }

        return $next($request);
    }
}

