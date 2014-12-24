<?php
/*
    Класс выборки товаров
*/ 

require_once dirname(dirname(dirname(__FILE__))).'/resources/getdata.class.php';

class modWebCatalogProductsGetdataProcessor extends modWebResourcesGetdataProcessor{
    
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'hot'                   => false,       // Получать новинки 
        ));
        
        $this->setProperties(array(
            "base_currency_id"         => (int)$this->modx->getOption("shopmodx.default_currency"), // ID базовой валюты магазина
        ));
        
        return parent::initialize();
    }
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c = parent::prepareQueryBeforeCount($c);
        
        $c->innerJoin('ShopmodxProduct', 'Product');
        
        if($this->getProperty('hot')){
            $c->innerJoin('modTemplateVarResource',  'hot', "hot.contentid = {$this->classKey}.id AND hot.tmplvarid = 8 AND hot.value='1'");
        }
        
        return $c;
    }
    
    
    
    # public function afterIteration(array $list){
    #     
    #     $list = parent::afterIteration($list);
    #     
    #     foreach($list as & $l){
    #           
    #         
    #         if(
    #             !empty($l['course']) 
    #             AND $course = (float)$l['course']
    #         ){
    #             $l['sm_price'] = round($l['sm_price'] * $course, 0); 
    #         }
    #         
    #     }
    #     
    #     return $list;
    # }
    
    
    protected function setSelection(xPDOQuery $c) {
        $c = parent::setSelection($c);
        
        $base_currency_id = (int)$this->getProperty('base_currency_id');
        
        $c->leftJoin('modResource', "Parent");
        
        // Курс валюты 
        $c->leftJoin('modResource', "base_currency_doc", "base_currency_doc.id = {$base_currency_id}");
        $c->leftJoin('modResource', "currency_doc", "currency_doc.id = Product.sm_currency");
        $c->leftJoin('modTemplateVarResource', "course_tv", "course_tv.contentid = Product.sm_currency");
        
        $c->select(array(
            "Product.*",
            "Product.id as `product_id`",
            "Parent.id as category_id",
            "Parent.uri as category_uri",
            "Parent.pagetitle as category_title",
            "course_tv.value as course",
            "if({$base_currency_id} != Product.sm_currency && course_tv.value, round(Product.sm_price * course_tv.value, 2), Product.sm_price) as sm_price",
            "if({$base_currency_id} != Product.sm_currency && course_tv.value, {$base_currency_id}, Product.sm_currency) as sm_currency",
            "if({$base_currency_id} != Product.sm_currency && course_tv.value, base_currency_doc.pagetitle, currency_doc.pagetitle) as currency_code",
        ));
        return $c;
    }
}

return 'modWebCatalogProductsGetdataProcessor';