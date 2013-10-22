<?php

/*
Создаем новый заказ с указанием товаров
*/

require_once MODX_CORE_PATH . 'components/billing/processors/mgr/orders/update.class.php';

class ShopMgrOrderUpdateProcessor extends modMgrOrdersUpdateProcessor{}

return 'ShopMgrOrderUpdateProcessor';




class ShopMgrOrderUpdateProcessor_depricated extends modObjectUpdateProcessor{
    public $classKey = 'ShopOrder';
    
    public function initialize(){
        if(!$id = (int)$this->getProperty('order_id')){
            return 'Не был получен ID заявки';
        }
        $this->setProperty('id', $id);
        
        return parent::initialize();
    }
    
    public function beforeSet(){
        $history = $this->modx->newObject('ShopOrderHistory', $this->object->toArray());
        $history->set('order_id', $this->object->get('id'));
        
        // print_r($history->toArray());
        $this->object->addMany($history);
        return parent::beforeSet();
    }
    
}

return 'ShopMgrOrderUpdateProcessor';