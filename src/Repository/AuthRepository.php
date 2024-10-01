<?php

namespace App\Repository;

use Exception;

require_once __DIR__ . '/../Utility/DBconfig.php';
use App\Repository\EmailRepository; 
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class AuthRepository
{
    private $DB;
    private $emailRepo;

    public function __construct()
    {
        $this->DB = getDBConnection();
        $this->emailRepo = new EmailRepository();
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

    public function adminLogin($email, $password){
        try {
            $stmt = $this->DB->prepare("SELECT * FROM admin WHERE username = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                throw new Exception("Admin not found");
            }
            $admin = $result->fetch_assoc();
            if ($password == $admin['password']) {
                return $admin;
            } else {
                throw new Exception("Invalid password");
            }
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Error in logging in admin");
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


    public function forgotPassword($email)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                throw new Exception("No user found with that email address");
            }

            $user = $result->fetch_assoc();
            $userId = $user['id'];

            $token = bin2hex(random_bytes(50));
            $tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour')); 

            $stmt = $this->DB->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
            $stmt->bind_param("sss", $token, $tokenExpiry, $email);
            $stmt->execute();

            if ($stmt->affected_rows === -1) {
                throw new Exception("Failed to generate password reset token");
            }

            $resetLink = "https://drophere-staging.web.app/reset-password.php?token=" . $token;
            $subject = "Password Reset Request";
            $body = "Click the link below to reset your password:<br><a href='$resetLink'>Reset Password</a>";
            $altBody = "Click the link to reset your password: $resetLink";

            $this->emailRepo->sendEmail($email, $subject, $body, $altBody);

            return $token;    

        } catch (PHPMailerException $e) {
            error_log($e->getMessage());
            throw new Exception("Error sending password reset email.");
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Database error in forgot password process.");
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function resetPassword($token, $newPassword)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expires > NOW()");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                throw new Exception("Invalid or expired token.");
            }

            $user = $result->fetch_assoc();
            $userId = $user['id'];

            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            $stmt = $this->DB->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?");
            $stmt->bind_param("si", $hashedPassword, $userId);
            $stmt->execute();

            if ($stmt->affected_rows === -1) {
                throw new Exception("Failed to reset password.");
            }

            return true;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Error in resetting password.");
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
