<?php

use App\Router\Router;
use App\Controller\UserController;
use App\Middleware\AuthMiddleware;
use App\Middleware\InputValidationMiddleware;

function registerUserRoutes(Router $router)
{
    $userValidation = new InputValidationMiddleware([
        'email' => 'required|email',
        'password' => 'required',
        'username' => 'required',
        'firstname' => 'required',
        'lastname' => 'required',
        'profile_image' => 'required'
    ]);
        

    $userController = new UserController();
    $authMiddleware = new AuthMiddleware();

    
    

    $router->get('/users', [$authMiddleware], function ($request) use ($userController) {
        $userController->getAllUsers();
    });

    $router->get('/users/{id}', [$authMiddleware], function ($request, $id) use ($userController) {
        $userController->getUserById($id);
    });
//Get driver details
    $router->get('/users/{id}/details', [$authMiddleware], function ($request, $id) use ($userController) {
        $userController->getUserDetails($id);
    });

    

    
    
}



?>