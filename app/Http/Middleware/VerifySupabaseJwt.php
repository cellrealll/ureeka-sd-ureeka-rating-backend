<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Throwable;

class VerifySupabaseJwt
{
    public function handle(
        Request $request,
        Closure $next
    ) {
        $header = $request->header('Authorization');

        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return response()->json([
                'error' => [
                    'code' => 'UNAUTHORIZED',
                    'message' => 'Token autentikasi diperlukan.',
                ],
            ], 401);
        }

        $token = substr($header, 7);

        try {
            $secret = config('services.supabase.jwt_secret');

            if (!$secret) {
                throw new \RuntimeException(
                    'JWT secret belum dikonfigurasi.'
                );
            }

            $decoded = JWT::decode(
                $token,
                new Key($secret, 'HS256')
            );

            $claims = (array) $decoded;

            if (
                empty($claims['sub']) ||
                empty($claims['email']) ||
                empty($claims['exp']) ||
                $claims['exp'] <= time()
            ) {
                throw new \UnexpectedValueException(
                    'Token tidak valid.'
                );
            }

            $user = User::firstOrCreate(
                ['supabase_id' => $claims['sub']],
                [
                    'email' => $claims['email'],
                    'name' => $claims['user_metadata']['full_name']
                        ?? $claims['user_metadata']['name']
                        ?? $claims['email'],
                    'avatar_url' =>
                        $claims['user_metadata']['avatar_url'] ?? null,
                    'role' => 'member',
                    'is_active' => true,
                ]
            );

            if (!$user->is_active) {
                return response()->json([
                    'error' => [
                        'code' => 'FORBIDDEN',
                        'message' => 'Akun tidak aktif.',
                    ],
                ], 403);
            }

            $request->attributes->set(
                'currentUser',
                $user
            );

        } catch (Throwable $e) {
            return response()->json([
                'error' => [
                    'code' => 'UNAUTHORIZED',
                    'message' => 'Token tidak valid atau kedaluwarsa.',
                ],
            ], 401);
        }

        return $next($request);
    }
}