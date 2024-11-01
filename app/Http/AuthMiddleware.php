<?php

namespace App\Http;

use App\Models\User;
use App\Repositories\UserRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{
    public function __construct(protected UserRepository $userRepository, readonly string $authTokenKey = 'HTTP_AUTHORIZATION', private string $jwtSecret = '')
    {
        $this->jwtSecret = getenv('JWT_SECRET') ?: $jwtSecret;
    }

    public function authenticate(): ?User
    {
        if (! isset($_SERVER[$this->authTokenKey])) {
            return null;
        }

        try {
            $jwt = str_replace('Bearer ', '', $_SERVER[$this->authTokenKey]);
            $decoded = JWT::decode($jwt, new Key($this->jwtSecret, 'HS256'));
        } catch (\Exception $e) {
            print_r($e->getMessage());

            return null;
        }

        return $this->userRepository->find($decoded->sub);
    }
}
