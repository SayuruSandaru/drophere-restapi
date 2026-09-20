<?php

use App\Router\Router;
use App\Controller\UserController;
use App\Middleware\AuthMiddleware;
use App\Middleware\InputValidationMiddleware;

function registerUserRouter(Router $router)
{
    $userController = new UserController();
    $authMiddleware = new AuthMiddleware();

    $reviewValidation = new InputValidationMiddleware([
        'description' => 'required',
        'rating' => 'required|integer|min:1|max:5',
        'driver_id' => 'required|integer'
    ]);

    $userController = new UserController();
    $authMiddleware = new AuthMiddleware();

    $router->post('/review/add', [$authMiddleware, $reviewValidation], function ($request) use ($userController) {
        $userController->addReview($request);
    });

    $router->get('/reviews/driver/{driverId}', [$authMiddleware], function ($request, $driverId) use ($userController) {
        $userController->getReviewsByDriverId(['driver_id' => $driverId]);
    });


    // $router->get('/review/{reviewId}', [$authMiddleware], function ($request, $reviewId) use ($userController) {
    //     $userController->getReviewById(['review_id' => $reviewId]);
    // });

    // $router->get('/reviews', [$authMiddleware], function ($request) use ($userController) {
    //     $userController->getAllReviews();
    // });


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

    $router->get('/user', [$authMiddleware], function ($request) use ($userController) {
        $id = $request['userId'];
        $userController->getUserDetails($id);
    });

    $router->delete('/users/delete/{id}', [$authMiddleware], function ($request, $id) use ($userController) {
        $userController->deleteUser($id);
    });

    $router->post('/user/status', [$authMiddleware], function ($request) use ($userController) {
        $userController->updateUserStatus($request);
    });
    


    // Instantiate the necessary controller and middleware
    $userController = new UserController();
    $authMiddleware = new AuthMiddleware();
    $rideValidation = new InputValidationMiddleware([
        'category' => 'required',
        'status' => 'required',
        'message' => 'required',

    ]);

    $router->post('/user/dispute/create', [$authMiddleware, $rideValidation], function ($request) use ($userController) {
        $userController->createDispute($request);
    });

    $router->get('/user/dispute/{disputeId}', [$authMiddleware], function ($request, $disputeId) use ($userController) {
        $userController->getDisputeById($disputeId);
    });
    $router->get('/user/disputes', [$authMiddleware], function ($request) use ($userController) {
        $userController->getAllDisputes();
    });
    $router->post('/user/dispute/status', [$authMiddleware], function ($request) use ($userController) {
        $userController->updateStatus($request);
    });
}
