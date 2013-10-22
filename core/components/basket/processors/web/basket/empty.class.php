<?php

/*
    Очищаем корзину
*/

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/basket/web/orders/empty.class.php';

class modEmptyProcessor_depricated extends modBasketWebOrdersEmptyProcessor{}

class modEmptyProcessor extends modEmptyProcessor_depricated{
    
    public function initialize(){
        
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[- Basket -] Class '". __CLASS__ ."' is depricated. ".__FILE__.":".__LINE__);
        
        return parent::initialize();
    }
    
}

return 'modEmptyProcessor';