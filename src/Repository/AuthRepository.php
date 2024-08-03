<?php

namespace App\Repository;

use Exception;

require_once __DIR__ . '/../Utility/DBconfig.php';

class AuthRepository
{
    private $DB;

    public function __construct()
    {
        $this->DB = getDBConnection();
    }

    public function login($email, $password)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                throw new Exception("User not found");
            }
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                throw new Exception("Invalid password");
            }
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Error in logging in user");
        }
    }


    public function register($email, $password, $firstname, $lastname, $username, $phone, $profile_image)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM users WHERE email = ?");
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . htmlspecialchars($this->DB->error));
            }
            $stmt->bind_param("s", $email,);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                throw new Exception("A user with this email already exists");
            }
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $this->DB->prepare("INSERT INTO users (email, password, firstname, lastname, username, phone, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . htmlspecialchars($this->DB->error));
            }
            $stmt->bind_param("sssssss", $email, $hashedPassword, $firstname, $lastname, $username, $phone, $profile_image);
            $stmt->execute();
            $result = $stmt->insert_id;

            $stmt->close();

            return $result;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Error in registering user");
        }
    }

    public function updateUser($sql, $types, $params)
    {
        try {
            $stmt = $this->DB->prepare($sql);
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . htmlspecialchars($this->DB->error));
            }

            // Dynamically bind the parameters
            $stmt->bind_param($types, ...$params);

            $stmt->execute();
            if ($stmt->affected_rows === -1) {
                throw new Exception('Execute failed: ' . htmlspecialchars($stmt->error));
            }

            $result = $stmt->affected_rows;
            $stmt->close();

            return $result > 0 ? $params[array_key_last($params)] : null;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Error in updating user");
        }
    }
}
