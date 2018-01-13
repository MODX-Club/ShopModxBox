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
    

    public function cleanup() {

        $object = & $this->object;
        
        $canViewAllData = ($this->modx->user->id && $object->id === $this->modx->user->id) || $this->modx->hasPermission("canViewAllData");

        unset($object->_fields['class_key']);
        unset($object->_fields['hash_class']);
        unset($object->_fields['salt']);
        unset($object->_fields['primary_group']);
        unset($object->_fields['session_stale']);
        unset($object->_fields['password']);

        if(!$canViewAllData){


        }

        $object->createdon = $object->createdon ? strtotime($object->createdon) : null;

        return parent::cleanup();
    }

}

return 'modWebUsersObjectProcessor';
