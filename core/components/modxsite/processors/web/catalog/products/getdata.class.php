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
        
        $alias = $c->getAlias();
        
        
        $c->innerJoin('ShopmodxProduct', 'Product');
        
        if($this->getProperty('hot')){
            $c->innerJoin('modTemplateVarResource',  'hot', "hot.contentid = {$alias}.id AND hot.tmplvarid = 8 AND hot.value='1'");
        }
        
        // Поиск товаров в категории и подкатегориях
        if($category_id = $this->getProperty('category_id')){
            $categories = array();
            $this->getCategories($category_id, $categories);
            $c->where(array(
                "parent:IN" => $categories,
            ));
        }
        
        
        // Курс валюты 
        $base_currency_id = (int)$this->getProperty('base_currency_id');
        $c->leftJoin('modResource', "base_currency_doc", "base_currency_doc.id = {$base_currency_id}");
        $c->leftJoin('modResource', "currency_doc", "currency_doc.id = Product.sm_currency");
        $c->leftJoin('modTemplateVarResource', "course_tv", "course_tv.contentid = Product.sm_currency");
        
        $c->select(array(
            "Product.*",
            "course_tv.value as course",
            "if({$base_currency_id} != Product.sm_currency && course_tv.value, round(Product.sm_price * course_tv.value, 2), Product.sm_price) as sm_price",
            "if({$base_currency_id} != Product.sm_currency && course_tv.value, {$base_currency_id}, Product.sm_currency) as sm_currency",
            "if({$base_currency_id} != Product.sm_currency && course_tv.value, base_currency_doc.pagetitle, currency_doc.pagetitle) as currency_code",
        ));
        
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
        
        $c->leftJoin('modResource', "Parent");
        
        $c->select(array(
            "Product.id as `product_id`",
            "Parent.id as category_id",
            "Parent.uri as category_uri",
            "Parent.pagetitle as category_title",
        ));
        return $c;
    }
    
    
    protected function getCategories($parents, array & $categories){
        
        if(!is_array($parents)){
            $parents = explode(",", $parents);
        }
        
        $parents = array_map('intval', $parents);
        $parents = array_filter($parents, function($item){
            return $item > 0;
        });
        
        if(!$parents){
            return $categories;
        }
        
        $categories = $categories + $parents;
        
        $q = $this->modx->newQuery('modResource', array(
            'deleted'   => 0,
            'published' => 1,
            'hidemenu'  => 0,
            'isfolder'  => 1,
            'parent:in'    => $parents,
        ));
        
        $q->select(array(
            'id',    
        ));
        
        if($s = $q->prepare() AND $s->execute()){
            while($row = $s->fetch(PDO::FETCH_ASSOC)){
                $categories[] = $row['id'];
                $this->getCategories($row['id'], $categories);
            }
        }
        
        $categories = array_unique($categories);
        
        return $categories;
    }
}

return 'modWebCatalogProductsGetdataProcessor';
