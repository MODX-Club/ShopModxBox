<?php

/*
    Remove product from order
*/

require_once dirname(__FILE__). '/update.class.php';

class modBasketWebOrdersProductsRemoveProcessor extends modBasketWebOrdersProductsUpdateProcessor{
    
    
    public function initialize(){
        
        $this->setProperties(array(
            "quantity"  => 0,
            "key"       => $this->getProperty('product_key'),
        ));
        
        return parent::initialize();
    }

}

return 'modBasketWebOrdersProductsRemoveProcessor';
