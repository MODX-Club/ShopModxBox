<?php


require_once dirname(dirname(dirname(__FILE__))) . '/images/thumb.class.php';

class modSiteWebResourcesImagesThumbProcessor extends modSiteWebImagesThumbProcessor{
    
    protected $resource = null;
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "tv" => "image",
        ));
        
        return parent::initialize();
    }
    
    
    public function process(){
        
        if(!$this->getProperty('src')){
            
            $alias = $this->getProperty('alias');
            
            if(!$this->resource_id = (int)$this->getProperty('resource_id')){
                
                return $this->failure("Не указан ID документа");
            }
            
            if(!empty($this->modx->resource) AND $this->resource_id == $this->modx->resource->id){
                
                $this->resource = $this->modx->resource;
            }
            else if( !$this->resource = $this->modx->getObject('modResource', $this->resource_id)){
                
                return $this->failure("Не был получен документ");
            }
            
            else if($this->resource->alias != $alias){
                return $this->failure("Неверный алиас картинки");
            }
            
            // else
            if(!$tv = $this->getProperty('tv')){
                return $this->failure("Не было получено название TV-поля");
            }
            
            $this->setProperty('src', $this->resource->getTVValue($tv));
        }
        
        # print '<pre>';
        # 
        # print_r($this->properties);
        
        
        return parent::process();
    }
    
    
    protected function getBasename(){
        $resource = & $this->resource;
        $extension = pathinfo($this->getProperty('src'), PATHINFO_EXTENSION);
        return "{$resource->alias}-{$resource->id}.{$extension}";
    }
    
}

return 'modSiteWebResourcesImagesThumbProcessor';

