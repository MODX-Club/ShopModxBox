<?php
/*
    Удаляем товары из корзины
    
    Устаревший. Не использовать!
    Все актуальные процессоры в processors/basket/
*/
 

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/basket/web/orders/products/remove.class.php';

class modProductsRemoveProcessor_depricated extends modBasketWebOrdersProductsRemoveProcessor{
    
}

class modProductsRemoveProcessor extends modProductsRemoveProcessor_depricated{
    
    public function initialize(){
        
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[- Basket -] Class '". __CLASS__ ."' is depricated. ".__FILE__.":".__LINE__);
        
        return parent::initialize();
    }
    
}

return 'modProductsRemoveProcessor';
