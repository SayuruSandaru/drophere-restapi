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
            throw new Exception($e->getMessage());
        }
    }

    public function createPassengerService($reservedCapacity, $reservationId)
    {
        try {
            $stmt = $this->DB->prepare("INSERT INTO passenger_service (reserved_capacity, reservation_id) 
                                        VALUES (?, ?)");
            $stmt->bind_param("ii", $reservedCapacity, $reservationId);
            $stmt->execute();
            if ($stmt->affected_rows == 0) {
                throw new Exception("Error in creating passenger service entry");
            }
            return $this->DB->insert_id;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function getAvailableReservations($status)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM reservation
                                        WHERE reservation.status = ?");
            $stmt->bind_param("s", $status);
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
}
