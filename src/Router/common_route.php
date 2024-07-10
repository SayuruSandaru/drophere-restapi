<?php

use App\Router\Router;
use App\Controller\FileUploadController;
use App\Service\FileUploadService;
use App\Repository\FileUploadRepository;
use App\Middleware\FileUploadValidationMiddleware;

function registerCommonRoutes(Router $router)
{
    $fileUploadRepository = new FileUploadRepository();
    $fileUploadService = new FileUploadService($fileUploadRepository);
    $fileUploadController = new FileUploadController($fileUploadService);

    $uploadValidation = new FileUploadValidationMiddleware();

    // Attach middleware and callback correctly
    $router->post('/common/upload', [$uploadValidation], function ($request) use ($fileUploadController) {
        // Assuming $fileUploadController->upload() needs the request data
        $fileUploadController->upload($request);
    });
}
