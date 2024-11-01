<?php

use App\Controllers\UserController;
use App\Models\User;
use App\Repositories\UserRepository;
use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase
{
    private UserController $userController;

    private UserRepository $userRepository;

    protected function setUp(): void
    {
        // Mock the UserRepository
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userController = new UserController($this->userRepository, 'test_secret');
    }

    public function testUserCreation()
    {
        // Define a mock user
        $user = new User('Test User', 'ROLE_USER', null);

        $this->userRepository->expects($this->once())
            ->method('create')
            ->with($user)
            ->willReturn($user);

        $result = $this->userController->store([
            'Authorization' => 'Bearer '.'valid_jwt',
            'name' => 'Test User',
            'role' => 'ROLE_USER',
        ]);

        $this->assertEquals($user, $result);
    }
}
