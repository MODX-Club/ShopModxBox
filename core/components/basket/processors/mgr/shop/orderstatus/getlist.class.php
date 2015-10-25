<?php

class ShopOrderStatusGetlistProcessor extends modObjectGetlistProcessor{
    public $classKey = 'OrderStatus';
    
    
    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 0,
            "sort"  => "rank",
            "dir"   => "ASC",
        ));
        return parent::initialize();
    }
    
    
    public function beforeIteration(array $list) {
        
        if($this->getProperty('show_empty_text')){
            $list[] = array(
                "id"    => 0,
                "status"  => "Выберите из списка",
            );
        }
        
        return $list;
    }
    
    public function prepareRow($object){
        
        $row = parent::prepareRow($object);
        
        $menu = array();
         
        
        $menu[] = array(
            'text' => 'Удалить цвет',
            'handler'   => 'this.ClearColor',
        );
        
        $row['menu'] = $menu;
        
        return $row;
    }
}

return 'ShopOrderStatusGetlistProcessor';