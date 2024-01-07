<?php

namespace app\routes;

use app\helpers\Request;
use app\helpers\Uri;

class Router {

    const CONTROLLER_NAMESPACE = 'app\\controllers';

    public static function load($controller, $method)
    {
        try {
            $controllerNamespace = self::CONTROLLER_NAMESPACE.'\\'.$controller;

            if (!class_exists($controllerNamespace)) {
                throw new \Exception("Controller $controller não existe.");
            }

            $controllerNamespace = new $controllerNamespace;

            if (!method_exists($controllerNamespace, $method)) {
                throw new \Exception("Método $method não existe em $controller.");
            }
            $controllerNamespace->$method();
        } catch (\Throwable $th) {
            echo $th->getMessage();
            echo '<br />';
        }
    }

    public static function routes(): array
    {
        return [
            'get' => [
                '/' => fn() => self::load('HomeController', 'index')
            ],
            'post' => [
                '/login' => fn() => self::load('LoginController', 'index'),
                '/register' => fn() => self::load('LoginController', 'create'),
            ]
        ];
    }

    public static function execute()
    {
        try {
            $routes = self::routes();
            $method = Request::get();
            $uri = Uri::get();

            if (!isset($routes[$method])) {
                throw new \Exception("$method) not found.");
            }

            if (!array_key_exists($uri, $routes[$method])) {
                throw new \Exception("$method $uri not found.");
            }

            $router = $routes[$method][$uri]; 

            if (!is_callable($router)) {
                throw new \Exception("$method $uri is not callable.");
            }

            $router();
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}