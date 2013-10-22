<?php

/*
    Получаем данные товаров
    
    Устаревший. Не использовать!
    Все актуальные процессоры в processors/basket/
*/


require_once dirname(dirname(dirname(__FILE__))) . '/basket/web/ajax/orders/getdata.class.php';

class modGetdataProcessor_depricated extends modBasketWebAjaxOrdersGetdataProcessor{
     
    
}

class modGetdataProcessor extends modGetdataProcessor_depricated{
     
    public function initialize(){
        
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[- Basket -] Class '". __CLASS__ ."' is depricated. ".__FILE__.":".__LINE__);
        
        return parent::initialize();
    }
    
}

return 'modGetdataProcessor';