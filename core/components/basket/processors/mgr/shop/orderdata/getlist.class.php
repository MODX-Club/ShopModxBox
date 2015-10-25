<?php
/*
    Получаем данные заказа (товары, стоимость и т.п.)
*/ 

//require_once MODX_CORE_PATH . 'components/modxsite/processors/web/catalog/products/getdata.class.php';

require_once dirname(dirname(dirname(dirname(__FILE__)))). '/basket/mgr/orders/products/getdata.class.php';

class ShopOrderdataGetlistProcessor extends modBasketMgrOrdersProductsGetdataProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'limit' => 0,
            'grid'  => true,
            'show_canceled'  => true,
        ));
        
        if(!$this->getProperty('order_id')){
            return "Не был получен ID заявки";
        }
        
        return parent::initialize();
    }
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        
        $c = parent::prepareQueryBeforeCount($c);
        
        $c->where(array(
            'order_id' => $this->getProperty('order_id'),    
        ));
        
           
        return $c;
    }    
    
    
    # public function setSelection(xPDOQuery $c){
    #     $c = parent::setSelection($c);
    #     
    #     $c->select(array(
    #         "{$this->classKey}.price as order_price",
    #         "{$this->classKey}.quantity as order_quantity",
    #     ));
    #     
    #     return $c;
    # }
    
    
    public function outputArray(array $array, $count = false){
        $result = parent::outputArray($array, $count);
        
        if($this->getProperty('grid')){
            
            $data = array();
            
            if(!empty($result['object'])){
                foreach($result['object'] as $a){
                    $data[] = $a;
                }
            }
            
            return '{"total":"'.$count.'","results":'.$this->modx->toJSON($data).'}';
        }
        
        // else
        return $result;
    }    
    
}
    


return 'ShopOrderdataGetlistProcessor';