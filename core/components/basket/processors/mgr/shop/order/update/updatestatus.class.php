<?php

require_once MODX_CORE_PATH . 'components/billing/processors/mgr/orders/status/update.class.php';

class ShopMgrOrderStatusUpdateProcessor extends modMgrOrdersStatusUpdateProcessor{
    
}

return 'ShopMgrOrderStatusUpdateProcessor';



require_once dirname(dirname(__FILE__)).'/update.class.php';

class ShopMgrOrderStatusUpdateProcessor_depricated extends ShopMgrOrderUpdateProcessor{
    
    public function beforeSet(){
        if(!$new_status = (int)$this->getProperty('new_status')){
            return 'Не был указан статус заявки';
        }
        if($this->object->get('status') == $new_status){
            return 'Заказ уже имеет указанный статус';
        }
        $this->setProperty('status', $new_status);
        
        return true;
    }
    
    function process__(){
        return $this->success('sdfsdfd');
    }
}

return 'ShopMgrOrderStatusUpdateProcessor';