<?php

/*
    Обновляем заказ
*/

class modMgrOrdersUpdateProcessor extends modObjectUpdateProcessor{
    
    public $classKey = 'Order';
    
    
    public function initialize(){
        
        if(!$order_id = (int)$this->getProperty('order_id')){
            return 'Не был указан ID заказа';
        }
        // else
        $this->setProperty('id', $order_id);
        
        return parent::initialize();
    }
    
    
    public function beforeSet(){
        
        $this->setProperties(array(
            'editedby'  =>  $this->modx->user->id ? $this->modx->user->id : null,
            'editedon'  => time(),
            'number_history' => $this->object->get('number_history') + 1,
        ));
        
        return parent::beforeSet();
    }
    
}

return 'modMgrOrdersUpdateProcessor';