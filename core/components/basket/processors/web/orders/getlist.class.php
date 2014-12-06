<?php
/*
    Получаем данные заказов. 
    Только данные заказы созданные пользователем или оформленные на него
*/

require_once MODX_CORE_PATH . 'components/billing/processors/mgr/orders/getlist.class.php';

class modBasketWebOrdersGetlistProcessor extends modMgrOrdersGetlistProcessor{
    
    public function initialize(){
        
        $this->setProperties(array(
            "contractor" =>  (int)$this->modx->user->id, 
        ));
        
        if(!$this->getProperty('contractor')){
            return "Не был получен ID пользователя";
        }
        
        return parent::initialize();
    }
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        if($order_id = (int)$this->getProperty('order_id')){
            $this->setProperty('limit', 1);
            $c->where(array(
                "id"    => $order_id,    
            ));
        }
        
        return $c;
    }
    
    
    public function prepareQueryAfterCount(xPDOQuery $c){
        $c = parent::prepareQueryAfterCount($c);
        
        $contractor = $this->getProperty('contractor');
        
        $c->leftJoin('OrderProduct', 'OrderProducts');
        
        $c->select(array(
            "count(*) as positions",
            "sum(OrderProducts.quantity) as total",
            "sum(OrderProducts.price*OrderProducts.quantity) as sum",
        ));
                 
        $c->where(array(
            // Либо статус - Новый и создатель - текущий пользователь
            array(
                "createdby" => $contractor,
                "status_id"  => 1,
            ),
            // Либо контрактор - текущий пользователь
            "OR:contractor:=" => $contractor,    
        ));
        
        $c->where(array(
            "status_id:!=" => 1,    
        ));
          
        $c->groupby("`{$this->classKey}`.id");
        
        return $c;
    }
    
    public function prepareRow(xPDOObject $object) {
        $data = parent::prepareRow($object);
        
        $menu = !empty($data['menu']) ? $data['menu'] : array();
        
        // Если заказ оформленный, то можно его оплатить
        if($data['status_id'] == 2){
            $menu[] = array(
                "text"      => "Оплатить",
                "handler"   => 'pay',
            );
        }
        
        $data['menu'] = $menu;
        
        return $data;
    }    
}

return 'modBasketWebOrdersGetlistProcessor';
