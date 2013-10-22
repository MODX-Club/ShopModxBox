<?php
/*
    Обновляем массово картинки моделей
*/ 
    
class  ShopmodxGroupeditUpdateImagesProcessor extends modProcessor{
    
    public $classKey = 'modResource';
    
    public function initialize(){
        //print_r($this->getProperties());
        
        if($data = $this->modx->fromJSON($this->getProperty('data'))){
            $this->setDefaultProperties($data);
        }
        
        //print_r($this->getProperties());
        
        if(!(int)$this->getProperty('parent')){
            return 'Не был получен ID модели';
        }
        
        /*if(!$color = $this->getProperty('color')){
            return 'Не был получен цвет';
        }
        
        if(!$color = $this->getProperty('color')){
            return 'Не было получено цветовое исполнение';
        }*/
        
        return parent::initialize();
    }
    
    public function process(){
        
        $c = $this->modx->newQuery($this->classKey);
        $c->innerJoin('ShopProduct', 'Product');



        $color = $this->getProperty('color', null);
        $design = $this->getProperty('design', null);
        
        $c->leftJoin('modTemplateVarResource', 'colortv', "colortv.tmplvarid=2 AND colortv.contentid={$this->classKey}.id");
        $c->leftJoin('modTemplateVarResource', 'designtv', "designtv.tmplvarid=3 AND designtv.contentid={$this->classKey}.id");
        
        $where = array(
            "{$this->classKey}.parent" => (int)$this->getProperty('parent'),  
            "colortv.value" => $color,
            "designtv.value" => $design,
        );
        
        $c->where($where); 
        
        //$c->leftJoin('modTemplateVarResource', 'imagetv', "imagetv.tmplvarid=8 AND imagetv.contentid={$this->classKey}.id");
        
        $c->select(array(
            'colortv.value as color',
            'designtv.value as design',
            "{$this->classKey}.*",
        )); 
        
        /*$c->select(array(
            "{$this->classKey}.parent",
            "{$this->classKey}.pagetitle",
            'colortv.value as color',
            'designtv.value as design',
            'imagetv.value as image',
            "{$this->classKey}.id",
        )); */
        // $c->groupby('image');
        
        
        /*
        Группировать не надо, так как всегда будет одна запись
        $c->groupby('color');
        $c->groupby('design');
        */

        /*$c = $this->modx->newQuery('ShopProduct');
        $c->innerJoin('modResource', 'resource', 'resource.id=resource_id');
        $c->command('update');
        $c->where(array(
            'resource.parent'    => (int)$this->getProperty('parent'),
            'sm_price'  => $this->getProperty('old_price'),
        ));
        $c->set(array(
            'sm_price' => $this->getProperty('sm_price'),
        ));*/
        
        /*$c->prepare();
        print $c->toSQL();
        
        return;*/
        
        /*if(!$s = $c->prepare() OR !$s->execute()){
            if($s){
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($s->errorInfo(), 1));
            }
            return $this->failure('Ошибка выполнения запроса');
        }*/
        
        /*$c->prepare();
        print $c->toSQL();
        
        exit;*/
        
        if(!$docs = $this->modx->getCollection($this->classKey, $c)){
            return $this->failure('Не были получены документы');
        }
        
        foreach($docs as $doc){
            // print_r($doc->toArray());
            $doc->setTVValue(8, $this->getProperty('relativeUrl'));
        }
        
        return $this->success('Записи успешно обновлены');
    }
}

return 'ShopmodxGroupeditUpdateImagesProcessor';