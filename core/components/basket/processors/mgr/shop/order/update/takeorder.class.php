<?php

/*
    Меняем статус на Принят в работу
*/

require_once MODX_CORE_PATH . 'components/billing/processors/mgr/orders/status/takeorder.class.php';

class ShopMgrUpdateStatusTakeorderProcessor extends modMgrOrdersStatusTakeorderProcessor{}

return 'ShopMgrUpdateStatusTakeorderProcessor';





require_once dirname(__FILE__).'/updatestatus.class.php';

class ShopMgrUpdateStatusTakeorderProcessor_depricated extends ShopMgrOrderStatusUpdateProcessor{
    
    public function initialize(){
        $this->setProperty('new_status', 2);
        
        return parent::initialize();
    }
    
    public function beforeSet(){
        // Проверяем статус. Смена статуса возможна только если статус Новая
        if($this->object->get('status') != 1){
            return 'Статус заявки не Новая. Сменить статус нельзя.';
        }
        
        $this->setProperty('manager', $this->modx->user->id);
        return parent::beforeSet();
    }
}

return 'ShopMgrUpdateStatusTakeorderProcessor';