<?php
/*
    Получаем товары категорий и подкатегорий
*/ 

require_once dirname(dirname(dirname(__FILE__))).'/products/getdata.class.php';

class modWebCatalogCategoryProductsGetdataProcessor extends modWebCatalogProductsGetdataProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'category_id'   => $this->modx->resource->id,    
        ));
        
        if(!(int)$this->getProperty('category_id')){
            return "Не была указана категория";
        }
        
        return parent::initialize();
    }
}

return 'modWebCatalogCategoryProductsGetdataProcessor';