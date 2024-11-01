<?php

namespace App\Repositories;

use App\Models\User;
use Doctrine\ORM\EntityManager;

class UserRepository
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(int $id): ?User
    {
        return $this->entityManager->find(User::class, $id);
    }

    public function create(User $user): User
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    // Additional repository methods
}
