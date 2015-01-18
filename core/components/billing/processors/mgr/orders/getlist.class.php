<?php

require_once dirname(dirname(__FILE__)). '/getlist.class.php';

class modMgrOrdersGetlistProcessor extends modMgrGetlistProcessor{
    public $classKey = 'Order';
    public $defaultSortField = 'id';
    
    public function initialize(){
        $this->setDefaultProperties(array(
            "sort"  => "{$this->classKey}.id",
            "dir"   => "desc",
        ));
        return parent::initialize();
    }
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        $order_products_table = $this->modx->getTableName('OrderProduct');
        
        $c->innerJoin('OrderStatus', 'Status');
        
        $c->select(array(
            "`{$this->classKey}`.id as order_id", 
            "Status.status as status_str", 
            "(select sum(op.price * op.quantity) from {$order_products_table} op where op.order_id = {$this->classKey}.id) as sum",
        ));
        
        
        /*$c->prepare();
        print $c->toSQL();
        exit;*/
         
        return $c;
    }
}

return 'modMgrOrdersGetlistProcessor';
