<?php
/*
    Проводка платежа
*/

require_once MODX_CORE_PATH . 'components/basket/processors/web/payments/create.class.php';

class modShopModxUnitPayWebPaymentsCreateProcessor extends modWebPaymentsCreateProcessor{
    
    
    # public static function getInstance(modX &$modx,$className,$properties = array()) {
    #     
    #     if(
    #         !empty($properties['method'])
    #         AND $properties['method'] == 'check'
    #     ){
    #         require_once dirname(__FILE__) . '/check.class.php';
    #         $className = 'modUnitpayWebPaymentsCheckProcessor';
    #     }
    #     
    #     return parent::getInstance($modx,$className,$properties);
    # }
    
    
    
    public function initialize(){
        
        if (!$params = (array)$this->getProperty('params')){
            $error = 'Invalid request';
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[- ".__CLASS__." -] params is empty");
            return $this->getResponseError($error);
        }
        
        $this->setProperties($params);
          
        if(!$shp_uid    = (int)$this->getProperty('uid')){
            $shp_uid = null;
        } 
        
        // else
        $this->setProperties(array(
            "paysystem_id"  => $this->modx->getOption('unitpay.bill_serv_id'),
            "order_id"          => (int)$params['account'] ? (int)$params['account'] : null,  
            "owner"             => $shp_uid,
            "paysys_invoice_id" => $this->getProperty('unitpayId'),
        ));
        
        return parent::initialize();
    }
    
    
    public function __process(){
        
        $method = $this->getProperty('method');
        switch($this->getProperty('method')){
            case 'check':
                return $this->check();
                break;
                
            case 'pay':
                return parent::__process();
                break;
            
            default: return $this->failure($this->getResponseError($method.' not supported'));
        }
    }
    
    
    protected function check(){
        
        /*
            Проверяем наличие платежа с id платежа юнитпея в системе
        */
        $q = $this->modx->newQuery($this->classKey, array(
            "paysystem_id" => $this->getProperty('paysystem_id'),
            "paysys_invoice_id" => $this->getProperty('paysys_invoice_id'),
        ));
        $q->limit(1);
        if($this->modx->getCount($this->classKey, $q)){
            return $this->failure($this->getResponseError('Payment already exists'));
        }
        
        /*
            Если указан счет, проверяем соответствует ли сумма
        */
        
        return $this->success($this->getResponseSuccess('CHECK is successful'));
    }
    
    
    protected function getSuccessMessage(){
        return $this->getResponseSuccess('PAY is successful');
    }
    

    /*
        Проверяем подпись 
    */ 
    public function checkSignature(){
        $params = (array)$this->getProperty('params');
        
        if ($params['sign'] != $this->getMd5Sign($params, $this->modx->getOption('unitpay.secret_key'))){
            return $this->getResponseError('Incorrect digital signature');
        }

        // else
        return true;
    } 


    protected function getResponseSuccess($message)
    {
        return json_encode(array(
            "jsonrpc" => "2.0",
            "result" => array(
                "message" => $message
            ),
            'id' => 1,
        ));
    }

    protected function getResponseError($message)
    {
        return json_encode(array(
            "jsonrpc" => "2.0",
            "error" => array(
                "code" => -32000,
                "message" => $message
            ),
            'id' => 1
        ));
    }

    private function getMd5Sign($params, $secretKey)
    {
        ksort($params);
        unset($params['sign']);
        return md5(join(null, $params).$secretKey);
    }
    
    
}

return 'modShopModxUnitPayWebPaymentsCreateProcessor';

 