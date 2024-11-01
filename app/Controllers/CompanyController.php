<?php

namespace App\Controllers;

use App\Models\Company;
use App\Repositories\CompanyRepository;

class CompanyController
{
    private CompanyRepository $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function index()
    {
        return $this->companyRepository->findAll();
    }

    public function store($request)
    {
        if ($request['user']->getRole() !== 'ROLE_SUPER_ADMIN') {
            return 'Unauthorized';
        }

        $company = new Company($request['name']);

        return $this->companyRepository->create($company);
    }
}
