<?php

/*
    Обновляем цену товаров модели, выбирая товары по заявленной цене
*/


require_once dirname(__FILE__).'/getlist.class.php';

class  ShopmodxGroupeditUpdatepriceProcessor extends ShopmodxGroupeditGetgoodsgroupedbypricesProcessor{
    
    public function initialize(){
        
        if($data = $this->modx->fromJSON($this->getProperty('data'))){
            $this->setDefaultProperties($data);
            unset($this->properties['data']);
        }
        
        if(!$parent = (int)$this->getProperty('parent')){
            return 'Не был получен ID модели';
        }
        $this->setProperty('model_id', $parent); 
        
        if(!$this->getProperty('id')){
            return 'Не был получен ID товара';
        }
        
        $sm_price = $this->getProperty('sm_price');
        if(!isset($sm_price)){
            return 'Не была указана цена';
        }
        
        /*$original_price = $this->getProperty('original_price');
        if(!isset($original_price)){
            return 'Не была указана исходная цена';
        }*/
        
        return parent::initialize();
    }    
    
    
    public function beforeQuery(){
        
        // Получаем текущую стоимость товара
        if(!$o = $this->modx->getObject('ShopProduct', array(
            'resource_id'   => (int)$this->getProperty('id')
        ))){
            return 'Не был получен объект товара';
        }
        
        $this->setProperty('original_price', $o->get('sm_price'));
        
        return parent::beforeQuery();
    }
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        $c->where(array(
            'Product.sm_price' => $this->getProperty('original_price'),    
        ));
        
        /*$c->prepare();
        print $c->toSQL();
        
        exit;;*/
        
        return $c;
    }
    
    protected function prepareGroupCondition(xPDOQuery $c){
        return $c;
    }
    
    /*
        Обновляем все элементы
    */
    public function afterIteration(array $list){
        
        // print_r($list);
        foreach($list as $l){
            if($product_id = $l['product_id'] AND $product = $this->modx->getObject('ShopProduct', $product_id)){
                $product->fromArray($this->getProperties());
                $product->save();
                
                //  print_r($product->toArray());
            }
        }
        
        return $list;
    }    
    

    
    public function outputArray(array $array,$count = false) {
        
        return $this->modx->toJSON(array(
            'success'   => true,
            'message'   => '',
            /*'results'    => array(
                array(
                    'id'        => $this->getProperty('id'),   
                    'sm_price' =>  $this->getProperty('sm_price'),   
                    'original_price' =>  $this->getProperty('original_price'),   
                )    
            ),*/
        ));
    }

/*    protected function getResults(xPDOQuery & $c){
        return $this->modx->getCollection($this->classKey,$c);
    }   */   
    
}

return 'ShopmodxGroupeditUpdatepriceProcessor';

/*
    Устаревший вариант
*/
class  ShopmodxGroupeditUpdatepriceProcessor___ extends modProcessor{
    
    public function initialize(){
        //print_r($this->getProperties());
        
        if($data = $this->modx->fromJSON($this->getProperty('data'))){
            $this->setDefaultProperties($data);
        }
        
        //print_r($this->getProperties());
        
        if(!(int)$this->getProperty('parent')){
            return 'Не был получен ID модели';
        }
        
        if(!$old_price = (int)$this->getProperty('old_price')){
            return 'Не была получена старая цена';
        }
        
        if(!$sm_price = (int)$this->getProperty('sm_price')){
            return 'Не была получена новая цена';
        }
        
        return parent::initialize();
    }
    
    public function process(){
        
        $c = $this->modx->newQuery('ShopProduct');
        $c->innerJoin('modResource', 'resource', 'resource.id=resource_id');
        $c->command('update');
        $c->where(array(
            'resource.parent'    => (int)$this->getProperty('parent'),
            'sm_price'  => $this->getProperty('old_price'),
        ));
        $c->set(array(
            'sm_price' => $this->getProperty('sm_price'),
        ));
        
        /*$c->prepare();
        print $c->toSQL();
        
        return;*/
        
        if(!$s = $c->prepare() OR !$s->execute()){
            if($s){
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($s->errorInfo(), 1));
            }
            return $this->failure('Ошибка выполнения запроса');
        }
        
        return $this->success('Записи успешно обновлены');
    }
     
}

return 'ShopmodxGroupeditUpdatepriceProcessor';