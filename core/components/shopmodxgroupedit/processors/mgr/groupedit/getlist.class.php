<?php

/*
    ShopmodxGroupeditGetlistInitializerProcessor
    ShopmodxGroupeditProductsGetlistProcessor
    ShopmodxGroupeditProductsModelsGetdataProcessor  
    ShopmodxGroupeditDocumentsGetdataProcessor
    
*/



require_once MODX_CORE_PATH . 'components/modxsite/processors/site/web/getdata.class.php';

/*
    Инициализация. Определяем, какой процессор использовать.
*/

class ShopmodxGroupeditGetlistInitializerProcessor extends modProcessor{
    public static function getInstance(modX &$modx,$className,$properties = array()) {
        /** @var modProcessor $processor */
        
        switch($properties['listType']){
                
            case 'models':
                $className = 'ShopmodxGroupeditProductsModelsGetdataProcessor';
                break;
                
            case 'products':
                # $className = 'ShopmodxGroupeditProductsGetlistProcessor';
                # break;
            case 'documents':
                $className = 'ShopmodxGroupeditDocumentsGetdataProcessor';
                break;
                
            default: $className = __CLASS__;
        }
        
        $processor = new $className($modx,$properties);
        return $processor;
    }
    
    public function process(){
        return $this->failure('Недопустимый тип вывода');
    }
    
}



/*
    Получаем документы
*/
class ShopmodxGroupeditDocumentsGetdataProcessor extends modSiteWebGetlistProcessor{
 
    public function initialize(){
        /*$this->modx->setLogLevel(3);
        $this->modx->setLogTarget('HTML');*/
        
        $this->setDefaultProperties(array(
            'limit'         => 20,
            'parent'        => 0,
            'context_key'   => 'web',
            'sort'          => '',
            'dir'           => 'ASC',
        ));
        
        # if($sort = $this->getProperty('sort')){
        #     $this->setProperty('sort', "{$this->classKey}.{$sort}");
        # }
        
        return parent::initialize();
    }
    
    protected function getCount(xPDOQuery & $c){
        
        $query = clone $c;
        $query = $this->prepareCountQuery($query);
        if(!$this->total = $this->countTotal($this->classKey,$query)){
            return false;
        }
        
        # $sortKey = 'pagetitle';
        if($sortKey = $this->getProperty('sort')){
            $dir = $this->getProperty('dir');
            $c->sortby($sortKey,$dir);
            $query->sortby($sortKey,$dir);
        }
        else{
            $sortKey = 'show_in_tree';
            $dir = 'DESC';
            $c->sortby($sortKey,$dir);
            $query->sortby($sortKey,$dir);
            
            $sortKey = 'isfolder';
            $dir = 'DESC';
            $c->sortby($sortKey,$dir);
            $query->sortby($sortKey,$dir);
        }
        
        
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));
        
        if ($limit > 0) {
            $query->limit($limit,$start);
        }
        
        $query = $this->prepareUniqObjectsQuery($query);
        if($query->prepare() && $query->stmt->execute() && $rows = $row = $query->stmt->fetchAll(PDO::FETCH_ASSOC)){
            $IDs = array();
            foreach($rows as $row){
                $IDs[] = $row['id'];
            }
            if ($this->flushWhere && isset($c->query['where'])) $c->query['where'] = array();
            $c->where(array(
                "{$this->classKey}.id:IN" => $IDs,
            ));
        }
        else{
            return false;
        }     
        
        return $c;
    }    
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        $c->leftJoin('ShopmodxProduct', 'Product');
        
        $where =  array();
        
        if($this->getProperty('listType') == 'products'){
            $where["Product.id:!="] = null;
        }
        else{
            $where["{$this->classKey}.parent"] = $this->getProperty('parent');
        }
         
        
        if($context_key = $this->getProperty('context_key')){
            $where["{$this->classKey}.context_key"] = $this->getProperty('context_key');
        }
        
        $c->where($where);
        return $c;
    }
     

    protected function setSelection(xPDOQuery $c){
        $c->leftJoin('modResource', 'Parent');
        $c->leftJoin('modResource', "Currency", "Currency.id = Product.sm_currency");
        $c->select(array(
            "Product.*",    
            'Parent.parent as uplevel_id',    
            'Parent.pagetitle as parent_title',   
            'Currency.pagetitle as currency_title',  
        ));
        
        return parent::setSelection($c);
    } 
 
    public function afterIteration(array $data){
        
        foreach($data as & $d){
            $menu = array();
            
            $menu[] = array(
                'text' => 'Редактировать',
                'handler' => 'this.editResource',
            );
            
            $menu[] = array(
                'text' => 'Просмотр',
                'handler' => 'this.showResource',
            );
            
            // Формируем меню для определенных типов объектов
            if(!empty($d['object_type'])){
                
                // Для моделей товаров
                if($d['object_type'] == 'model'){
                    
                    $menu[] = array(
                        'text' => 'Изменить цены',
                        'handler' => 'this.changeModelPrices',
                    );
                    
                    $menu[] = array(
                        'text' => 'Изменить картинку',
                        'handler' => 'this.changeModelImage',
                    );
                }
                
                // Для товаров
                else if($d['object_type'] == 'product'){
                    
                }
            }
            
            
            $d['menu'] = $menu;
        }
        
        return $data;
    }
    
    public function prepareRow(xPDOObject $object) {
        
        /*
            Определяем тип объекта
        */
        if($object instanceOf ShopmodxResourceProductModel){
            $object->set('object_type', 'model');
        }
        else if($object instanceOf ShopmodxResourceProduct){
            $object->set('object_type', 'product');
        }
        else{
            $object->set('object_type', 'document');
        }
        return parent::prepareRow($object);
    }    
    
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




/*
    Получаем товары
*/
/*class ShopmodxGroupeditProductsGetlistProcessor extends modWebCatalogProductsGetdataProcessor{
 
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'limit' => 20,
        ));
        
        return parent::initialize();
    }
    
    
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
}*/


/*
    Поулчаем модели товаров
*/
/*class ShopmodxGroupeditProductsModelsGetdataProcessor extends modWebCatalogModelsGetdataProcessor{
 
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'limit' => 20,
        ));
        
        return parent::initialize();
    }
    
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
}*/




return 'ShopmodxGroupeditGetlistInitializerProcessor';