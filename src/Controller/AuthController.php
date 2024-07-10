<?php

namespace App\Controller;

use App\Service\AuthService;
use App\Utility\ResponseUtility;

class AuthController
{
    private $authenticationService;

    public function __construct()
    {
        $this->authenticationService = new AuthService();
    }

    public function login($data)
    {
        try {
            $email = $data['email'];
            $password = $data['password'];
            $user = $this->authenticationService->login($email, $password);
            if ($user['status']) {
                // $token = $this->authenticationService->generateToken($user['user']);
                $_SESSION['user_id'] = $user['user']['id'];
                $_SESSION['username'] = $user['user']['username'];
                $_SESSION['loggedin'] = true;
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_SUCCESS,
                    [
                        "message" => "User logged in successfully",
                    ],
                    200
                );
            } else {
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_ERROR,
                    [
                        "message" => $user['message']
                    ],
                    400
                );
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                [
                    "message" => $user['message']
                ],
                400
            );
        }
    }

    public function register($data)
    {
        try {
            $email = $data['email'];
            $password = $data['password'];
            $firstname = $data['firstname'];
            $lastname = $data['lastname'];
            $username = $data['username'];
            $phone = $data['phone'];
            $profile_image = $data['profile_image'];
            $user = $this->authenticationService->register($username, $password, $firstname, $lastname, $email, $phone, $profile_image);
            if ($user['status']) {
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_SUCCESS,
                    [
                        "message" => "User registered successfully",
                    ],
                    200
                );
            } else {
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_ERROR,
                    [
                        "message" => $user['message']
                    ],
                    400
                );
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                [
                    "message" => $user['message']
                ],
                400
            );
        }
    }
}
