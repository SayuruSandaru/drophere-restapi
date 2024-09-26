<?php

namespace App\Repository;

use Exception;

require_once __DIR__ . '/../Utility/DBconfig.php';

class ReservationRepository
{
    private $DB;

    public function __construct()
    {
        $this->DB = getDBConnection();
    }

    public function createReservationPassenger($userId, $driverId, $rideId, $status, $price, $type)
    {
        try {
            $stmt = $this->DB->prepare("INSERT INTO reservation (user_id, driver_id, ride_id, status, price, type) 
                                        VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisds", $userId, $driverId, $rideId, $status, $price, $type);
            $stmt->execute();

            if ($stmt->affected_rows == 0) {
                throw new Exception("Error in creating reservation");
            }
            return $this->DB->insert_id;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("MySQL Error: " . $e->getMessage());
        }
    }

    public function createPassengerService($reservedCapacity, $reservationId)
    {
        try {
            $stmt = $this->DB->prepare("INSERT INTO passenger_service (reserved_capacity, reservation_id) VALUES (?, ?)");
            if ($stmt === false) {
                throw new Exception("Failed to prepare statement: " . $this->DB->error);
            }
            $stmt->bind_param("ii", $reservedCapacity, $reservationId);
            $stmt->execute();
            if ($stmt->affected_rows == 0) {
                throw new Exception("Error in creating passenger service entry");
            }
            return $this->DB->insert_id;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("MySQL Error: " . $e->getMessage());
        }
    }

    public function createDeliveryService($recipientName, $recipientAddress, $recipientPhone, $weight, $signature = null, $reservationId)
    {
        try {
            $stmt = $this->DB->prepare("INSERT INTO delivery_service (recipient_name, recipient_address, recipient_phone, weight, signature, reservation_id) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception("Failed to prepare statement: " . $this->DB->error);
            }
    
            $stmt->bind_param("sssdsi", $recipientName, $recipientAddress, $recipientPhone, $weight, $signature, $reservationId);
            
            $stmt->execute();
    
            if ($stmt->affected_rows == 0) {
                throw new Exception("Error in creating delivery service entry");
            }
            
            return $this->DB->insert_id;
    
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("MySQL Error: " . $e->getMessage());
        }
    }
    

    public function getAvailableReservations($status, $user_id)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM reservation
                                        WHERE reservation.status = ? AND reservation.user_id = ?");
            $stmt->bind_param("si", $status, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $reservations = [];
            while ($row = $result->fetch_assoc()) {
                $reservations[] = $row;
            }

            return $reservations;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Failed to retrieve available reservations with passenger service details: " . $e->getMessage());
        }
    }



    public function updateReservationStatus($reservationId, $newStatus)
    {
        try {
            $stmt = $this->DB->prepare("UPDATE reservation SET status = ? WHERE reservation_id = ?");
            $stmt->bind_param("si", $newStatus, $reservationId);
            $stmt->execute();

            if ($stmt->affected_rows == 0) {
                throw new Exception("No reservation found or status unchanged");
            }
            return true;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Failed to update reservation status: " . $e->getMessage());
        }
    }

    public function getDeliveryDetails($reservationId)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM delivery_service WHERE reservation_id = ?");
            $stmt->bind_param("i", $reservationId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                throw new Exception("No delivery service details found");
            }

            return $result->fetch_assoc();
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Failed to retrieve delivery service details: " . $e->getMessage());
        }
    }

    public function getReservationByID($reservationId)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM reservation WHERE reservation_id = ?");
            $stmt->bind_param("i", $reservationId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                throw new Exception("No reservation found");
            }

            return $result->fetch_assoc();
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Failed to retrieve reservation details: " . $e->getMessage());
        }
    }

    public function updateDeliverySignature($reservationId, $signature)
    {
        try {
            $stmt = $this->DB->prepare("UPDATE delivery_service SET signature = ? WHERE reservation_id = ?");
            $stmt->bind_param("si", $signature, $reservationId);
            $stmt->execute();

            if ($stmt->affected_rows == 0) {
                throw new Exception("No delivery service details found or signature unchanged");
            }
            return true;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception("Failed to update delivery signature: " . $e->getMessage());
        }
    }
}
