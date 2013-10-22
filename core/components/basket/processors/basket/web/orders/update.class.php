<?php

/*
    Обновление заказа
*/

require_once MODX_CORE_PATH. 'components/billing/processors/mgr/orders/update.class.php';

class modBasketWebOrdersUpdateProcessor extends modMgrOrdersUpdateProcessor{
    
    
    public function initialize(){
        
        $this->setProperty('order_id', $this->modx->basket->getActiveOrderID());
        
        return parent::initialize();
    }
    
    public function beforeSet(){
        
        // Проверяем является ло заказ текущим заказом пользователя
        if($this->object->get('id') != $this->getProperty('order_id')){
            return 'Неверный заказ';
        }
        
        return parent::beforeSet();
    }    
    
}


return 'modBasketWebOrdersUpdateProcessor';