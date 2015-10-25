<?php

require_once dirname(__FILE__).'/update.class.php';

class modMgrShopOrderstatusUpdatefromgridProcessor extends modMgrShopOrderstatusUpdateProcessor{ 
    
    public function initialize(){
        
        /*$this->modx->setLogLevel(3);
        $this->modx->setLogTarget('HTML');*/
        
        if($data = $this->modx->fromJSON($this->getProperty('data'))){
            $this->setDefaultProperties($data);
            unset($this->properties['data']);
        }
        
        # $this->setProperty('id', (int)$this->getProperty('id'));
        return parent::initialize();
    }
    
}

return 'modMgrShopOrderstatusUpdatefromgridProcessor';