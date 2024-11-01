<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241101082636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE users (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL CHECK (LENGTH(name) >= 3),
            role VARCHAR(20) NOT NULL CHECK (role IN ('ROLE_USER', 'ROLE_COMPANY_ADMIN', 'ROLE_SUPER_ADMIN')),
            company_id INTEGER REFERENCES companies(id)
        )");

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS users');

    }
}
