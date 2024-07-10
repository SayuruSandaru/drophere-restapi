<?php

namespace App\Service;

use App\Repository\AuthRepository;

class AuthService
{
    // private $secretKey = "drophere-iit10-secret-key";
    // private $algorithm = "HS256";
    private $authenticationRepository;
    // private $token;

    public function __construct()
    {
        $this->authenticationRepository = new AuthRepository();
        // $this->token = new JWTService();
    }

    // public function generateToken($user)
    // {
    //     $issuedAt = time();
    //     $expirationTime = $issuedAt + 3600 * 24 * 30; // Token valid for 30 days
    //     $payload = array(
    //         'userid' => $user['id'],
    //         'email' => $user['email'],
    //         'firstname' => $user['firstname'],
    //         'lastname' => $user['lastname'],
    //         'username' => $user['username'],
    //         'phone' => $user['phone'],
    //         'iat' => $issuedAt,
    //         'exp' => $expirationTime
    //     );

    //     return $this->token->encode($payload);
    // }

    // public function validateToken($token): array
    // {
    //     try {
    //         $decodedPayload = $this->token->decode($token);
    //         if ($decodedPayload !== null) {
    //             return [
    //                 "status" => true,
    //                 "data" => $decodedPayload
    //             ];
    //         } else {
    //             return [
    //                 "status" => false,
    //                 "message" => "Token validation failed or token expired"
    //             ];
    //         }
    //     } catch (\Exception $e) {
    //         return [
    //             "status" => false,
    //             "message" => $e->getMessage()
    //         ];
    //     }
    // }


    // public function getUserIdFromToken($token)
    // {
    //     $decodedPayload = $this->token->decode($token);
    //     if ($decodedPayload !== null && isset($decodedPayload['userid'])) {
    //         return $decodedPayload['userid'];
    //     }
    //     return null; // Or handle as per your error management strategy
    // }

    public function login($username, $password)
    {
        try {
            $user = $this->authenticationRepository->login($username, $password);
            if ($user !== NULL) {
                return [
                    "status" => true,
                    "user" => $user
                ];
            } else {
                return [
                    "status" => false,
                    "message" => "Invalid username or password"
                ];
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    public function register($username, $password, $firstname, $lastname, $email, $phone, $profile_image)
    {
        try {
            $user = $this->authenticationRepository->register($email, $password, $firstname, $lastname, $username, $phone, $profile_image);
            if ($user !== NULL) {
                return [
                    "status" => true,
                    "userId" => $user
                ];
            } else {
                return [
                    "status" => false,
                    "message" => "Error in registering user"
                ];
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }
}
