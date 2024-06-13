<?php

namespace App\Router;

use Exception;

class Router
{
    private $routes = [];

    public function post($uri, $middlewares, $callback)
    {
        $this->addRoute('POST', $uri, $middlewares, $callback);
    }

    private function addRoute($method, $uri, $middlewares, $callback)
    {
        $this->routes[$method][$uri] = [
            'middlewares' => (array)$middlewares,
            'callback' => $callback
        ];
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            $request = json_decode(file_get_contents('php://input'), true);

            // Define the final action to call the callback
            $next = function ($req) use ($route) {
                if (is_callable($route['callback'])) {
                    call_user_func($route['callback'], $req);
                } else {
                    throw new Exception("Callback is not callable.");
                }
            };

            // Process middlewares
            $processMiddlewares = function ($middlewares, $request, $next) {
                $lastCallable = $next;
                while ($middleware = array_pop($middlewares)) {
                    $lastCallable = function ($req) use ($middleware, $lastCallable) {
                        if (is_callable([$middleware, 'handle'])) {
                            return $middleware->handle($req, $lastCallable);
                        } else {
                            throw new Exception("Middleware handle method is not callable.");
                        }
                    };
                }
                return $lastCallable($request);
            };

            // Start processing middlewares
            $processMiddlewares($route['middlewares'], $request, $next);
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
        }
    }
}
