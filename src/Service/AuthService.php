<?php

namespace App\Service;

use App\Repository\AuthRepository;
use Firebase\JWT\JWT;

class AuthService
{
    private $secretKey = "drophere-iit10-secret-key";
    private $algorithm = "HS256";
    private $authenticationRepository;

    public function __construct()
    {
        $this->authenticationRepository = new AuthRepository();
    }

    public function generateToken($user)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600 * 24;
        $payload = array(
            'userid' => $user['id'],
            'email' => $user['email'],
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'username' => $user['username'],
            'phone' => $user['phone'],
            'iat' => $issuedAt,
            'exp' => $expirationTime
        );

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    public function validateToken($token): array
    {
        try {
            $payload = JWT::decode($token, $this->secretKey, [$this->algorithm]);
            return [
                "status" => true,
                "data" => (array) $payload
            ];
        } catch (\Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

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
