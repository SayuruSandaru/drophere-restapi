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
    


public function findById($id) {
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
}



?>