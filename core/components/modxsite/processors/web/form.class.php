<?php
require_once dirname(dirname(__FILE__)) . '/site/web/form.class.php';

abstract class modWebFormProcessor extends modSiteWebFormProcessor{
    
    
    public function initialize(){
        
        if(empty($this->modx->smarty)){
            $this->modx->invokeEvent('OnHandleRequest');
        }
        
        return parent::initialize();
    }
    
}

return 'modWebFormProcessor';
