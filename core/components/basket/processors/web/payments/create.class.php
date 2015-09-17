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
    
    # public function checkPermissions() {
    #     
    #     // Проверяем подпись платежной системы
    #     $ok = $this->checkSignature();
    #     if($ok !== true){
    #         $this->error($ok);
    #         return false;
    #     }
    #     
    #     return parent::checkPermissions();
    # }
    
    
    /*
        Обязательно надо прописывать метод, в котором будет выполняться проверка 
        подписи с сервера платежной системы
    */
    abstract protected function checkSignature();
    
    
    public function initialize(){
         
        $this->BillingProcessorsPath = MODX_CORE_PATH . 'components/billing/processors/';
        
        $this->setDefaultProperties(array(
            'currency_id'  => $this->modx->getOption('shopmodx.default_currency'),
        ));
        
        if(!$this->getProperty('paysystem_id')){
            $error = "Не был получен ID платежной системы";
            $this->error($error);
            return $this->getResponseError($error);
        }
        
        $this->setProperties(array(
            "allow_partial_payment"    => $this->modx->getOption('shop.allow_partial_payment', null, false),    // Разрешить частичную оплату
        ));
        
        return parent::initialize();
    }
    
    public function process(){
        
        // Проверяем подпись платежной системы
        $ok = $this->checkSignature();
        if($ok !== true){
            $this->error($ok);
            return $this->failure($this->getResponseError($ok));
        }
        
        // else
        if(
            !$currency_id = (int)$this->getProperty('currency_id')
            OR !$currency = $this->modx->getObject('modResource', $currency_id)
            OR ! $currency instanceof ShopmodxResourceCurrency
        ){
            $error = "Не был получен объект валюты";
            $this->error($error);
            return $this->failure($this->getResponseError($error));
        }
        
        if(
            !$paysystem_id = (int)$this->getProperty('paysystem_id')
            OR !$paysystem = $this->modx->getObject('Paysystem', $paysystem_id)
            OR ! $paysystem instanceof Paysystem
        ){
            $error = "Не был получен объект платежной системы";
            $this->error($error);
            return $this->failure($this->getResponseError($error));
        }
        
        // Проверяем, если указан счет платежной системы, то надо убедиться, что 
        // он еще не числится в биллинге
        if($paysys_invoice_id = $this->getProperty('paysys_invoice_id')){
            if($this->modx->getCount($this->classKey, array(
                'paysys_invoice_id' => $paysys_invoice_id,
                'paysystem_id'      => $paysystem_id,
            ))){
                $error = "Данный счет уже создан в системе.";
                $this->error($error);
                return $this->failure($this->getResponseError($error));
            }
        }
        
        // Проверяем счет
        if(
            $order_id = (int)$this->getProperty('order_id')
        ){
            $namespace = 'basket';
            $response = $this->modx->runProcessor('basket/mgr/orders/products/getdata',
            array(
                "order_id"  => $order_id,
            ), array(
                'processors_path' => $this->modx->getObject('modNamespace', $namespace)->getCorePath().'processors/',
            ));
            
            if($response->isError()){
                $error = $response->getMessage();
                $this->error($error);
                return $this->failure($this->getResponseError($error));
            }
            
            // else
            $r = $response->getResponse();
            
            if(!$r['object']){
                $error = 'Order not exists';
                $this->error($error);
                return $this->failure($this->getResponseError($error));
            } 
            
            // Проверяем сумму
            if(
                !empty($r['sum'])
                AND !$this->getProperty('allow_partial_payment')
                AND ((float)$this->getProperty('sum') < (float)$r['sum'])
            ){
                $error = 'Incorrect sum';
                $this->error($error);
                return $this->failure($this->getResponseError($error));
            } 
        }
        
        $this->object->addOne($currency);
        $this->object->addOne($paysystem);
        
        return $this->__process();
    }
    
    
    protected function __process(){
        return parent::process();
    } 
    
    
    public function beforeSet(){
        
        $this->setProperties(array(
            "createdby" => $this->modx->user->id ? $this->modx->user->id : null,
            "date"      => time(),
        ));
        
        return parent::beforeSet();
    }
    
    
    protected function getResponseSuccess($message)
    {
        return $message;
    }
    
    protected function getResponseError($message)
    {
        return $message;
    }
    
    protected function log($msg, $level = null){
        if($level === null){
            $level = xPDO::LOG_LEVEL_INFO;
        }
        $this->modx->log($level, "[Basket - ".__CLASS__."] {$msg}");
        $this->modx->log($level, print_r($this->getProperties(), true));
        return $msg;
    }
    
    protected function error($msg){
        $this->log(xPDO::LOG_LEVEL_ERROR, $msg);
        return $msg;
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
