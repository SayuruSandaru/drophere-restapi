<?php

namespace App\Repository;

use Exception;

class UserRepository
{
    private $DB;

    public function __construct()
    {
        $this->DB = getDBConnection();
    }



    public function getAllUsers()
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM users");
            $stmt->execute();
            $result = $stmt->get_result();
            $users = [];
            while ($row = $result->fetch_assoc()) {
                if ($row) {
                    unset($row['password']);
                    $users[] = $row;
                }
            }
            return $users;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function findById($id)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                throw new Exception("User not found");
            }

            $user = $result->fetch_assoc();

            if ($user) {
                unset($user['password']);

                if ($this->isDriver($id)) {
                    $driverDetails = $this->getDriverDetails($id);
                    $user = array_merge($user, $driverDetails);
                }
            }

            return $user;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    private function isDriver($userId)
    {
        try {
            $stmt = $this->DB->prepare("SELECT COUNT(*) as count FROM drivers WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'] > 0;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    private function getDriverDetails($userId)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM drivers WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                throw new Exception("Driver details not found");
            }
            return $result->fetch_assoc();
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function addReview($description, $rating, $driver_id)
    {
        try {
            $stmt = $this->DB->prepare("INSERT INTO reviews (description, rating, driver_id) VALUES (?, ?, ?)");
            if ($stmt === false) {
                throw new \Exception("Failed to prepare statement: " . $this->DB->error);
            }
            $stmt->bind_param("sii", $description, $rating, $driver_id);
            $stmt->execute();
            if ($stmt->affected_rows == 0) {
                throw new \Exception("Error in adding review");
            }
            $review_Id = $this->DB->insert_id;
            return $review_Id;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new \Exception($e->getMessage());
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function getReviewsByDriverId($driver_id)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM reviews WHERE driver_id = ?");
            $stmt->bind_param("i", $driver_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $reviews = [];
            while ($row = $result->fetch_assoc()) {
                $reviews[] = $row;
            }
            return $reviews;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
