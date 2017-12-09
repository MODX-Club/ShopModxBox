<?php


require_once __DIR__ . '/../object.class.php';

class modWebUsersObjectProcessor extends modWebObjectProcessor{

    public $classKey = 'modUser';
    
    
    public function checkPermissions(){
        
        // if(!$this->modx->user->id){
        //     return false;
        // }
        
        return $this->modx->hasPermission("crm_users_update") && parent::checkPermissions();
    }


    public function beforeSet(){

    	return parent::beforeSet();
    }


    public function beforeSave(){

    	$object = & $this->object;

    	// print_r($object->toArray());


    	// return "Debug";

    	return parent::beforeSave();
    }

}

return 'modWebUsersObjectProcessor';
