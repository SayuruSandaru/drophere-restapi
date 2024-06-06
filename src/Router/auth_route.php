    <?php

    use App\Router\Router;
    use App\Middleware\InputValidationMiddleware;
    use App\Controller\AuthController;



    function registerAuthRoutes(Router $router)
    {
        $authController = new AuthController();
        $loginValidation = new InputValidationMiddleware([
            'email' => 'email',
            'password' => 'required',
        ]);

        $registerValidation = new InputValidationMiddleware([
            'email' => 'email',
            'password' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => 'required',
            'phone' => 'required'
        ]);

        $router->addMiddleware($loginValidation);
        $router->addMiddleware($registerValidation);

        $router->post('/login', function () use ($authController) {
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $authController->login($data);
            // ResponseUtility::sendJsonResponse($response, true);
        });


        $router->post('/register', function () use ($authController) {
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $authController->register($data);
            // ResponseUtility::sendJsonResponse($response, true);
        });
    }
