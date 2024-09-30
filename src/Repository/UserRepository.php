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


    public function deleteUser($id)
{
    try {
        $stmt = $this->DB->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt === false) {
            throw new Exception("Failed to prepare the SQL statement: " . $this->DB->error);
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows == 0) {
            throw new Exception("No user found with the provided ID");
        }

    } catch (\mysqli_sql_exception $e) {
        error_log($e->getMessage());
        throw new Exception("Database error: " . $e->getMessage());
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

    public function addReview($description, $rating, $driver_id, $user_id)
    {
        try {
            $stmt = $this->DB->prepare("INSERT INTO reviews (description, rating, driver_id, user_id) VALUES (?, ?, ?, ?)");
            if ($stmt === false) {
                throw new \Exception("Failed to prepare statement: " . $this->DB->error);
            }
            $stmt->bind_param("siii", $description, $rating, $driver_id, $user_id);
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
            $stmt = $this->DB->prepare("
                SELECT r.review_id, r.description, r.rating, r.driver_id, u.username
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.driver_id = ?
            ");
            if ($stmt === false) {
                throw new \Exception("Failed to prepare statement: " . $this->DB->error);
            }
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


    public function createDispute($userId, $category, $status, $message)
    {
        try {
            // Insert into the dispute table
            $stmt = $this->DB->prepare("INSERT INTO dispute (Category, Status, Message) VALUES (?, ?, ?)");
            if ($stmt === false) {
                throw new Exception("Failed to prepare the SQL statement for dispute: " . $this->DB->error);
            }
            $stmt->bind_param("sss", $category, $status, $message);
            $stmt->execute();
    
            if ($stmt->affected_rows == 0) {
                throw new Exception("Error in creating dispute: No rows affected.");
            }
    
            // Get the last inserted dispute ID
            $disputeId = $this->DB->insert_id;
            error_log("Inserted dispute ID: " . $disputeId); // Log for debugging
    
            // Insert into the user_dispute table
            $stmt = $this->DB->prepare("INSERT INTO user_dispute (UserId, DisputeId) VALUES (?, ?)");
            if ($stmt === false) {
                throw new Exception("Failed to prepare the SQL statement for user_dispute: " . $this->DB->error);
            }
            $stmt->bind_param("ii", $userId, $disputeId);
            
            // Log the values being inserted for debugging
            error_log("UserId: " . $userId . " | DisputeId: " . $disputeId);
    
            $stmt->execute();
    
            if ($stmt->affected_rows == 0) {
                throw new Exception("Error in creating user_dispute link: No rows affected.");
            }
    
            return $disputeId;
    
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception("General error: " . $e->getMessage());
        }
    }


    public function getAllDisputes()
    {
        try {

            $stmt = $this->DB->prepare("SELECT dispute.*, user_dispute.UserId 
                                    FROM dispute 
                                    JOIN user_dispute ON dispute.DisputeId = user_dispute.DisputeId");
            $stmt->execute();
            $result = $stmt->get_result();
            $disputes = [];
            while ($row = $result->fetch_assoc()) {
                $disputes[] = $row;
            }
            return $disputes;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        }
    }



    public function getDisputeById($disputeId)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM dispute WHERE DisputeId = ?");
            if ($stmt === false) {
                throw new Exception("Failed to prepare the SQL statement: " . $this->DB->error);
            }

            $stmt->bind_param("i", $disputeId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                throw new Exception("Dispute not found with ID: $disputeId");
            }

            return $result->fetch_assoc();
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }


    public function updateStatus($disputeId, $status)
    {
        try {
            $stmt = $this->DB->prepare("UPDATE dispute SET Status = ? WHERE DisputeId = ?");
            if ($stmt === false) {
                throw new Exception("Failed to prepare the SQL statement: " . $this->DB->error);
            }

            $stmt->bind_param("si", $status, $disputeId);
            $stmt->execute();

            if ($stmt->affected_rows == 0) {
                throw new Exception("Error in updating status: No rows affected.");
            }

            return true;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception("General error: " . $e->getMessage());
        }
    }


    public function updateUserStatus($userId, $status)
{
    try {
        $stmt = $this->DB->prepare("UPDATE users SET status = ? WHERE id = ?");
        if ($stmt === false) {
            throw new Exception("Failed to prepare the SQL statement: " . $this->DB->error);
        }

        $stmt->bind_param("si", $status, $userId);
        $stmt->execute();

        if ($stmt->affected_rows == 0) {
            throw new Exception("Error in updating status: No rows affected.");
        }

        return true;
    } catch (\mysqli_sql_exception $e) {
        error_log($e->getMessage());
        throw new Exception("Database error: " . $e->getMessage());
    } catch (Exception $e) {
        error_log($e->getMessage());
        throw new Exception("General error: " . $e->getMessage());
    }
}
}
