<?php

/*
    Обновление товара
*/

require_once MODX_CORE_PATH . 'components/billing/processors/mgr/orders/products/update.class.php';

class modBasketWebOrdersProductsUpdateProcessor extends modMgrOrdersProductsUpdateProcessor{
    
    
    public function initialize(){
        
        // Сбрасываем зарезервированные параметры
        $this->unsetProperty('order_id');
        $this->unsetProperty('product_id');
        $this->unsetProperty('price');
        $this->unsetProperty('currency_id');
        
        
        if(!$order_id = $this->modx->basket->getActiveOrderID()){
            return "Нет активной корзины";
        }
        // else
        $this->setProperty('order_id', $order_id);
        
        if(!$product_key = (int)$this->getProperty('product_key')){
            return 'Не был получен ключ товара';
        }
        
        $this->setProperties(array(
            "order_product_id" => $product_key,
        ));
        
        return parent::initialize();
    }
        
    
    public function beforeSet(){
        
        // Проверяем, является ли это товар текущей корзины
        if($this->object->get('order_id') != $this->getProperty('order_id')){
            return 'Товар не относится к текущей корзине';
        }
        
        
        return parent::beforeSet();
    }
    
}