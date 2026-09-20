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
                $token = $this->authenticationService->generateToken($user['user']);
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_SUCCESS,
                    [
                        "message" => "User logged in successfully",
                        "token" => $token,
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
                    "message" => isset($user['message']) ? $user['message'] : 'An error occurred'
                ],
                400
            );
        }
    }

    public function updateUser($data)
    {
        try {
            $email = $data['email'] ?? null;
            if (!$email) {
                throw new \Exception("Email is required");
            }

            $firstname = $data['firstname'] ?? null;
            $lastname = $data['lastname'] ?? null;
            $username = $data['username'] ?? null;
            $phone = $data['phone'] ?? null;
            $profile_image = $data['profile_image'] ?? null;

            $user = $this->authenticationService->updateUser($email, $firstname, $lastname, $username, $phone, $profile_image);

            if ($user['status']) {
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_SUCCESS,
                    [
                        "message" => "User updated successfully",
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
                    "message" => $e->getMessage()
                ],
                400
            );
        }
    }

    public function adminLogin($data)
    {
        try {
            $email = $data['email'];
            $password = $data['password'];
            $admin = $this->authenticationService->adminLogin($email, $password);
            if ($admin['status']) {
                $token = $this->authenticationService->generateToken($admin['admin']);
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_SUCCESS,
                    [
                        "message" => "Admin logged in successfully",
                        "token" => $token,
                    ],
                    200
                );
            } else {
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_ERROR,
                    [
                        "message" => $admin['message']
                    ],
                    400
                );
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                [
                    "message" => $e->getMessage()
                ],
                400
            );
        }
    }

    public function forgotPassword($email){
        try {
            $user = $this->authenticationService->forgotPassword($email);
            if ($user['status']) {
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_SUCCESS,
                    [
                        "message" => "Password reset link sent successfully",
                        "token" => $user['token']
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
                    "message" => $e->getMessage()
                ],
                400
            );
        }
    }

    public function resetPassword($token, $newPassword)
    {
        try {
            $user = $this->authenticationService->resetPassword($newPassword, $token);
            if ($user['status']) {
                ResponseUtility::sendJsonResponse(
                    ResponseUtility::STATUS_SUCCESS,
                    [
                        "message" => "Password reset successfully",
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
                    "message" => $e->getMessage()
                ],
                400
            );
        }
    }
}
