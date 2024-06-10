<?php

namespace App\Repository;

use Exception;

require_once __DIR__ . '/../Utility/DBconfig.php';

class AuthRepository
{
    private $DB;

    public function __construct()
    {
        error_log("Accessing Db instance via AuthRepo");
        $this->DB = getDBConnection();
        if (!$this->DB) {
            throw new Exception('Failed to connect to the database');
        }
    }

    public function login($email, $password)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM users WHERE email = ?");
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . htmlspecialchars($this->DB->error));
            }
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

    public function register($email, $password, $firstname, $lastname, $username, $phone)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
            if ($stmt === false) {
                throw new Exception('Prepare failed": ' . htmlspecialchars($this->DB->error));
            }
            $stmt->bind_param("ss", $email, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                throw new Exception("A user with this email or username already exists");
            }
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->DB->prepare("INSERT INTO users (email, password, firstname, lastname, username, phone) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception('Prepare failed: ' . htmlspecialchars($this->DB->error));
            }

            $stmt->bind_param("ssssss", $email, $hashedPassword, $firstname, $lastname, $username, $phone);
            $stmt->execute();
            $result = $stmt->insert_id;

            $stmt->close();

            return $result;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Error in registering user");
        }
    }
}
