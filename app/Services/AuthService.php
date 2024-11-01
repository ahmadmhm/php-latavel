<?php

namespace App\Services;

use Firebase\JWT\JWT;

class AuthService
{
    public function __construct(private string $jwtSecret = '')
    {
        $this->jwtSecret = getenv('JWT_SECRET') ?: $jwtSecret;
    }

    public function generateToken($user): string
    {
        $payload = [
            'iss' => 'api.local',
            'sub' => $user->getId(),
            'role' => $user->getRole(),
            'iat' => time(),
            'exp' => time() + (3600 * 24), // Token valid for 1 day
        ];

        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }
}
