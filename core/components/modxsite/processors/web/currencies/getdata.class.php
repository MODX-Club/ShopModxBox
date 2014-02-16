<?php

require_once dirname(dirname(__FILE__))  .'/resources/getdata.class.php';

class modWebCurrenciesGetdataProcessor extends modWebResourcesGetdataProcessor{
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'limit'             => 0,
        ));
        
        return parent::initialize();
    }
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c = parent::prepareQueryBeforeCount($c);
        
        $c->where(array(
            'class_key'  => 'ShopmodxResourceCurrency',    
        ));
        
        return $c;
    }
}
    
return 'modWebCurrenciesGetdataProcessor';  