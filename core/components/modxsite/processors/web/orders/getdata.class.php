<?php
/*
    Класс выборки товаров
*/

require_once MODX_CORE_PATH . 'components/shopmodx/processors/shopmodx/orders/getdata.class.php';

class modWebOrdersGetdataProcessor extends modShopmodxOrdersGetdataProcessor{


    public function initialize(){

        $this->setDefaultProperties(array(
            "format"    => "json",
        ));


        return parent::initialize();
    }


    public function prepareQueryBeforeCount(xPDOQuery $c){
        
        $c = parent::prepareQueryBeforeCount($c);

        $alias = $c->getAlias();

        $where = array(
        );

        if($statuses = (array)$this->getProperty("statuses")){
            
            $where['status_id:in'] = $statuses;

        }

        if($where){
            $c->where($where);
        }

        return $c;
    }
}

return 'modWebOrdersGetdataProcessor';
