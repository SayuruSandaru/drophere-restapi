<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Router/auth_route.php';

use App\Utility\ResponseUtility;
use App\Router\Router;
use App\Middleware\InputValidationMiddleware;

$router = new Router();

registerAuthRoutes($router);

$router->resolve();
