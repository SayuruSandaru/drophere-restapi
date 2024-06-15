<?php

use App\Router\Router;
use App\Controller\VehicleController;
use App\Middleware\AuthMiddleware;

function registerVehicleRoutes(Router $router)
{
    $vehicleController = new VehicleController();
    $authMiddleware = new AuthMiddleware();

    $router->post('/vehicle/add', [$authMiddleware], function ($request) use ($vehicleController) {
        $vehicleController->addVehicle($request);
    });

    $router->get('/vehicle/{vehicleId}', [$authMiddleware], function ($request, $vehicleId) use ($vehicleController) {
        $vehicleController->getVehicleById(['vehicle_id' => $vehicleId]);
    });

    $router->get('/vehicles', [$authMiddleware], function ($request) use ($vehicleController) {
        $vehicleController->getAllVehicles();
    });

    $router->get('/vehicles/owner/{ownerId}', [$authMiddleware], function ($request, $ownerId) use ($vehicleController) {
        $vehicleController->getVehiclesByOwnerId(['owner_id' => $ownerId]);
    });
}
