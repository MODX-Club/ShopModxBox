<?php
/*
    Получаем данные корзины
*/
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/mgr/orders/products/getdata.class.php';

class modBasketWebOrdersProductsGetdataProcessor extends modBasketMgrOrdersProductsGetdataProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "limit" => 0,
        ));
        
        // Получаем активный заказ юзера
        $this->setProperty('order_id', $this->modx->basket->getActiveOrderId());
        
        return parent::initialize();
    }

    
    public function process(){
        // Если у пользователя нет нового заказа (корзины), 
        // то просто возвращаем пустой объект,
        // так как это не есть ошибка.
        
        if(!$this->getProperty('order_id')){
            return $this->success('Корзина пуста');
        }
        
        // else
        return parent::process();
    }


    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        $c->where(array(
            "order_id"  => $this->getProperty('order_id'),
        ));
        
        return $c;
    }    
    
}

return 'modBasketWebOrdersProductsGetdataProcessor';