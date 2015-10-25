<?php

require_once MODX_CORE_PATH . 'components/billing/processors/web/orders/products/add.class.php';

class modMgrShopOrderProductsAddProcessor extends modWebOrdersProductsAddProcessor{
    
    public function initialize(){
        
        if(!$this->getProperty('order_id')){
            return "Не указан ID заказа";
        }
        
        if(!$resource_id = (int)$this->getProperty('resource_id')){
            return "Не указан ID товара";
        }
        
        // else
        if($resource = $this->modx->getObject('modResource', $resource_id)){
            $this->setProperty('product_id', $resource->Product->id);
        }
        
        return parent::initialize();
    }
    
}

return 'modMgrShopOrderProductsAddProcessor';
