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

    public function createReservationPassanger($userId, $driverId, $rideId, $status, $price)
    {
        try {

            $stmt = $this->DB->prepare("INSERT INTO reservation (user_id, driver_id, ride_id, status, price) 
                            VALUES (?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception("Failed to prepare statement: " . $this->DB->error);
            }
            $stmt->bind_param("iiisd", $userId, $driverId, $rideId, $status, $price);
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

    public function createDeliveryService($recipientName, $recipientAddress, $recipientPhone, $weight, $signature = null)
    {
        try {
            $stmt = $this->DB->prepare("INSERT INTO delivery_service (recipient_name, recipient_address, recipient_phone, weight, signature) VALUES (?, ?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception("Failed to prepare statement: " . $this->DB->error);
            }
            $stmt->bind_param("sssds", $recipientName, $recipientAddress, $recipientPhone, $weight, $signature);
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
}
