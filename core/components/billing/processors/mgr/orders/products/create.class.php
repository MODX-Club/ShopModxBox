<?php

/*
    Создаем запись Заказ-Товар
*/

class modMgrOrdersProductsCreateProcessor extends modObjectCreateProcessor{
    
    public $classKey = 'OrderProduct';
    
    public function initialize(){
        
        if(!(int)$this->getProperty('product_id')){
            return 'Не был указан ID товара';
        } 
        
        return parent::initialize();
    }
    
    public function beforeSet(){
        
        // Проверяем наличие товара
        if(!$product = $this->modx->getObject('ShopmodxProduct', (int)$this->getProperty('product_id'))){
            $error = 'Не был получен товар';
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Billing] - {$error}");
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), true));
            return $error;
        }
        
        // Если указан ID заказа, то пытаемся получить данный заказ.
        if($order_id = $this->getProperty('order_id')){
            
            // Если он не был получен, то возвращаем ошибку.
            if(!$this->modx->getObject('Order', $order_id)){
                $error = "Не был получен заказ";
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Billing] - {$error}");
                return $error;
            }
        }
        else{
            if(!$response = $this->modx->runProcessor('mgr/orders/create', array(
                
            ),array(
                "processors_path"  => dirname(dirname(dirname(dirname(__FILE__)))). '/',
            ))){
                $error = "Не был создан новый заказ";
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Billing] - {$error}");
                return $error;
            }
            
            // else
            if($response->isError() OR !$object = $response->getObject() OR !$order_id = $object['id']){
                $error = "Не был создан новый заказ";
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Billing] - {$error}");
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($response->getResponse(), 1));
                return $error;
            }
            
            // else
            // Устанавливаем значение ID заказа
            $this->setProperty('order_id', $order_id);
        }
        
        // Устанавливаем значение цены и валюты
        // В дальнейшем в этом месте можно будет вклиниться с переопределением цен
        $this->object->fromArray(array(
            'price' => $product->get('sm_price'),    
            'currency_id' => $product->get('sm_currency'),    
        ));
        
        return parent::beforeSet();
    }
    
    public function cleanup() {
        $response = parent::cleanup();
        $response['message'] = $this->getProperty('success_message', 'Товар успешно добавлен');
        return $response;
    }    
}

return 'modMgrOrdersProductsCreateProcessor';