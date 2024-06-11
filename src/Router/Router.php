<?php

namespace App\Router;

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
            $request = [];  // Initialize an empty request array or build from globals

            $next = function ($req) use ($route) {
                call_user_func($route['callback'], $req);  // Execute the final route callback
            };

            // Process middlewares
            $middlewareResult = array_reduce(array_reverse($route['middlewares']), function ($next, $middleware) {
                return function ($req) use ($middleware, $next) {
                    return $middleware($req, $next);  // Pass the request and the next middleware/callback
                };
            }, $next);

            $middlewareResult($request);  // Start middleware chain with initial request
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
        }
    }
}
