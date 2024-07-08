<?php

namespace App\Service;

use App\Repository\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function addReview($description, $rating, $driver_id)
    {
        try {
            $review_Id = $this->userRepository->addReview($description, $rating, $driver_id);
            if ($review_Id > 0) {
                return [
                    'status' => true,
                    'message' => 'Review added successfully',
                    'id' => $review_Id
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Failed to add review',
                ];
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function getReviewsByDriverId($driver_id)
    {
        try {
            $reviews = $this->userRepository->getReviewsByDriverId($driver_id);
            return [
                'status' => true,
                'reviews' => $reviews
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }



    // public function getReviewById($review_id)
    // {
    //     try {
    //         $review = $this->userRepository->getReviewById($review_id);
    //         return [
    //             'status' => true,
    //             'review' => $review
    //         ];
    //     } catch (\Exception $e) {
    //         error_log($e->getMessage());
    //         return [
    //             'status' => false,
    //             'message' => $e->getMessage()
    //         ];
    //     }
    // }

    // public function getAllReviews()
    // {
    //     try {
    //         $reviews = $this->userRepository->getAllReviews();
    //         return [
    //             'status' => true,
    //             'reviews' => $reviews
    //         ];
    //     } catch (\Exception $e) {
    //         error_log($e->getMessage());
    //         return [
    //             'status' => false,
    //             'message' => $e->getMessage()
    //         ];
    //     }
    // }

   
}
