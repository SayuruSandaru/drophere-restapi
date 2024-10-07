<?php

use App\Router\Router;
use App\Controller\VehicleController;
use App\Middleware\AuthMiddleware;
use App\Middleware\InputValidationMiddleware;

function registerVehicleRoutes(Router $router)
{
    $vehicleValidation = new InputValidationMiddleware([
        'type' => 'required',
        'capacity' => 'required',
        'available' => 'required',
        'license_plate' => 'required',
        'model' => 'required',
        'year' => 'required',
        'image_url' => 'required'
    ]);

    $vehicleController = new VehicleController();
    $authMiddleware = new AuthMiddleware();

    $router->post('/vehicle/add', [$authMiddleware, $vehicleValidation], function ($request) use ($vehicleController) {
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

    $router->get('/vehicle-fees', [$authMiddleware], function ($request) use ($vehicleController) {
        $vehicleController->getVehicleFeeDetails();
    });

    $router->post('/vehicle-fee/update', [$authMiddleware], function ($request) use ($vehicleController) {
        $vehicleController->updateVehicleFee($request);
    });
}
