<?php

/*
    Получаем картинки, сгруппированные по цвету и исполнению товара.
    Цель: массовое обновление похожих товаров
*/
 

if($this instanceof Modxsite){
    $modxsite = & $this;
}
else{
    $modxsite = & $this->modxsite;
}

$modxsite->loadProcessor('web.catalog.products.getdata', 'npghardwarestore');

// class modGroupedImagesGetlistProcessor extends modWebCatalogProductsGetdataProcessor{
// class modGroupedImagesGetlistProcessor extends modWebResourceproductGetDataProcessor{
class modGroupedImagesGetlistProcessor extends ShopmodxWebGetDataProcessor{
    
    public function initialize(){
        if(!(int)$this->getProperty('model_id')){
            return 'Не был получен ID модели';
        }
        
        $this->setDefaultProperties(array(
            'sort' => 'image',    
        ));
        
        return parent::initialize();
    }
    
    
    /*
        Перегружаем базовые процессоры shopModx-а
    */  
     
    /*
        ****************************************
    */
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        $c->innerJoin('ShopProduct', 'Product');
        
        
        $c->leftJoin('modTemplateVarResource', 'colortv', "colortv.tmplvarid=2 AND colortv.contentid={$this->classKey}.id");
        $c->leftJoin('modTemplateVarResource', 'designtv', "designtv.tmplvarid=3 AND designtv.contentid={$this->classKey}.id");
        $c->leftJoin('modTemplateVarResource', 'imagetv', "imagetv.tmplvarid=8 AND imagetv.contentid={$this->classKey}.id");
        
        
        $c->select(array(
            "{$this->classKey}.parent",
            "{$this->classKey}.pagetitle",
            'colortv.value as color',
            'designtv.value as design',
            'imagetv.value as image',
            "{$this->classKey}.id",
        )); 
        
        
        $c->where(array(
            "{$this->classKey}.parent" => (int)$this->getProperty('model_id'),    
        )); 
        
        $c->groupby('color');
        $c->groupby('design');
        $c->groupby('image');
        
        /*$c->prepare();
        print $c->toSQL();
        exit;*/
        
        return $c;
    }    
    
    /*
        Возвращаем запрос как есть, так как у нас запрос с группировкой
    */
    protected function PrepareUniqObjectsQuery(xPDOQuery & $query){        
        return $query;
    }  
    

    protected function countTotal($className, xPDOQuery & $query){
        if(!$query->prepare()){
            return false;
        }
        $sql = "SELECT count(*) as count FROM (". $query->toSQL() .") as t;";
        
        if(!$stmt = $this->modx->prepare($sql) OR !$stmt->execute()){
            return false;
        }
        return current((array)$stmt->fetch(PDO::FETCH_NUM));
    }    
    
    
    protected function setSelection(xPDOQuery $c){
        
        $c->select(array(
            "Product.*",
            "Product.id as `product_id`",
            "{$this->classKey}.id as object_id",
        ));
        /*$c->prepare();
        print $c->toSQL();
        exit;*/
        return $c;
    }
    
    public function afterIteration(array $data) {
        $data = parent::afterIteration($data);
        
        // УРЛ источника картинок:
        $imagesUrl = $this->modx->runSnippet('getSourcePath');
        
        foreach($data as & $d){
            
            $d['source_path'] = $imagesUrl;
            
            // Получаем картинку
            if(!empty($d['image'])){
                
                /*
                    Имя директории нам надо для быстрого перехода к папке изображения
                */
                $pi = pathinfo($d['image']);
                $d['dirname'] = $pi['dirname'].'/';
                
                
                // $image = $imagesUrl. $d['image'];
                //$d['image'] = $image;
                
                $d['relativeUrl'] = $d['image'];
                 
                
                $d['fullRelativeUrl'] = $imagesUrl. $d['image'];
                
                // exit;
            }            
        } 
        
        return $data;
    }    
    
    
    public function outputArray(array $array,$count = false) {
        $results = array();
          
        foreach($array as $a){
            $results[] = $a;
        }
        /*print '<pre>';
        print_R($array);
        exit;*/
        return $this->modx->toJSON(array(
            'success'   => true,
            'message'   => '',
            'count'   => count($array),
            'total'   => $count,
            'results'    => $results,
        ));
    }
}

return 'modGroupedImagesGetlistProcessor';