<?php

/*
    Основной класс корзины
*/

$this->loadClass('modProcessor', '', false, true);

class Basket extends modProcessor{
         
    
    function __construct(modX & $modx,array $properties = array()) {
        parent::__construct($modx, $properties);
        
        
        if(!$billing_path = $modx->getOption('billing.core_path')){
            $billing_path = $modx->getOption('core_path') . 'components/billing/';
        }
        
        $this->setProperties(array(
            "billing_path"   => $billing_path,
        ));
    }
    
    
    public function getActiveOrderId(){
        $order_id = null;
        
        // Проверяем, авторизован ли пользователь
        if(
            $user_id  = (int)$this->modx->user->get('id')
        ){
            $c = $this->modx->newQuery('Order', array(
                'contractor'    => $user_id,
            ));
            $c->sortby('id', 'DESC');
            $c->limit(1);
            if(
                $order = $this->modx->getObject('Order', $c)
                AND $order->status_id == '1'
            ){
                $order_id = $order->id;
            }
        }
        
        if(!$order_id AND !empty($_SESSION['order_id'])){
            $order_id = $_SESSION['order_id'];
        }
        
        return $order_id;
    }
    
    public function flushSession(){
        if(isset($_SESSION['order_id'])){
            unset($_SESSION['order_id']);
        }
        return;
    }
    
    public function getBillingPath(){
        return $this->getProperty('billing_path');
    }
    
    
    public function process(){}
}
