<?php

namespace App\Repositories;

use App\Models\Company;
use Doctrine\ORM\EntityManager;

class CompanyRepository
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(int $id): ?Company
    {
        return $this->entityManager->find(Company::class, $id);
    }

    public function create(Company $company): Company
    {
        $this->entityManager->persist($company);
        $this->entityManager->flush();

        return $company;
    }

    // Additional repository methods
}
