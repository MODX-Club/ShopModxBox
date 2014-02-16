<?php
/*
    Получаем все дочерние категории.
    По умолчанию поиск выполняется во всем каталоге
*/ 

require_once dirname(dirname(dirname(__FILE__))).'/resources/getdata.class.php';


class modWebCatalogCategoryGetdataProcessor extends modWebResourcesGetdataProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'category_id'   => $this->modx->getOption('yandexmarket.catalog_root'),    
        ));
        
        if(!(int)$this->getProperty('category_id')){
            return "Не была указана категория";
        }
        
        return parent::initialize();
    }
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c = parent::prepareQueryBeforeCount($c);
         
        $categories = array();
        $where = array(
            'id:in'    => $this->getCategories((int)$this->getProperty('category_id'), $categories),
        );
        
        $c->where($where);
        
        return $c;
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

return 'modWebCatalogCategoryGetdataProcessor';