<?php
class Order extends xPDOSimpleObject {
    
    /*public function save($cacheFlag= null){
        
        if($this->isNew() && !$this->get('createdby') && $this->xpdo->user->id){
            $this->set('createdby', $this->xpdo->user->id);
        }
        
        return parent::save($cacheFlag);
    }*/
}