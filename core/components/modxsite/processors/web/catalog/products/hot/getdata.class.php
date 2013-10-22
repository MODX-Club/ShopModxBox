<?php
/*
    Получаем новинки
*/

require_once dirname(dirname(__FILE__)).'/getdata.class.php';

class modWebCatalogProductsHotGetdataProcessor extends modWebCatalogProductsGetdataProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'hot'   => true,       // Получать новинки
            "sort"  => "{$this->classKey}.publishedon",
            "dir"   => "DESC",
        ));
        
        return parent::initialize();
    }
    

}

return 'modWebCatalogProductsHotGetdataProcessor';