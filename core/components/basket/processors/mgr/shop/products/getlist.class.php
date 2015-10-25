<?php

require_once dirname(__FILE__) . '/getdata.class.php';

class modMgrShopProductsGetlistProcessor extends modMgrShopProductsGetdataProcessor{
    
    public function outputArray(array $array, $count = false){
        
        # if($this->getProperty('json', false)){
        #     return parent::outputArray($array, $count);
        # }
        
        $result = array();
        
        foreach($array as $a){
            $result[] = $a;
        }
        
        // else
        return json_encode(array(
            'success'   => true,
            'message'   => '',
            'total'     => $count,
            'results'    => $result,
        ));
    } 
    
}

return 'modMgrShopProductsGetlistProcessor';
