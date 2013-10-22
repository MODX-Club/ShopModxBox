<?php
/*
    Удаление заявок
*/

class ShopOrdersRemoveProcessor extends modObjectRemoveProcessor{
    public $classKey = 'Order';
    
    public $permission = 'delete_order';
    
    public function initialize(){
        
        if(!$id = (int)$this->getProperty('order_id')){
            return 'Не был получен ID заявки';
        }
        $this->setProperty('id', $id);
        
        return parent::initialize();
    }
    
    /*function process__(){
        return $this->success();
    }*/
}

return 'ShopOrdersRemoveProcessor';