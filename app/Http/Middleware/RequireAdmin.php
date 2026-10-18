<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireAdmin
{
    public function handle(
        Request $request,
        Closure $next
    ) {
        $user = $request->attributes->get('currentUser');

        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => 'Akses hanya untuk admin.',
                ],
            ], 403);
        }

        return $next($request);
    }
}