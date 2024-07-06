<?php

use App\Router\Router;
use App\Middleware\InputValidationMiddleware;
use App\Controller\RideController;
use App\Middleware\AuthMiddleware;

function registerRideRoutes(Router $router)
{
    // Instantiate the necessary controller and middleware
    $rideController = new RideController();
    $authMiddleware = new AuthMiddleware();
    $rideValidation = new InputValidationMiddleware([
        'driver_id' => 'required',
        'vehicle_id' => 'required',
        'status' => 'required',
        'start_time' => 'required',
        'current_location' => 'required',
        'route' => 'required',
        'start_location' => 'required',
        'end_location' => 'required',
    ]);

    $directionsValidation = new InputValidationMiddleware([
        'pickup_lat' => 'required',
        'pickup_lng' => 'required',
        'destination_lat' => 'required',
        'destination_lng' => 'required',
    ]);

    $router->post('/ride/create', [$authMiddleware, $rideValidation], function ($request) use ($rideController) {
        $rideController->createRide($request);
    });
    $router->get('/ride/{rideId}', [$authMiddleware], function ($request, $rideId) use ($rideController) {
        $rideController->getRideById(['ride_id' => $rideId]);
    });
    $router->get('/rides', [$authMiddleware], function ($request) use ($rideController) {
        $rideController->getAllRides();
    });

    $router->post('/rides/search', [$authMiddleware], function ($request) use ($rideController) {
        $rideController->searchRides($request);
    });

    $router->post('/rides/direction', [$authMiddleware, $directionsValidation], function ($request) use ($rideController) {
        $rideController->getDirections($request);
    });
}
