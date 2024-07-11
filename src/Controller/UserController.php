<?php

namespace App\Controller;

use App\Service\UserService;
use App\Utility\ResponseUtility;

class UserController
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function addReview($request)
    {
        $userId = $request['userId'];
        $description = $request['description'];
        $rating = $request['rating'];
        $driver_id = $request['driver_id'];
        $res = $this->userService->addReview($description, $rating, $driver_id, $userId);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['message' => $res['message'], 'id' => $res['id']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                400
            );
        }
    }

    public function getReviewsByDriverId($request)
    {
        $driver_id = $request['driver_id'];

        $res = $this->userService->getReviewsByDriverId($driver_id);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['reviews' => $res['reviews']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                404
            );
        }
    }



    // public function getReviewById($request)
    // {
    //     $review_id = $request['review_id'];

    //     $res = $this->userService->getReviewById($review_id);

    //     if ($res['status']) {
    //         ResponseUtility::sendJsonResponse(
    //             ResponseUtility::STATUS_SUCCESS,
    //             ['review' => $res['review']],
    //             200
    //         );
    //     } else {
    //         ResponseUtility::sendJsonResponse(
    //             ResponseUtility::STATUS_ERROR,
    //             ['message' => $res['message']],
    //             404
    //         );
    //     }
    // }

    // public function getAllReviews()
    // {
    //     $res = $this->userService->getAllReviews();

    //     if ($res['status']) {
    //         ResponseUtility::sendJsonResponse(
    //             ResponseUtility::STATUS_SUCCESS,
    //             ['reviews' => $res['reviews']],
    //             200
    //         );
    //     } else {
    //         ResponseUtility::sendJsonResponse(
    //             ResponseUtility::STATUS_ERROR,
    //             ['message' => $res['message']],
    //             500
    //         );
    //     }
    // }


    public function getAllUsers()
    {
        $res = $this->userService->getAllUsers();

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['users' => $res['users']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                500
            );
        }
    }


    public function getUserById($id)
    {
        $res = $this->userService->getUserById($id);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['user' => $res['user']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                404
            );
        }
    }


    public function getUserDetails($id)
    {
        $res = $this->userService->getUserDetails($id);

        if ($res['status']) {
            $responseData = [
                'user' => $res['user'],
                'isDriver' => $res['isDriver']
            ];

            if ($res['isDriver']) {
                $responseData['driverDetails'] = $res['driverDetails'];
            }

            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                $responseData,
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                $res['message'] === 'User not found' ? 404 : 500
            );
        }
    }


    public function getDisputeById($dispute_id)
    {
        $res = $this->userService->getDisputeById($dispute_id);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['dispute' => $res['dispute']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                200
            );
        }
    }

    public function createDispute($request)
    {
        $userId = $request['userId'];
        $status = $request['status'];
        $category = $request['category'];
        $message = $request['message'];

        $res = $this->userService->createDispute($status, $category, $message, $userId);

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['message' => $res['message']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                200
            );
        }
    }

    public function getAllDisputes()
    {
        $res = $this->userService->getAllDisputes();

        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['disputes' => $res['disputes']],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                500
            );
        }
    }

    public function updateStatus($request)
    {
        $dispute_id = $request['dispute_id'];
        $status = $request['status'];

        $res = $this->userService->updateStatus($dispute_id, $status);
        if ($res['status']) {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_SUCCESS,
                ['message' => "Status updated successfully"],
                200
            );
        } else {
            ResponseUtility::sendJsonResponse(
                ResponseUtility::STATUS_ERROR,
                ['message' => $res['message']],
                500
            );
        }
    }
}
