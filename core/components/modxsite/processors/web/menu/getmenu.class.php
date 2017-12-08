<?php

class modSiteWebGetmenuProcessor extends modProcessor{
    
    public $classKey = 'modResource';
    
    protected $activeIDs = array();     // ID of active parents
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'startId'           => !empty($this->modx->resource->id) ? $this->modx->resource->id : 0,
            'id'                => 'menu',      // Menu id
            'cacheable'         => false,
            'level'             => 1,
            'sortBy'            => 'menuindex',
            'sortOrder'         => 'ASC',
            'levelClass'        => '',
            'activeClass'       => 'active',
            'ignoreHidden'      => false,
            'showUnpublished'   => false,
            'hideSubMenus'      => false,
            'context_key'       => 'web',
        ));
        
        return parent::initialize();
    }
    
    
    public function process() {
        $output = '';
        
        // get active parents
        if(!empty($this->modx->resource) AND $this->modx->resource instanceOf modResource){
            $resource = $this->modx->resource;
            $this->activeIDs[] = $resource->id;
            
            while($resource = $resource->getOne('Parent')){
                $this->activeIDs[] = $resource->id;
            }
        }
        
        // get menu items
        if(!$items = $this->getMenuItems()){
            return;
        }
        
        // prepare menu items
        $items = $this->prepareMenu($items);
        
        return array(
            'success'   => true,
            'message'   => '',
            'object'     => $items,
        );
    }
    
    
    public function getMenuItems(){
        $items = array();
        
        $startId = $this->getProperty('startId');
        $level = $this->getProperty('level');
        $cacheable = $this->getProperty('cacheable');
        $id = $this->getProperty('id', 'menu');
        $cacheKey = $this->modx->context->key."/{$id}/{$startId}";
        
        if($cacheable){
            if($fromCache = $this->modx->cacheManager->get($cacheKey)){
                return $fromCache;
            }
        }
            
        //else
        if($items = $this->getItems($startId, $level)){
            if($cacheable){
                $this->modx->cacheManager->set($cacheKey, $items);
            }
        }
        
        return $items;
    }
      


    protected function getItems($parent, $level){
        $level--;
        $items = array();
        
        // If 
        if($this->getProperty('hideSubMenus') AND !in_array($parent, $this->activeIDs)){
            return $items;
        }
        
        $q = $this->prepareGetitemsQuery($parent);
        
        if($q->prepare() && $q->stmt->execute()){
            while($row = $q->stmt->fetch(PDO::FETCH_ASSOC)){
                
                $row = $this->prepareRow($row, $level);
                
                if($level>0){
                    $row['childs'] = $this->getItems($row['id'], $level);
                }
                else{
                    $row['childs'] = array();
                }
                $items[$row['id']] = $row;
            }
        }
        
        
        return $items;
    }
    
    protected function prepareRow(array $row, $level){
        return $row;
    }
    
    protected function & prepareGetitemsQuery($parent){
        $q = $this->modx->newQuery($this->classKey);
        
        $where = $this->getDefaultConditions();
        
        $where['parent'] = $parent;

        if($this->getProperty('context_key')){
            $where['context_key']=$this->getProperty('context_key');
        }

        $q->where($where);


        $q->select(array(
            "{$this->classKey}.id", 
            "{$this->classKey}.parent", 
            "{$this->classKey}.pagetitle", 
            "{$this->classKey}.longtitle", 
            "{$this->classKey}.description", 
            "{$this->classKey}.menutitle", 
            "{$this->classKey}.link_attributes", 
            "{$this->classKey}.uri", 
            "{$this->classKey}.alias",
        ));
        
        $q->sortby($this->getProperty("sortBy"), $this->getProperty('sortOrder'));
        
        $q->prepare();
        print $q->toSQL();

        return $q;
    }
    
    
    protected function prepareMenu(array & $items, $currentlevel=1){
        $levelClass = $this->getProperty('levelClass');
        $activeClass = $this->getProperty('activeClass');
        
        foreach($items as &$item){
            
            $cls = array();
            
            if($levelClass){
                $cls[] = "{$levelClass}{$currentlevel}";
            }
            
            $item['linktext'] = ($item['menutitle'] ? $item['menutitle'] : $item['pagetitle']);
            
            if(in_array($item['id'], $this->activeIDs)){
                if($activeClass){
                    $cls[] = $activeClass;   
                }
            }
            
            $item['cls'] = implode(" ", $cls);
            
            if($item['childs']){
                $item['childs'] = $this->prepareMenu($item['childs'], $currentlevel+1);
            }
        }
        
        return $items;
    }
    
    
    protected function getDefaultConditions(){
        $where = array(
            'deleted'   => 0,
        );
        
        if(!$this->getProperty('showUnpublished')){
            $where['published'] = true;
        }
        
        if(!$this->getProperty('ignoreHidden')){
            $where['hidemenu'] = false;
        }
        
        if($_where = $this->getProperty('where')){
            $where = array_merge($where, $_where);
        }
        return $where;
    }
}


return 'modSiteWebGetmenuProcessor';
