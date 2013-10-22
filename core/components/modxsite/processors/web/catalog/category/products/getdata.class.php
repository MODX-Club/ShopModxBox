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
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        // Получаем подразделы
        $category_id = (int)$this->getProperty('category_id');
        $categories = array($category_id);
        $this->getCategories($category_id, $categories);
        $c->where(array(
            "parent:IN" => $categories,
        ));
        return parent::prepareQueryBeforeCount($c);
    }
    
    protected function getCategories($parent, array & $categories){
        $q = $this->modx->newQuery('modResource', array(
            'deleted'   => 0,
            'published' => 1,
            'hidemenu'  => 0,
            'isfolder'  => 1,
            'parent'    => $parent,
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
        return $categories;
    }
}

return 'modWebCatalogCategoryProductsGetdataProcessor';