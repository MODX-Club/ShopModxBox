<?php

/*
    Абстрактный класс на проведение оплаты.
    Его нельзя вызывать напрямую, чтобы исключить случаи инжекта оплаты. 
    Этот класс должен расширяться другим классом конкретной платежной системы,
    чтобы использовать методы проверки платежа самой платежной системы
*/

abstract class modWebPaymentsCreateProcessor extends modObjectCreateProcessor{
    public $classKey = 'Payment';
    
    protected $BillingProcessorsPath;
    
    public function checkPermissions() {
        
        // Проверяем подпись платежной системы
        $ok = $this->checkSignature();
        if($ok !== true){
            $this->error($ok);
            return false;
        }
        
        return parent::checkPermissions();
    }
    
    public function initialize(){
        
        $this->BillingProcessorsPath = MODX_CORE_PATH . 'components/billing/processors/';
        
        $this->setDefaultProperties(array(
            'currency_id'  => $this->modx->getOption('shopmodx.default_currency'),
        ));
        
        if(!$this->getProperty('paysystem_id')){
            return $this->error("Не был получен ID платежной системы");
        }
        
        return parent::initialize();
    }
    
    public function beforeSet(){
        
        $this->setProperties(array(
            "createdby" => $this->modx->user->id ? $this->modx->user->id : null,
            "date"      => time(),
        ));
        
        return parent::beforeSet();
    }
    
    public function beforeSave(){
        if(
            !$currency_id = (int)$this->getProperty('currency_id')
            OR !$currency = $this->modx->getObject('modResource', $currency_id)
            OR ! $currency instanceof ShopmodxResourceCurrency
        ){
            return $this->error("Не был получен объект валюты");
        }
        
        if(
            !$paysystem_id = (int)$this->getProperty('paysystem_id')
            OR !$paysystem = $this->modx->getObject('Paysystem', $paysystem_id)
            OR ! $paysystem instanceof Paysystem
        ){
            return $this->error("Не был получен объект платежной системы");
        }
        
        // Проверяем, если указан счет платежной системы, то надо убедиться, что 
        // он еще не числится в биллинге
        if($paysys_invoice_id = $this->object->get('paysys_invoice_id')){
            if($this->modx->getCount($this->classKey, array(
                'paysys_invoice_id' => $paysys_invoice_id,
                'paysystem_id'      => $paysystem_id,
            ))){
                return $this->error("Данный счет уже создан в системе.");
            }
        }
        
        $this->object->addOne($currency);
        $this->object->addOne($paysystem);
        
        return parent::beforeSave();
    }
    
    /*
        Обязательно надо прописывать метод, в котором будет выполняться проверка 
        подписи с сервера платежной системы
    */
    abstract protected function checkSignature();
    
    protected function log($msg, $level = null){
        if($level === null){
            $level = xPDO::LOG_LEVEL_INFO;
        }
        $this->modx->log($level, "[Basket - ".__CLASS__."] {$msg}");
        $this->modx->log($level, print_r($this->getProperties(), true));
        return $msg;
    }
    
    protected function error($msg){
        return $this->log($msg, xPDO::LOG_LEVEL_ERROR);
    }
    
    /*
        Логируем все ошибки процессора, на всякий случай
    */
    public function failure($msg = '',$object = null) {
        $this->error($msg);
        if(!empty($this->object) && is_object($this->object)){
            $this->error(print_r($this->object->toArray(), true));
        }
        return parent::failure($msg,$object);
    }
    
    public function cleanup() {
        /*
            // Если оплата прошла успешно, то обновляем статус заказа
        */
        if($order_id = $this->object->get('order_id')){
            $this->modx->runProcessor('mgr/orders/status/pay', array(
                'order_id'  => $order_id,
            ), array(
                'processors_path' => $this->BillingProcessorsPath,    
            ));
            // На всякий случай сбрасываем счетчик ошибок, если вдруг в вызываемом
            // процессоре были ошибки
            $this->modx->error->reset();
        }
        
        return $this->success($this->getSuccessMessage(), $this->object);
    }
    
    protected function getSuccessMessage(){
        return '';
    }
}

return 'modWebPaymentsCreateProcessor';