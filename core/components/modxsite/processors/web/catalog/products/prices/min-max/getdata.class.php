<?php

/*
    Получаем минимальную и максимальную цены
*/

require_once dirname(dirname(__FILE__)) . '/getdata.class.php';


class modWebCatalogProductsPricesMinmaxGetdataProcessor extends modWebCatalogProductsPricesGetdataProcessor{
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c = parent::prepareQueryBeforeCount($c);
        
        # print_r($c->query['columns']);
        # exit;
        
        $column = current(array_filter($c->query['columns'], function($value){
            return preg_match("/as (sm_price)$/", $value);
        }));
        
        
        $str = preg_replace("/as (sm_price)$/", "", $column);
        $columns = array(
            "min({$str}) as min_price",
            "max({$str}) as max_price",
        );
        
        $c->query['columns'] = $columns;
        
        return $c;
    }
    

    public function outputArray(array $array, $count = false){
        
        if($array){
            $array = current($array);
            # print_r($array);
            
            # exit;
            $array = array(
                "min_price" => $array['min_price'],
                "max_price" => $array['max_price'],
            );
        }
        
        # print_r($array);
        # exit;
        
        return $this->prepareResponse(array(
            'success' => true,
            'message' => $this->getMessage(),
            'object'  => $array,
        ));
    }
    
}


return 'modWebCatalogProductsPricesMinmaxGetdataProcessor';

