<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if (! $user || $user->role !== $role) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Forbidden.',
            ], 403);
        }

        return $next($request);
    }
}

