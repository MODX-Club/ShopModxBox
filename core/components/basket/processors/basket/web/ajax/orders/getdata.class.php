<?php

/*
    Чисто для Ajax-ответа корзины. Надо будет пересмотреть
*/

require_once dirname(dirname(dirname(__FILE__))). '/orders/products/getdata.class.php';

class modBasketWebAjaxOrdersGetdataProcessor extends modBasketWebOrdersProductsGetdataProcessor{
    
    public function outputArray(array $array,$count = false) {
        
        $response = parent::outputArray($array,$count);
         
        $object = $response['object'];
        
        
        if($response['success']){
            $response['object'] = array(
                "sum" => $response['sum'],
                "original_sum" => $response['original_sum'],
                "discount" => $response['discount'],
                "total" => $response['quantity'],
            );
        }
        else{
            $response['object'] = array();
        }
        
        return $response;
    }    
    
}

// return 'modBasketWebOrdersProductsGetdataProcessor';