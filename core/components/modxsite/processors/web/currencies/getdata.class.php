<?php

require_once dirname(dirname(__FILE__))  .'/getdata.class.php';

class modWebCurrenciesGetdataProcessor extends modWebGetdataProcessor{
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