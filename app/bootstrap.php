<?php

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

require_once __DIR__.'/../vendor/autoload.php';

$dbParams = [
    'driver' => 'pdo_pgsql',
    'host' => getenv('DB_HOST') ?: 'postgres',
    'port' => getenv('DB_PORT') ?: 5432,
    'dbname' => getenv('DB_DATABASE') ?: 'php_ag_db',
    'user' => getenv('DB_USERNAME') ?: 'php_ag_db_user',
    'password' => getenv('DB_PASSWORD') ?: '123456',
];

$config = ORMSetup::createAttributeMetadataConfiguration(
    [__DIR__ . "/app/Models"], // Path to entities
    true,               // Enable development mode
);

// Create the Entity Manager
$connection = DriverManager::getConnection($dbParams);
$entityManager = new EntityManager($connection, $config);

return $entityManager;
