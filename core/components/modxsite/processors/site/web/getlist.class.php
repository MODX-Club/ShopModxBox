<?php

class modSiteWebGetlistProcessor extends modObjectGetListProcessor{
    public $classKey = 'modResource';
    public $defaultSortField = '';
    public $flushWhere = true;   // Flush query condition and search only by objects IDs
    protected $total = 0;
   
    protected $sources = array();


    public function initialize(){
        
        $this->setDefaultProperties(array(
            'cache'             => false,           // Use cache
            'cache_lifetime'    => 0,               // seconds
            'cache_prefix'      => 'getdata/',      
            'current'           => false,   // get and return only first element 
            'page'              => 0,   // !empty($_REQUEST['page']) ? (int)$_REQUEST['page'] : 0,
            'getPage'           => false,
            'getPageParamsSet'  => "getPage",   // Имя набора параметров для getPage
        ));
        
        $initialized = parent::initialize();
        
        /*
            Need limits and etc.
        */
        if($initialized !== true){
            return $initialized;
        }
        
        if($this->getProperty('current')){
            $this->setProperty('limit', 1);
        }
        
        if($page = $this->getProperty('page') AND $page > 1 AND $limit = $this->getProperty('limit', 0)){
            $this->setProperty('start', ($page-1) * $limit);
        }
        
        if(
            $sort = $this->getProperty('sort')
            AND mb_strpos($sort, ".", 0,  'utf-8') === false
            AND $fields = $this->modx->getFields($this->classKey)
            AND array_key_exists($sort, $fields)
        ){ 
            $this->setProperty('sort', "{$this->classKey}.{$sort}");
        }
        
        return !$this->hasErrors();
    }


    public function process(){
        
        // Use or not caching
        $cacheable = $this->getProperty('cache');
        
        if($cacheable){
            $key = $this->getProperty('cache_prefix') . md5( __CLASS__ . json_encode($this->getProperties()));
            if($cache = $this->modx->cacheManager->get($key)){
                return $this->prepareResponse($cache);
            }
        }
        
        $result = parent::process();
        
        if($cacheable){
            $this->modx->cacheManager->set($key, $result, $this->getProperty('cache_lifetime', 0));
        }
        
        return $result;
    }  

    
    /**
     * Get the data of the query
     * @return array
     */
    public function getData() {
        $data = array(
            'total' => 0,
            'results' => array(),
        );

        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);
        if(!$c = $this->getCount($c)){
            return $data;
        }

        $this->setSelection($c);
        
        $data['total']   = $this->total;
        $data['results'] = $this->getResults($c);
        return $data;
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
        
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));
        
        if ($limit || $start) {
            $query->limit($limit,$start);
        }
        
        $query = $this->prepareUniqObjectsQuery($query);
        if($query->prepare() && $query->stmt->execute() && $rows = $query->stmt->fetchAll(PDO::FETCH_ASSOC)){
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
            
            if($query->stmt AND $query->stmt->errorCode() !== "00000"){
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, __CLASS__);
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($query->stmt->errorInfo(), true));
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, $query->toSQL());
            }
            
            return false;
        }     
        
        return $c;
    }

    protected function prepareCountQuery(xPDOQuery & $query){
        if($where = (array)$this->getProperty('where')){
            $query->where($where);
        }
        return $query;
    }

    
    /*
     * Count total results
     */
    protected function countTotal($className, xPDOQuery & $criteria){
        $count = 0; 
        
        $query = clone($criteria);
        
        $stmt = null;
        $expr = '*';
        if ($pk = $this->modx->getPK($className)) {
            if (!is_array($pk)) {
                $pk = array($pk);
            }
            $expr = $this->modx->getSelectColumns($className, $query->getAlias(), '', $pk);
        } 
        if (isset($query->query['columns'])) {
            $query->query['columns'] = array();
        }
        if (!empty($query->query['groupby']) || !empty($query->query['having'])) {
            $query->select($expr);
            if ($query->prepare()) {
                $countQuery = new xPDOCriteria($this->modx, "SELECT COUNT(*) FROM ({$query->toSQL(false)}) cq", $query->bindings, $query->cacheFlag);
                $stmt = $countQuery->prepare();
            }
        } else {
            $query->select(array("COUNT(DISTINCT {$expr})"));
            $stmt = $query->prepare();
        }
        if ($stmt) {
            if ($stmt->execute()) {
                $count = intval($stmt->fetchColumn());
                
            }
            else if($stmt->errorCode() !== "00000"){
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, __CLASS__);
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($stmt->errorInfo(), true));
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, $query->toSQL());
            }
        } 
        
        return $count;
    }

    protected function prepareUniqObjectsQuery(xPDOQuery & $query){
        
        $query->select(array ("{$this->classKey}.id"));
        $query->distinct(); 
        
        return $query;
    } 

    protected function setSelection(xPDOQuery $c){
        $c->select(array(
            "{$this->classKey}.*",
            "{$this->classKey}.id as `object_id`",    // Make sure resource id will not overwrite
        ));
        return $c;
    }
    
    protected function getResults(xPDOQuery & $c){
        return $this->modx->getCollection($this->classKey,$c);
    }
    
    protected function getMessage(){return '';}

    /*
        Here you may add callback when caching anabled
    */
    
    /*
        В этот прописываем вызов всего того, что должно быть отработано даже тогда,
        когда результат процессора взят из кеша (к примеру, вызов сниппета getPage,
        а то если его не вызывать каждый раз, то не будет отображаться постраничность)
    */
    protected function prepareResponse($response){
        
        if($this->getProperty('getPage') AND $limit = $this->getProperty('limit') AND !empty($response['total'])){
            
            $this->modx->setPlaceholder('total', $response['total']);
            
            $snippet = "getPage";
            if($getPageParamsSet = $this->getProperty('getPageParamsSet')){
                $snippet .= "@{$getPageParamsSet}";
            }
            
            $this->modx->runSnippet($snippet, array(
                'limit' => $limit,
            ));
        }
        
        return $response;
    }

    public function outputArray(array $array, $count = false){
        
        if($this->getProperty('current')){
            if($array){
                $array = current($array);
                $_count = 1;
            }
            else{
                $_count = 0;
            }
        }
        else{
            $_count = count($array);
        }
        
        return $this->prepareResponse(array(
            'success' => true,
            'message' => $this->getMessage(),
            'count'   => $_count,
            'total'   => $count,
            'limit'   => (int)$this->getProperty('limit', 0),
            'page'    => (int)$this->getProperty('page', 0),
            'object'  => $array,
        ));
    }
    
    
    protected function getSourcePath($id = null, $callback = 'getBaseUrl', $params = array()){
        
        if(!$id){
            $id = $this->modx->getOption('default_media_source', null, 1);
        }
        
        if(empty($this->sources[$id])){
            // Получаем объект
            if(
                !$source = $this->modx->getObject('sources.modMediaSource', $id)
                OR !$source->initialize()
            ){
                return '';
            }
            $this->sources[$id] = & $source;
        }
         
        
        switch($callback){
            case 'getBaseUrl':
                if(!is_string($params)){
                    $params = '';
                }
                break;
        }
        
        $result = $this->sources[$id]->$callback($params); 
        
        return $result;
    }
}

return 'modSiteWebGetlistProcessor';
