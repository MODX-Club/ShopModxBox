<?php

/**
 * @package group_edit
 */

#ini_set('display_errors',1);
#error_reporting(E_ALL);

abstract class groupEditBaseManagerController extends modExtraManagerController {
    
    public function initialize() {
        
        return parent::initialize();
    
    }
    
    public function getLanguageTopics() {
        return array('group_edit:default');
    }
    
    public function checkPermissions() {
        return true;
    }
    
}

class IndexManagerController extends groupEditBaseManagerController {
    public static function getDefaultController() { return 'mgr/home'; }
}
