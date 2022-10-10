<?php

namespace App\Traits;

use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;


trait Orders {


    private static function getOrderPath() {

        return 'assets/json/coding-challenge-1.json';

    }


    public static function getUnits($data) {

        $total_unit = null;

        if(!empty($data)){
            $total_unit = array_sum(array_column($data, 'quantity'));
        }

        return $total_unit;
        

    }


    public static function calculateDiscount($total_unit_price, $discount) {

        $total_discount = $total_unit_price;

        if(empty($discount)){
            return $total_discount;
        }

        if($discount[0]['type'] == 'DOLLAR'){
            $total_discount = ($total_unit_price - $discount[0]['value']);
        }

        if($discount[0]['type'] == 'PERCENT'){
            $total_discount = ($total_unit_price/100)*$discount[0]['value'];
        }

       return $total_discount;
    }

    public static function getOrderValues($items) {

        $total_unit_price = null;
       
        if(!empty($items)){

            $sum_price = [];

            foreach($items['items'] as $item){
                $quantity = $item['quantity'];
                $unit_price = $item['unit_price'];
                $multiple_price[] = ($quantity * $unit_price);
            }

            $sum_price = array_sum($multiple_price);

            $total_unit_price = $items['shipping_price'] ?  ($sum_price - $items['shipping_price']) : $sum_price;

            $calc_discount = self::calculateDiscount($total_unit_price, $items['discounts']);

            return $calc_discount;
        }


    }


    public static function getAllList() 
    {

        try {

            $package = new Package(new EmptyVersionStrategy());
            $path = $package->getUrl(self::getOrderPath());
            $data = file_get_contents($path);
            
            if(empty($data)){
                return [
                    'status' => false,
                    'data' => null,
                    'message' => 'Order list not found.'
                ];
            }

            return [
                'status' => true,
                'data' => json_decode($data,true),
                'message' => 'Order list found'
            ];

        } catch (\Exception $ex) {
            return [
                'status' => false,
                'data' => null,
                'message' => $ex->getMessage()
            ];
        }

    }

    public static function orderSchema ($order){

        return [
            'order_id' => $order['order_id'],
            'order_date' => date('d-m-Y',strtotime($order['order_datetime'])),
            'total_order_value' => self::getOrderValues($order),
            'unit_count' => self::getUnits($order['items']),
            'customer_state' => $order['customer']['shipping_address']['state'],
        ];
    }

    public static function findOrderbyID($ordersList, $order_id) {
    
        
        $key = array_search($order_id, array_column($ordersList['data'], 'order_id'));
        $order = $ordersList['data'][$key];

        if(empty($order)) {
            return [
                'status' => false,
                'data' => null,
                'message' => 'Order not found!'
            ];
        }

        return [
            'status' => true,
            'data' => self::orderSchema($order),
            'message' => 'Order found!'
        ];

    }


    public static function getOrderDetail($order_id){

        $orderList = self::getAllList();
        
        if(empty($orderList['status']) && empty($orderList['data'])) {

            $error_msg =  $orderList;

            return $error_msg;

        }

       return self::findOrderbyID($orderList,$order_id);


    }

}