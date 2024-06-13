<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Router/auth_route.php';
require_once __DIR__ . '/src/Router/common_route.php';
require_once __DIR__ . '/src/Router/driver_route.php';


use App\Router\Router;

$router = new Router();

registerAuthRoutes($router);
registerCommonRoutes($router);
registerDriverRoutes($router);

$router->dispatch();
