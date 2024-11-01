<?php

namespace App\Repositories;

use App\Models\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class UserRepository extends EntityRepository
{
    //    private EntityManager $entityManager;
    //
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager, new ClassMetadata(User::class));
    }

    public function findd(int $id): ?User
    {
        return $this->entityManager->find(User::class, $id);
    }

    public function findByName(string $name): ?User
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function create(User $user): User
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    // Additional repository methods
}
