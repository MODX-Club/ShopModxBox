<?php

/*
    Пересчитываем корзину
    
    Устаревший. Не использовать!
    Все актуальные процессоры в processors/basket/
*/

require_once dirname(dirname(dirname(__FILE__))) . '/basket/web/orders/recalculate.class.php';

class modRecalculateProcessor_depricated extends modBasketWebOrdersRecalculateProcessor{
     
}

class modRecalculateProcessor extends modRecalculateProcessor_depricated{
     
    public function initialize(){
        
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[- Basket -] Class '". __CLASS__ ."' is depricated. ".__FILE__.":".__LINE__);
        
        return parent::initialize();
    }
}

return 'modRecalculateProcessor';