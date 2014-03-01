<?php

/*
    Пересчитываем корзину.
    Для этого получим все товары текущего заказа, почле чего пройдемся в цикле 
    по товарам и сравним количество. Те, где есть расхождения, отправим на пересчет
    
*/

require_once dirname(__FILE__) . '/products/getdata.class.php';

class modBasketWebOrdersRecalculateProcessor extends modBasketWebOrdersProductsGetdataProcessor{
    
    
    public function initialize(){
        
        if(!$this->getProperty('quantity')){
            return 'Не были получены данные';
        }
        
        return parent::initialize();
    }
    
    
    public function process(){
        
        // Получаем  данные корзины
        $response = parent::process();
        
        if(!$response['success']){
            return $response;
        }
        
        if(!$response['object']){
            return $this->failure('Корзина пуста');
        }
        
        return $this->success('Корзина успешно пересчитана');
    }
    
    
    public function afterIteration(array $list) {
        $list = parent::afterIteration($list);
        
        /*
            [quantity] => Array
        (
            [78] => 10
            [79] => 2
            [80] => 1
            [81] => 1
            [83] => 2
        )
        */
        
        $processors_path =  dirname(dirname(dirname(dirname(__FILE__)))). '/';
        
        $products = (array)$this->getProperty('quantity');
        
        foreach($list as & $l){
            
            $order_product_id = $l['id'];
            
            if(isset($products[$order_product_id])){
                
                $quantity = (int)$products[$order_product_id];
                
                if($quantity != $l['quantity']){
                    
                    $this->modx->error->reset();
                    
                    $properties = array(
                        'product_key'   => $order_product_id,
                        'quantity'      => $quantity,
                    );
                    
                    if(!$response = $this->modx->runProcessor("basket/web/orders/products/update", 
                        $properties,
                        array(
                            "processors_path" => $processors_path,
                        )
                    )){
                        $error = "Ошибка выполнения запроса";
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, __CLASS__." - {$error}");
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), true));
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($properties, true));
                    }
                    
                    else if($response->isError()){
                        $error = $response->getMessage();
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, __CLASS__." - {$error}");
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), true));
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($response->getResponse(), true));
                    }
                    else{
                        $l['quantity'] = $quantity;
                    }
                    
                }
                
            }
            
        }
        
        return $list;
    }
    
    
    
}

return 'modBasketWebOrdersRecalculateProcessor';