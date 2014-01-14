<?php
/*
    Проводка платежа от робокассы
*/

require_once MODX_CORE_PATH . 'components/basket/processors/web/payments/create.class.php';

class modEdinayakassaWebPaymentsCreateProcessor extends modWebPaymentsCreateProcessor{
    
    public function initialize(){
        
        $this->setProperties(array(
            "paysystem_id"  => $this->modx->getOption('edinayakassa.bill_serv_id'),
        ));
        
        if(!(int)$this->getProperty('order_id')){
            return 'Не был получен ID заказа';
        }
        
        return parent::initialize();
    }
    
    /*
        Проверяем подпись с робокассы
    */
    protected function checkSignature(){
        
        // Параметры, передаваемые в запросе от робокассы
        $out_sum    = $this->getProperty('WMI_PAYMENT_AMOUNT');
        $shp_order  = (int)$this->getProperty('order_id', null);
        $inv_id     = $this->getProperty('WMI_ORDER_ID');
        if(!$shp_uid    = (int)$this->getProperty('uid')){
            $shp_uid = null;
        } 
        
        $this->modx->log(xPDO::LOG_LEVEL_INFO, "[EdinayaKassa - edinayakassa.payResult]", print_r($_REQUEST, true));
        
        // проверка корректности подписи
        /*if ($my_crc !=$crc){
            $error = "[Robokassa - robokassa.payResult] - Неверная подпись. Получена: '{$crc}'. Должна быть: '{$my_crc}'";
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $error);
            return "bad sign";
        } */
        
        // else
        $this->setProperties(array(
            "sum"               => $out_sum,  
            "order_id"          => $shp_order,  
            "owner"             => $shp_uid,
            "paysys_invoice_id" => $inv_id,
        ));
        
        return true;
    }
    
    protected function getSuccessMessage(){
        return 'OK'.$this->getProperty('InvId');
    }    
}

return 'modEdinayakassaWebPaymentsCreateProcessor';

 