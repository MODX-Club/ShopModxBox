<?php

class modMgrShopOrderdataRemoveProcessor extends modObjectremoveProcessor{
    
    public $classKey = 'OrderProduct';
    public $permission = 'remove';
    
    
    public function initialize(){
        
        if(!$id = (int)$this->getProperty('id')){
            
            return 'Не был получен id элемента';
        }
        
        // else
        $this->setProperty('id', $id);
        
        return parent::initialize();
    }
    
    
    # public function beforeRemove() { 
    #     
    #     print_r($this->object->toArray());
    #     
    #     return 'debug';
    # }
    
}

return 'modMgrShopOrderdataRemoveProcessor';


