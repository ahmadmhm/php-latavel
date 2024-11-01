<?php

namespace App\Controllers\Api;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class BaseController
{
    protected string $jwtSecret;

    // Authenticate using JWT token
    protected function authenticate($request): ?User
    {
        if (! isset($request['Authorization'])) {
            throw new \Exception('Authorization header missing');
        }

        $jwt = str_replace('Bearer ', '', $request['Authorization']);
        $decoded = JWT::decode($jwt, new Key($this->jwtSecret, 'HS256'));

        return $this->userRepository->find($decoded->sub);
    }
}
