<?php

namespace App\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController
{
    private UserRepository $userRepository;

    private string $jwtSecret;

    public function __construct(UserRepository $userRepository, string $jwtSecret)
    {
        $this->userRepository = $userRepository;
        $this->jwtSecret = $jwtSecret;
    }

    // Get list of users based on role
    public function index($request)
    {
        $user = $this->authenticate($request);

        if ($user->getRole() === 'ROLE_USER') {
            return $this->userRepository->findByCompany($user->getCompany());
        } elseif ($user->getRole() === 'ROLE_COMPANY_ADMIN') {
            return $this->userRepository->findByCompany($user->getCompany());
        } elseif ($user->getRole() === 'ROLE_SUPER_ADMIN') {
            return $this->userRepository->findAll();
        }

        return null;
    }

    // Create a new user
    public function store($request)
    {
        $authUser = $this->authenticate($request);

        if ($authUser->getRole() !== 'ROLE_SUPER_ADMIN' && $authUser->getRole() !== 'ROLE_COMPANY_ADMIN') {
            return 'Unauthorized';
        }

        $user = new User($request['name'], $request['role'], $authUser->getCompany());

        return $this->userRepository->create($user);
    }

    // Authenticate using JWT token
    private function authenticate($request): ?User
    {
        if (! isset($request['Authorization'])) {
            throw new \Exception('Authorization header missing');
        }

        $jwt = str_replace('Bearer ', '', $request['Authorization']);
        $decoded = JWT::decode($jwt, new Key($this->jwtSecret, 'HS256'));

        return $this->userRepository->find($decoded->sub);
    }
}
