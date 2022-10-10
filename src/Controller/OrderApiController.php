<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

use App\Traits\Orders;

class OrderApiController extends AbstractController
{



    /**
     * @Route(
     * "/api/order/{id}", 
     * name="order_api_show",
     * methods="{'GET|HEAD'}",
     * )
     */
    public function show(int $order_id): JsonResponse
    {
        try {

            $orderDetail = Orders::getOrderDetail($order_id);

            if($orderDetail['status'] == false){
                return $this->json([
                    'code' => 400,
                    'status' => false,
                    'message' => $orderDetail['message']
                ]);
            }
            
            return $this->json([
                $orderDetail
            ]);

        } catch (\Exception $ex) {

            return $this->json([
                'code' => $ex->getCode(),
                'status' => false,
                'message' => $ex->getMessage(),
            ]);

        }
        
    }


}
