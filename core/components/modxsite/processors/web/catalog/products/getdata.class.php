<?php
/*
    Класс выборки товаров
*/ 

require_once dirname(dirname(dirname(__FILE__))).'/getdata.class.php';

class modWebCatalogProductsGetdataProcessor extends modWebGetdataProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'hot'                   => false,       // Получать новинки
            
            
            /*
                Схема ссылки на картинку.  
                - false : Отсутствуе. Будет выдано значение TV-параметра как есть
                - base  : Будет сформировано с учетом УРЛ-а на медиасурс от корня сайта
                - full  : Будет сформирован полный путь, включая http://
                
            */
            'image_url_schema'      => 'base',      
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
    
    protected function setSelection(xPDOQuery $c) {
        $c = parent::setSelection($c);
        $c->leftJoin('modResource', "Parent");
        $c->select(array(
            "Product.*",
            "Product.id as `product_id`",
            "Parent.id as category_id",
            "Parent.uri as category_uri",
            "Parent.pagetitle as category_title",
        ));
        return $c;
    }
    
    //
    public function afterIteration(array $list){
        $list = parent::afterIteration($list);
        
        switch($this->getProperty('image_url_schema')){
            case 'base':
                $images_base_url = $this->modx->runSnippet('getSourcePath');
                break;
                
            case 'full':
                $images_base_url = $this->modx->getOption('site_url');
                $images_base_url .= preg_replace("/^\//", "", $this->modx->runSnippet('getSourcePath'));
                break;
                
            default: $images_base_url = '';
        }
        
        foreach($list as & $l){
            $l['image'] = '';
            if(!empty($l['tvs']['image']['value'])){
                $l['image'] = $images_base_url . $l['tvs']['image']['value'];
            }
            else{
                $l['imageDefault'] = $images_base_url . 'products/No-Photo.jpg';
            }
        }
        return $list;
    }
}

return 'modWebCatalogProductsGetdataProcessor';