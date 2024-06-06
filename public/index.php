<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Router/auth_route.php';

use App\Utility\ResponseUtility;
use App\Router\Router;
use App\Middleware\InputValidationMiddleware;


error_log("Creating Router instance.");

$router = new Router();

registerAuthRoutes($router);

$router->resolve();
