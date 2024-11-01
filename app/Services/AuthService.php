<?php

namespace App\Services;

use Firebase\JWT\JWT;

class AuthService
{
    private string $jwtSecret;

    public function __construct(string $jwtSecret)
    {
        $this->jwtSecret = $jwtSecret;
    }

    public function generateToken($user): string
    {
        $payload = [
            'iss' => 'api.local',
            'sub' => $user->getId(),
            'role' => $user->getRole(),
            'iat' => time(),
            'exp' => time() + 3600, // Token valid for 1 hour
        ];

        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }
}
