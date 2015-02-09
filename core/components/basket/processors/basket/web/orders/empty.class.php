<?php

/*
    Очищаем корзину
*/


require_once dirname(__FILE__). '/status/update.class.php';

class modBasketWebOrdersEmptyProcessor extends modBasketWebOrdersStatusUpdateProcessor{
     
    
    public function initialize(){
        
        // Устанавливаем новый статус - Отменен
        $this->setProperty('new_status', 7);
        
        return parent::initialize();
    }
    
    
    public function beforeSet(){
        
        /*
            Проверяем, можно ли очищать корзину.
            Можно только если статус - новый
        */
        
        if($this->object->get('status_id') != '1'){
            $error = "Данную корзину нельзя очистить";
            
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, __CLASS__ . " - {$error}");
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), true));
            
            return $error;
        }
        
        return parent::beforeSet();
    }
    
    
    public function cleanup() {
        
        // Сбрассываем сессию
        unset($_SESSION['order_id']);
        
        return $this->success('Корзина успешно очищена',$this->object->toArray());
    }  
    
}

/*class modBasketWebOrdersEmptyProcessor extends modProcessor{
     
    
    public function process(){
         
        // Проверяем, есть ли активный заказ
        if($order_id = $this->modx->basket->getActiveOrderID()){
            
            // Если есть, то пытаемся отменить заказ
            if(!$response = $this->modx->runProcessor('mgr/orders/status/update', array(
                'order_id'      => $order_id,
                'new_status'    => 7,
            ), array(
                'processors_path' => $this->modx->basket->getBillingPath() . 'processors/',    
            ))){
                $error = "Ошибка выполнения запроса";
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Basket] - OnFlush - {$error}");
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), 1));
                return $this->failure($error);
            }
            
            if($response->isError()){
                return $response->getResponse();
            }
        }
        
        // Если все ОК, то сбрасываем сессию
        $this->modx->basket->flushSession();
        
        return $this->success("Корзина успешно очищена");
        
    }
    
}*/

return  'modBasketWebOrdersEmptyProcessor';
