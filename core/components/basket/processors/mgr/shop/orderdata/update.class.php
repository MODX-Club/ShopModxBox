<?php

require_once MODX_CORE_PATH . 'components/billing/processors/mgr/orders/products/update.class.php';

class modMgrShopOrderdataUpdateProcessor extends modMgrOrdersProductsUpdateProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "order_product_id"  => $this->getProperty('id'),
        ));
        
        
        return parent::initialize();
    }
    
}


return 'modMgrShopOrderdataUpdateProcessor';
