<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Repository\DriverRepository;

class UserService
{
    private $userRepository;
    private $driverRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->driverRepository = new DriverRepository();
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





    public function getAllUsers()
    {
        try {
            $users = $this->userRepository->getAllUsers();
            return [
                'status' => true,
                'users' => $users
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function getUserById($id)
    {
        try {
            $user = $this->userRepository->findById($id);
            return ['status' => true, 'user' => $user];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return ['status' => false, 'message' => 'User not found'];
        }
    }


    public function getUserDetails($id)
    {
        try {
            $user = $this->userRepository->findById($id);

            if (!$user) {
                return ['status' => false, 'message' => 'User not found'];
            }
            $driverDetails = $this->driverRepository->findByUserId($id);
            if ($driverDetails) {
                return [
                    'status' => true,
                    'user' => $user,
                    'isDriver' => true,
                    'driverDetails' => $driverDetails
                ];
            } else {
                return [
                    'status' => true,
                    'user' => $user,
                    'isDriver' => false
                ];
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            error_log($e->getMessage());
            return ['status' => false, 'message' => 'Error retrieving user details'];
        }
    }
}
