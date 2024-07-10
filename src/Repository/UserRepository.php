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


    public function createDispute($userId, $category, $status, $message)
    {
        try {
            $stmt = $this->DB->prepare("INSERT INTO dispute (Category, Status, Message) VALUES (?, ?, ?)");
            if ($stmt === false) {
                throw new Exception("Failed to prepare the SQL statement for dispute: " . $this->DB->error);
            }
            $stmt->bind_param("sss", $category, $status, $message);
            $stmt->execute();
            if ($stmt->affected_rows == 0) {
                throw new Exception("Error in creating dispute: No rows affected.");
            }
            $disputeId = $this->DB->insert_id;
            $stmt = $this->DB->prepare("INSERT INTO user_dispute (UserId, DisputeId) VALUES (?, ?)");
            if ($stmt === false) {
                throw new Exception("Failed to prepare the SQL statement for user_dispute: " . $this->DB->error);
            }
            $stmt->bind_param("ii", $userId, $disputeId);
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
}
