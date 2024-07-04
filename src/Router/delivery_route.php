<?php

use App\Router\Router;
use App\Middleware\InputValidationMiddleware;
use App\Controller\DeliveryController;
use App\Middleware\AuthMiddleware;

function registerDeliveryRoutes(Router $router)
{
    $deliveryController = new DeliveryController();
    $authMiddleware = new AuthMiddleware();
    $deliveryValidation = new InputValidationMiddleware([
        'user_id' => 'required',
        'delivery_address' => 'required',
        'delivery_date' => 'required',
        'status' => 'required',
    ]);

    $router->post('/delivery', [$authMiddleware, $deliveryValidation], function ($request) use ($deliveryController) {
        $deliveryController->createDelivery($request);
    });

    $router->get('/delivery/{id}', [$authMiddleware], function ($request, $id) use ($deliveryController) {
        $deliveryController->getDeliveryById(['id' => $id]);
    });

    $router->get('/deliveries', [$authMiddleware], function ($request) use ($deliveryController) {
        $deliveryController->getAllDeliveries();
    });
}
