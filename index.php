<?php
require_once __DIR__ . '/src/Router/Router.php';
require_once __DIR__ . '/src/Controller/AuthController.php';
require_once __DIR__ . '/src/Service/AuthService.php';
require_once __DIR__ . '/src/Utility/ResponseUtility.php';
require_once __DIR__ . '/src/Repository/AuthRepository.php';
require_once __DIR__ . '/src/Service/UserService.php';
require_once __DIR__ . '/src/Controller/UserController.php';
require_once __DIR__ . '/src/Repository/UserRepository.php';
require_once __DIR__ . '/src/Service/RideService.php';
require_once __DIR__ . '/src/Repository/RideRepository.php';
require_once __DIR__ . '/src/Controller/RideController.php';
require_once __DIR__ . '/src/Repository/VehicleRepository.php';
require_once __DIR__ . '/src/Service/VehicleService.php';
require_once __DIR__ . '/src/Controller/VehicleController.php';
require_once __DIR__ . '/src/Middleware/MiddlewareBase.php';
require_once __DIR__ . '/src/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/src/Service/AuthService.php';
require_once __DIR__ . '/src/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/src/Repository/DriverRepository.php';
require_once __DIR__ . '/src/Repository/UserRepository.php';
require_once __DIR__ . '/src/Service/DriverService.php';
require_once __DIR__ . '/src/Controller/DriverController.php';
require_once __DIR__ . '/src/Middleware/FileUploadValidationMiddleware.php';
require_once __DIR__ . '/src/Controller/FileUploadController.php';
require_once __DIR__ . '/src/Repository/FileUploadRepository.php';
require_once __DIR__ . '/src/Middleware/InputValidationMiddleware.php';
require_once __DIR__ . '/src/Service/JWTService.php';
require_once __DIR__ . '/src/Service/FileUploadService.php';
require_once __DIR__ . '/src/Router/auth_route.php';
require_once __DIR__ . '/src/Router/common_route.php';
require_once __DIR__ . '/src/Router/driver_route.php';
require_once __DIR__ . '/src/Router/vehicle_router.php';
require_once __DIR__ . '/src/Utility/cors.php';
require_once __DIR__ . '/src/Router/ride_route.php';
require_once __DIR__ . '/src/Router/delivery_route.php';
require_once __DIR__ . '/src/Service/DeliveryService.php';
require_once __DIR__ . '/src/Repository/DeliveryRepository.php';
require_once __DIR__ . '/src/Controller/DeliveryController.php';
require_once __DIR__ . '/src/Router/user_router.php';

require_once __DIR__ . '/src/Router/reservation_route.php';
require_once __DIR__ . '/src/Repository/ReservationRepository.php';
require_once __DIR__ . '/src/Service/ReservationService.php';
require_once __DIR__ . '/src/Controller/ReservationController.php';
require_once __DIR__ . '/src/Router/user_router.php';

use App\Router\Router;

$router = new Router();

registerAuthRoutes($router);
registerCommonRoutes($router);
registerDriverRoutes($router);
registerVehicleRoutes($router);
registerRideRoutes($router);
registerDeliveryRoutes($router);
registerUserRouter($router);
registerReservationRoutes($router);

$router->dispatch();
