<?php

/*
    Абстрактный класс для получения цен каталога
*/

require_once dirname(dirname(__FILE__)) . '/getdata.class.php';

abstract class modWebCatalogProductsPricesGetdataProcessor extends modWebCatalogProductsGetdataProcessor{
        
    public function initialize(){
        
        $this->setDefaultProperties(array(
            # "showunpublished"   => $this->modx->hasPermission('debug'),
            "limit"          => 0,
        ));
        
        return parent::initialize();
    }
    
    protected function getCount(xPDOQuery & $c){
        if(!$sortKey = $this->getProperty('sort')){
            $sortClassKey = $this->getSortClassKey();
            $sortKey = $this->modx->getSelectColumns($sortClassKey,$this->getProperty('sortAlias',$sortClassKey),'',array($this->getProperty('sort')));
        }
        
        $c = $this->prepareCountQuery($c);
        if(!$this->total = $this->countTotal($this->classKey,$c)){
            return false;
        }
        
        $c = $this->prepareQueryAfterCount($c);
        
        
        if($sortKey){
            $c->sortby($sortKey,$this->getProperty('dir'));
        }
        
        $query = clone $c; 
        
        return $c;
    }
    
    
    protected function setSelection(xPDOQuery $c) {
        return $c;
    }
    
    
    public function afterIteration(array $list){
        
        return $list;
    }
    
}

return 'modWebCatalogProductsPricesGetdataProcessor';
