<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $role;

    /**
     * @ORM\ManyToOne(targetEntity="Company")
     *
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=true)
     */
    private ?Company $company;

    public function __construct(string $name, string $role, ?Company $company = null)
    {
        $this->name = $name;
        $this->role = $role;
        $this->company = $company;
    }

    // Getters and Setters here
}
