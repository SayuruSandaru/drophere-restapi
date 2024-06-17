<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Router/auth_route.php';
require_once __DIR__ . '/src/Router/common_route.php';
require_once __DIR__ . '/src/Router/driver_route.php';
require_once __DIR__ . '/src/Router/vehicle_router.php';
require_once __DIR__ . '/src/Utility/cors.php';

use App\Router\Router;

$router = new Router();

registerAuthRoutes($router);
registerCommonRoutes($router);
registerDriverRoutes($router);
registerVehicleRoutes($router);

$router->dispatch();
