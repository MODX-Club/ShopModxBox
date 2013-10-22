<?php
class ShopOrder extends xPDOSimpleObject {
    
    public function save($cacheFlag = null) {
        $this->beforeSave();
        return parent::save($cacheFlag);
    }
    
    protected function beforeSave(){
        if(!$this->get('create_date')){
            $this->set('create_date', time());
        }
        
        $this->set('modify_date', time());
        
        if(!$this->get('modified_by')){
            $this->set('modified_by', $this->xpdo->user->id);
        }
        return;
    }
}