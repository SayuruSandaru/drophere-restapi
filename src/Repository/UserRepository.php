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

    // public function getReviewById($review_id)
    // {
    //     try {
    //         $stmt = $this->DB->prepare("SELECT * FROM reviews WHERE review_id = ?");
    //         $stmt->bind_param("i", $review_id);
    //         $stmt->execute();
    //         $result = $stmt->get_result();
    //         if ($result->num_rows == 0) {
    //             throw new Exception("Review not found");
    //         }
    //         return $result->fetch_assoc();
    //     } catch (\mysqli_sql_exception $e) {
    //         error_log($e->getMessage());
    //         throw new Exception($e->getMessage());
    //     }
    // }

    // public function getAllReviews()
    // {
    //     try {
    //         $stmt = $this->DB->prepare("SELECT * FROM reviews");
    //         $stmt->execute();
    //         $result = $stmt->get_result();
    //         $reviews = [];
    //         while ($row = $result->fetch_assoc()) {
    //             $reviews[] = $row;
    //         }
    //         return $reviews;
    //     } catch (\mysqli_sql_exception $e) {
    //         error_log($e->getMessage());
    //         throw new Exception($e->getMessage());
    //     }
    // }

  
}
