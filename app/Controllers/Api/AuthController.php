<?php

namespace App\Controllers\Api;

use App\Repositories\UserRepository;

class AuthController extends BaseController
{
    public function __construct(protected UserRepository $userRepository) {}

    public function login()
    {
        $name = ! empty($_POST['name']) ? $_POST['name'] : null;
        if (! $name) {
            unprocessAbleResponse([
                'name' => 'Name field is required to login.',
            ]);
        }

        $user = $this->userRepository->findByName($name);
        print_r($user);
        exit('uu');
    }
}
