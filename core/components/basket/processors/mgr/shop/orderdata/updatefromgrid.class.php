<?php

require_once dirname(__FILE__).'/update.class.php';

class modMgrShopOrderdataUpdatefromgridProcessor extends modMgrShopOrderdataUpdateProcessor{
    
    public function initialize(){
        
        
        if($data = $this->modx->fromJSON($this->getProperty('data'))){
            $this->setDefaultProperties($data);
            unset($this->properties['data']);
        }
        
        return parent::initialize();
    }
    
}

return 'modMgrShopOrderdataUpdatefromgridProcessor';
