<?php
/*
    Получаем товары, сгруппированные по цене
*/
if($this instanceof Modxsite){
    $modxsite = & $this;
}
else{
    $modxsite = & $this->modxsite;
}

$modxsite->loadProcessor('web.catalog.products.getdata', 'npghardwarestore');

class ShopmodxGroupeditGetgoodsgroupedbypricesProcessor extends modWebCatalogProductsGetdataProcessor{
    
    
    public function initialize(){
        if(!(int)$this->getProperty('model_id')){
            return 'Не был получен ID модели';
        }
        
        return parent::initialize();
    }
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        $c->where(array(
            'parent' => (int)$this->getProperty('model_id'),    
        ));
        
        $c = $this->prepareGroupCondition($c);
        
        return $c;
    }
    
    protected function prepareGroupCondition(xPDOQuery $c){
        $c->groupby('sm_price');
        $c->groupby('sm_trade_price');
        return $c;
    }
    
    /*public function setSelection(xPDOQuery $c){
        $c = parent::setSelection($c);
        $c->select(array(
            'sm_price as original_price',  
        ));
        return $c;
    }*/
    
    
    public function outputArray(array $array,$count = false) {
        $results = array();
          
        foreach($array as $a){
            $results[] = $a;
        }
        
        return $this->modx->toJSON(array(
            'success'   => true,
            'message'   => '',
            'count'   => count($array),
            'total'   => $count,
            'results'    => $results,
        ));
    }
}

return 'ShopmodxGroupeditGetgoodsgroupedbypricesProcessor';