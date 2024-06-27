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

    public function get($uri, $middlewares, $callback)
    {
        $this->addRoute('GET', $uri, $middlewares, $callback);
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

        $parsedUri = parse_url($uri);
        $path = $parsedUri['path'];

        $route = $this->matchRoute($method, $path);

        if ($route) {
            // Initialize the request array and decode the JSON body if present
            $request = array_merge($_GET, $_POST);
            $jsonRequestBody = json_decode(file_get_contents('php://input'), true);
            if (is_array($jsonRequestBody)) {
                $request = array_merge($request, $jsonRequestBody);
            }

            $params = $route['params'];

            // Define the final action to call the callback
            $next = function ($req) use ($route, $params) {
                if (is_callable($route['callback'])) {
                    call_user_func($route['callback'], $req, ...array_values($params));
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

    private function matchRoute($method, $path)
    {
        if (!isset($this->routes[$method])) {
            return false;
        }

        foreach ($this->routes[$method] as $routeUri => $route) {
            $routeUriPattern = preg_replace('/{([^}]+)}/', '([^/]+)', $routeUri);
            $routeUriPattern = str_replace('/', '\/', $routeUriPattern);
            $routeUriPattern = '/^' . $routeUriPattern . '$/';

            if (preg_match($routeUriPattern, $path, $matches)) {
                array_shift($matches);
                $params = [];

                if (preg_match_all('/{([^}]+)}/', $routeUri, $paramNames)) {
                    $paramNames = $paramNames[1];
                    foreach ($paramNames as $index => $name) {
                        $params[$name] = $matches[$index];
                    }
                }

                $route['params'] = $params;
                return $route;
            }
        }

        return false;
    }
}
