<?php

/*
    Получаем данные корзины
    
    Устаревший. Не использовать!
    Все актуальные процессоры в processors/basket/
*/

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/basket/web/orders/products/getdata.class.php';



class modWebBasketEntityGetdataProcessor_depricated extends modBasketWebOrdersProductsGetdataProcessor{
    
    public function setSelection(xPDOQuery $c) {
        $c = parent::setSelection($c);
        
        $c->select(array(
            "{$this->classKey}.price as order_price",
            "{$this->classKey}.id as `key`",
        ));
            
        return $c;    
    }    
    
}


class modWebBasketEntityGetdataProcessor extends modWebBasketEntityGetdataProcessor_depricated{
       
    
    public function initialize(){
        
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[- Basket -] Class '". __CLASS__ ."' is depricated. ".__FILE__.":".__LINE__);
        
        return parent::initialize();
    }
    
}

return 'modWebBasketEntityGetdataProcessor';

 