<?php

require 'vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use App\ApiRouter;
use App\Controllers\Api\AuthController;
use App\Controllers\Api\CompanyController;
use App\Controllers\Api\UserController;
use App\Http\AuthMiddleware;
use App\Repositories\CompanyRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;

// Load configuration
$entityManager = require 'app/bootstrap.php';
$envManager = require 'app/Helpers/helpers.php';
$router = new ApiRouter;
loadEnv();

// Dependency injection
$userRepository = new UserRepository($entityManager);
$companyRepository = new CompanyRepository($entityManager);
$authService = new AuthService;
$authController = new AuthController($userRepository);
$userController = new UserController($userRepository);
$companyController = new CompanyController($companyRepository);

// Auth routes
$router->post('/auth/login', [$authController, 'login']);

//needs auth middleware routes
if ((new AuthMiddleware($userRepository))->authenticate()) {
    // User routes
    $router->get('/users', [$userController, 'index']);
    $router->get('/users/{id}', [$userController, 'show']);
    $router->post('/users', [$userController, 'store']);
    $router->delete('/users/{id}', [$userController, 'delete']);

    // Company routes
    $router->get('/companies', [$companyController, 'index']);
    $router->get('/companies/{id}', [$companyController, 'show']);
    $router->post('/companies', [$companyController, 'store']);
}

// Dispatch the request
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

header('Content-Type: application/json');
$router->dispatch($method, $uri);
