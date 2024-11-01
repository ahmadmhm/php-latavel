<?php

require 'vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use App\Controllers\CompanyController;
use App\Controllers\UserController;
use App\Repositories\CompanyRepository;
use App\Repositories\UserRepository;

// Load configuration
$entityManager = require 'app/bootstrap.php';
$jwtSecret = 'your_jwt_secret';

// Dependency injection
$userRepository = new UserRepository($entityManager);
$companyRepository = new CompanyRepository($entityManager);
$userController = new UserController($userRepository, $jwtSecret);
$companyController = new CompanyController($companyRepository);

// Simple Router
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Routes
switch ($requestUri) {
    case '/users':
        if ($requestMethod === 'GET') {
            echo json_encode($userController->index($_SERVER));
        } elseif ($requestMethod === 'POST') {
            echo json_encode($userController->store($_POST));
        }
        break;
    case '/companies':
        if ($requestMethod === 'GET') {
            echo json_encode($companyController->index());
        } elseif ($requestMethod === 'POST') {
            echo json_encode($companyController->store($_POST));
        }
        break;
    default:
        echo '404 Not Found';
        break;
}
