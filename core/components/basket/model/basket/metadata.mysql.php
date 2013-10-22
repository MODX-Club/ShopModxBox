<?php
// Явно подгружаем класс документа

// $this->loadClass('ShopmodxResourceProduct');

/*$xpdo_meta_map = array (
  'modResource' => 
  array (
    'ShopResourceProduct',
  ),
);*/

/*foreach((array)$this->getDescendants('modResource') as $class){
    $xpdo_meta_map[$class] = array('NpghrdwshopResource');  
}*/

// Переопределяем связь класса
/*$this->map['ShopmodxResourceProduct']['composites']['Product'] = array (
    'class' => 'ShopProduct',
    'local' => 'id',
    'foreign' => 'resource_id',
    'cardinality' => 'one',
    'owner' => 'local',
);*/