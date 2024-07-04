<?php

namespace App\Repository;

use Exception;

require_once __DIR__ . '/../Utility/DBconfig.php';

class DeliveryRepository
{
    private $DB;

    public function __construct()
    {
        $this->DB = getDBConnection();
    }

    public function createDelivery(array $data)
    {
        try {
            $stmt = $this->DB->prepare("INSERT INTO deliveries (user_id, delivery_address, delivery_date, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $data['user_id'], $data['delivery_address'], $data['delivery_date'], $data['status']);
            $stmt->execute();
            if ($stmt->affected_rows == 0) {
                throw new Exception("Error in creating delivery");
            }
            return $this->DB->insert_id;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function getDeliveryById($deliveryId)
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM deliveries WHERE delivery_id = ?");
            $stmt->bind_param("i", $deliveryId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                throw new Exception("Delivery not found");
            }
            return $result->fetch_assoc();
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function getAllDeliveries()
    {
        try {
            $stmt = $this->DB->prepare("SELECT * FROM deliveries");
            $stmt->execute();
            $result = $stmt->get_result();
            $deliveries = [];
            while ($row = $result->fetch_assoc()) {
                $deliveries[] = $row;
            }
            return $deliveries;
        } catch (\mysqli_sql_exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
