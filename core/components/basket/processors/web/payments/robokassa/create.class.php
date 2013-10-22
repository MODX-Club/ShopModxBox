<?php
/*
    Проводка платежа от робокассы
*/

require_once dirname(dirname(__FILE__)). '/create.class.php';

class modWebPaymentsRobokassaCreateProcessor extends modWebPaymentsCreateProcessor{
    
    public function initialize(){
        
        $this->setProperties(array(
            "paysystem_id"  => $this->modx->getOption('robokassa.bill_serv_id'),
        ));
        
        return parent::initialize();
    }
    
    /*
        Проверяем подпись с робокассы
    */
    protected function checkSignature(){
        
        $mrh_pass2 = $this->modx->getOption('robokassa.mrh_pass2');

        // Параметры, передаваемые в запросе от робокассы
        $crc        = mb_strtoupper($this->getProperty('SignatureValue'));
        $out_sum    = $this->getProperty('OutSum');
        $inv_id     = $this->getProperty('InvId');
        $shp_aid    = $this->getProperty('shp_aid'); 
        $shp_order  = $this->getProperty('shp_order', null);
        $shp_trff   = $this->getProperty('shp_trff');
        $shp_uid    = $this->getProperty('shp_uid');
         
        $my_crc = mb_strtoupper(md5("{$out_sum}:{$inv_id}:{$mrh_pass2}:shp_aid={$shp_aid}:shp_order={$shp_order}:shp_trff={$shp_trff}:shp_uid={$shp_uid}"));
        
        $this->modx->log(xPDO::LOG_LEVEL_INFO, "[Robokassa - robokassa.payResult]", print_r($_REQUEST, true));
        
        // проверка корректности подписи
        if ($my_crc !=$crc){
            $error = "[Robokassa - robokassa.payResult] - Неверная подпись. Получена: '{$crc}'. Должна быть: '{$my_crc}'";
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $error);
            return "bad sign";
        } 
        
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

return 'modWebPaymentsRobokassaCreateProcessor';

 