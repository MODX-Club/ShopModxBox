<?php

/*
    Добавляем товары в корзину
    
    Устаревший. Не использовать!
    Все актуальные процессоры в processors/basket/
*/

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/basket/web/orders/products/add.class.php';

class modProductsAddProcessor_depricated extends modBasketWebOrdersProductsAddProcessor{
     
}


class modProductsAddProcessor extends modProductsAddProcessor_depricated{
    
    public function initialize(){
        
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[- Basket -] Class '". __CLASS__ ."' is depricated. ".__FILE__.":".__LINE__);
        
        return parent::initialize();
    }
}

return 'modProductsAddProcessor';