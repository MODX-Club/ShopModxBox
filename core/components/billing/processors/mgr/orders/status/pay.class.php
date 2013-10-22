<?php

/*
    Заказ оплачен
*/

require_once dirname(__FILE__). '/update.class.php';

class modMgrOrdersStatusPayProcessor extends modMgrOrdersStatusUpdateProcessor{
    
    
    public function initialize(){
        
        $this->setProperty('new_status', 8);
        
        return parent::initialize();
    }
    
}

// return 'modMgrOrdersStatusPayProcessor';