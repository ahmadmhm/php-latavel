<?php

namespace App\Controllers\Api;

use App\Models\User;
use App\Repositories\UserRepository;

class UserController extends BaseController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
}
