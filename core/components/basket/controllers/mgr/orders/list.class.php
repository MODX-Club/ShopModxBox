<?php

require_once dirname(dirname(__FILE__)). '/index.class.php';

class ControllersMgrOrdersListManagerController extends ControllersMgrManagerController{
    
    public static function getInstance(modX &$modx, $className, array $config = array()) {
        $className = __CLASS__;
        return new $className($modx, $config);
    }

    function loadCustomCssJs(){
        parent::loadCustomCssJs();
        $assets_url = $this->getOption('assets_url');
        $this->modx->regClientStartupScript($assets_url.'js/ext/ux/RowExpander.js'); 
        $this->modx->regClientStartupScript($assets_url.'js/widgets/orders/orders.grid.js'); 
        
        return;
    }
    
    public function getTemplateFile() {
        return 'orders/list/index.tpl';
    }
}