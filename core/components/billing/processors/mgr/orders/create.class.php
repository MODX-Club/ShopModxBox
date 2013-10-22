<?php

/*
    Создаем новый заказ.
    К заказу добавляются товары/услуги.
    На заказ потом создается счет, который в дальнейшем может быть оплачен
*/

class modMgrOrdersCreateProcessor extends modObjectCreateProcessor{
    public $classKey = 'Order';
    
    public function initialize(){
        
        $this->setProperties(array(
            'createdby' => $this->modx->user->id ? $this->modx->user->id : null,  
            'contractor' => $this->modx->user->id ? $this->modx->user->id : null,  
        ));
        
        return parent::initialize();
    }
}

return 'modMgrOrdersCreateProcessor';