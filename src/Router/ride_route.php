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
        'status' => 'required',
        'start_time' => 'required',
        'current_location' => 'required',
        'route' => 'required',
        'start_location' => 'required',
        'end_location' => 'required',
    ]);

    // Create a new ride
    $router->post('/ride/create', [$authMiddleware, $rideValidation], function ($request) use ($rideController) {
        $rideController->createRide($request);
    });

    // Get ride details by ID
    $router->get('/ride/{rideId}', [$authMiddleware], function ($request, $rideId) use ($rideController) {
        $rideController->getRideById(['ride_id' => $rideId]);
    });

    // Get all rides
    $router->get('/rides', [$authMiddleware], function ($request) use ($rideController) {
        $rideController->getAllRides();
    });
}
