<?php

namespace App\Router;

class Router
{
    private $routes = [];
    private $middleware = [];
    public function addMiddleware($middleware)
    {
        $this->middleware[] = $middleware;
    }

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        error_log("URI: $uri");
        $base_path = '/drophere-restapi/public';
        error_log("Base path: $base_path");
        $uri = substr($uri, strlen($base_path));
        error_log("URI after base path removal: $uri");
        $handlerFound = false;
        foreach ($this->middleware as $middleware) {
            if (!$middleware->handle($_REQUEST, function () use (&$handlerFound, $method, $uri) {
                foreach ($this->routes[$method] as $path => $callback) {
                    if ($path === $uri) {
                        call_user_func($callback);
                        $handlerFound = true;
                        return;
                    }
                }
            })) {
                return;
            }
        }

        if (!$handlerFound) {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}
