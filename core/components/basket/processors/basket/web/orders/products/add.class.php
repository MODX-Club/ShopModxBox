<?php

/*
    Добавление товаров в корзину
*/

require_once MODX_CORE_PATH . 'components/billing/processors/web/orders/products/add.class.php';

class modBasketWebOrdersProductsAddProcessor extends modWebOrdersProductsAddProcessor{

    
    public function initialize(){
        
        $this->setProperties(array(
            'order_id'    => $this->modx->basket->getActiveOrderID(),
        ));
        
        return parent::initialize();
    }
    
    protected function processResponse($response){
         
        if(
            !$response->isError() 
            AND $object = $response->getObject() 
            AND !empty($object['order_id'])
        ){
            $_SESSION['order_id'] = $object['order_id'];
        }
        
        return parent::processResponse($response);
    }    
    
}

return 'modBasketWebOrdersProductsAddProcessor';
