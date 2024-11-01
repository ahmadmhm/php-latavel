<?php

namespace App;

class ApiRouter
{
    public function __construct()
    {
        //bind post data
        $postData = json_decode(file_get_contents('php://input'), true);
        foreach ($postData as $key => $value) {
            $_POST[$key] = $value;
        }
    }

    private $routes = [];

    public function get($route, $action)
    {
        $this->addRoute('GET', $route, $action);
    }

    public function post($route, $action)
    {
        $this->addRoute('POST', $route, $action);
    }

    public function delete($route, $action)
    {
        $this->addRoute('DELETE', $route, $action);
    }

    private function addRoute($method, $route, $action)
    {
        $this->routes[] = [
            'method' => $method,
            'route' => $this->parseRoute($route),
            'action' => $action,
        ];
    }

    private function parseRoute($route)
    {
        return '/^'.str_replace(['/', '{id}'], ['\/', '(\d+)'], $route).'$/';
    }

    public function dispatch($method, $uri)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['route'], $uri, $matches)) {
                array_shift($matches);

                return call_user_func_array($route['action'], $matches);
            }
        }
        http_response_code(404);
        echo json_encode(['error' => '404 Not Found']);
    }
}
