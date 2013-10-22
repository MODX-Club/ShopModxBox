<?php
$this->loadClass('ShopOrder');
class ShopOrderHistory extends ShopOrder {
    
    protected function beforeSave(){
        return;
    }    
}