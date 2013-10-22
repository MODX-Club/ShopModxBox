<?php
/*
    Получаем данные корзины
*/
require_once MODX_CORE_PATH . 'components/shopmodx/processors/web/getdata.class.php';

class modBasketMgrOrdersProductsGetdataProcessor extends ShopmodxWebGetlistProcessor{
    
    public $classKey = 'OrderProduct';
    
    protected $sum = 0;         // Общая сумма заказа
    protected $positions = 0;   // Количество позиций
    protected $quantity = 0;    // Общее количество товаров
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        $where = array();
        
        if($order_id = (int)$this->getProperty('order_id')){
            $where['order_id'] = $order_id;
        }
        
        if(!$this->getProperty('show_canceled', false)){
            $where['quantity:>'] = 0;
        }
        
        if($where){
            $c->where($where);
        }
        
        return $c;
    }
    
    public function setSelection(xPDOQuery $c){
        $c = parent::setSelection($c);
        
        $c->innerJoin('ShopmodxProduct', 'Product');
        
        $c->select(array(
            "Product.resource_id",    
        ));
        
        return $c;
    }
    
    public function afterIteration(array $list){
        $list = parent::afterIteration($list);
        
        $this->quantity = 0;
        $this->sum = 0;
        
        // Получаем id всех товаров и подсчитываем общее число товаров и сумму
        $ids = array();
        foreach($list as $l){
            $this->quantity += $l['quantity'];
            $this->sum += $l['quantity'] * $l['price'];
            $ids[] = $l['resource_id'];
        }
        
        // Получаем данные товаров
        $resources = array();
        
        if($ids){
            $ids = array_unique($ids);
            
            if(
                $response = $this->modx->runProcessor('web/catalog/products/getdata', array(
                    "limit"    => 0,
                    "showhidden"    => true,
                    "where" => array(
                        "id:in" => $ids,
                    ),    
                ), array(
                    'processors_path' => MODX_CORE_PATH . 'components/modxsite/processors/',    
                ))
                AND $resources = $response->getObject())
            {
                // print_r($response->getResponse());
                foreach($list as & $l){
                    if(!empty($resources[$l['resource_id']])){
                        $l = array_merge($resources[$l['resource_id']], $l);
                    }
                }
            }
            
        }
        
        return $list;
    }
    
    public function outputArray(array $array, $count = false){
        $result = parent::outputArray($array, $count);
        
        $result['sum'] = $this->sum;
        $result['quantity'] = $this->quantity;
        $result['positions'] = $count;
        
        return $result;
    }    
    
}

return 'modBasketMgrOrdersProductsGetdataProcessor';