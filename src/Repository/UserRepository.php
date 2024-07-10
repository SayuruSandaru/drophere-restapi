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


    public function createDispute($dispute_id, $status, $category, $email, $phone_no, $message)
    {
        try {
            $stmt = $this->DB->prepare("INSERT INTO dispute ($dispute_id, $status, $category, $email, $phone_no, $message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception("Failed to prepare the SQL statement: " . $this->DB->error);
            }
            $stmt->bind_param("issssssi", $dispute_id, $status, $category, $email, $phone_no, $message);
            $stmt->execute();
            if ($stmt->affected_rows == 0) {
                throw new Exception("Error in creating dispute: No rows affected.");
            }
            $id = $this->DB->insert_id;
            return true;
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
            $stmt = $this->DB->prepare("SELECT * FROM dispute");
            $stmt->execute();
            $result = $stmt->get_result();
            $disputes = [];
            while ($row = $result->fetch_assoc()) {
                $disputes[] = $row;
            }
            return $disputes;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function getdisputeById($dispute_id)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM dispute WHERE dispute_id = ?");
            $stmt->bind_param("i", $dispute_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                throw new Exception("Dispute not found");
            }
            return $result->fetch_assoc();
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function updateStatus($dispute_id, $status)
    {
        try {
            $stmt = $this->DB->prepare("UPDATE dispute SET status = ? WHERE dispute_id = ?");
            if ($stmt === false) {
                throw new Exception("Failed to prepare the SQL statement: " . $this->DB->error);
            }
            $stmt->bind_param("si", $status, $dispute_id);
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
