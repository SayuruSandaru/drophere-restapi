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
        $description = $request['description'];
        $rating = $request['rating'];
        $driver_id = $request['driver_id'];
        $res = $this->userService->addReview($description, $rating, $driver_id);

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

   
}
