<?php

namespace App\Service;

use App\Repository\AuthRepository;

class AuthService
{
    private $secretKey = "drophere-iit10-secret-key";
    private $algorithm = "HS256";
    private $authenticationRepository;
    private $token;

    public function __construct()
    {
        $this->authenticationRepository = new AuthRepository();
        $this->token = new JWTService();
    }

    public function generateToken($user)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600 * 24 * 30; // Token valid for 30 days
        $payload = array(
            'userid' => $user['id'] ?? null,
            'email' => $user['email'] ?? null,
            'firstname' => $user['firstname'] ?? null,
            'lastname' => $user['lastname'] ?? null,
            'username' => $user['username'] ?? null,
            'phone' => $user['phone'] ?? null,
            'iat' => $issuedAt,
            'exp' => $expirationTime
        );

        return $this->token->encode($payload);
    }

    public function validateToken($token): array
    {
        try {
            $decodedPayload = $this->token->decode($token);
            if ($decodedPayload !== null) {
                return [
                    "status" => true,
                    "data" => $decodedPayload
                ];
            } else {
                return [
                    "status" => false,
                    "message" => "Token validation failed or token expired"
                ];
            }
        } catch (\Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }


    public function getUserIdFromToken($token)
    {
        $decodedPayload = $this->token->decode($token);
        if ($decodedPayload !== null && isset($decodedPayload['userid'])) {
            return $decodedPayload['userid'];
        }
        return null;
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

    public function adminLogin($username, $password)
    {
        try {
            $admin = $this->authenticationRepository->adminLogin($username, $password);
            if ($admin !== NULL) {
                return [
                    "status" => true,
                    "admin" => $admin
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

    public function updateUser($email, $firstname = null, $lastname = null, $username = null, $phone = null, $profile_image = null)
    {
        try {

            $fields = [];
            $params = [];
            $types = '';

            if ($firstname !== null) {
                $fields[] = "firstname = ?";
                $params[] = $firstname;
                $types .= 's';
            }
            if ($lastname !== null) {
                $fields[] = "lastname = ?";
                $params[] = $lastname;
                $types .= 's';
            }
            if ($username !== null) {
                $fields[] = "username = ?";
                $params[] = $username;
                $types .= 's';
            }
            if ($phone !== null) {
                $fields[] = "phone = ?";
                $params[] = $phone;
                $types .= 's';
            }
            if ($profile_image !== null) {
                $fields[] = "profile_image = ?";
                $params[] = $profile_image;
                $types .= 's';
            }

            if (empty($fields)) {
                return [
                    "status" => false,
                    "message" => "No fields to update"
                ];
            }
            $params[] = $email;
            $types .= 's';

            $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE email = ?";

            $user = $this->authenticationRepository->updateUser($sql, $types, $params);

            if ($user !== NULL) {
                return [
                    "status" => true,
                    "userId" => $user
                ];
            } else {
                return [
                    "status" => false,
                    "message" => "Error in updating user"
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
