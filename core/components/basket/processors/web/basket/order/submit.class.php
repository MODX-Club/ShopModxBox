<?php
/*
    Оформление заказа (по сути просто смена статуса заказа)
    
    Устаревший. Не использовать!
    Все актуальные процессоры в processors/basket/
*/

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/basket/web/orders/submit.class.php';


class modWebBasketOrderSubmitProcessor_depricated extends modBasketWebOrdersSubmitProcessor{
    
    /*
        Удалите эти параметры (чтобы в силу вступили актуальные параметры родительского класса)
        и создайте шаблоны этих писем
    */
    
    protected $contractor_message_tpl = "message/new_order.tpl";
    protected $manager_message_tpl = "message/new_order.tpl";
    
    
    /*
        Оставили обязательные поля для обратной совместимости
    */
    protected function getFields(){
        $fields = (array)parent::getFields();
            
        $fields = array_merge($fields, array(
            'phone' => array(
                'name'  => 'Сотовый телефон',
                'error_message' => 'Укажите телефон',     
                'required' => true,
            ),
            'address' => array(
                'name'  => 'Адрес доставки',
                'error_message' => 'Адрес доставки',     
                'required' => true,
            ),
        ));
            
        return $fields;
    }
    
    
}

class modWebBasketOrderSubmitProcessor extends modWebBasketOrderSubmitProcessor_depricated{
    
    public function initialize(){
        
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[- Basket -] Class '". __CLASS__ ."' is depricated. ".__FILE__.":".__LINE__);
        
        return parent::initialize();
    }
}


return 'modWebBasketOrderSubmitProcessor';
 