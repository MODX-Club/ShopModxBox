<?php

class ShopOrderStatusGetlistProcessor extends modObjectGetlistProcessor{
    public $classKey = 'OrderStatus';
    
    
    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 0,
            "sort"  => "rank",
            "dir"   => "ASC",
        ));
        return parent::initialize();
    }
}

return 'ShopOrderStatusGetlistProcessor';