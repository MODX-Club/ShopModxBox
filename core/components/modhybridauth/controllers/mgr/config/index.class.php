<?php

require_once dirname(dirname(__FILE__)). '/index.class.php';

class ControllersMgrConfigManagerController extends ControllersMgrManagerController{
    
    public static function getInstance(modX &$modx, $className, array $config = array()) {
        $className = __CLASS__;
        return new $className($modx, $config);
    }
    
    function loadCustomCssJs(){
        parent::loadCustomCssJs();
         
        return;
    }
}