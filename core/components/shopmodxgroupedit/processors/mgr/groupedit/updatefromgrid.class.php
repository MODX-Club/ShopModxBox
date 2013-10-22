<?php

/*
    Обновляем документы из грида
    Надо учитывать сразу, что это процессор для различных типов объектов (modDocument, ShopmodxResourcePropduct и т.п.)
*/
require_once MODX_PROCESSORS_PATH .'resource/update.class.php';

class ShopmodxGroupeditUpdatefromgridProcessor extends modResourceUpdateProcessor{
    
    public static function getInstance(modX &$modx,$className,$properties = array()) {
        
        if(isset($properties['data'])){
            $data = (array)$modx->fromJSON($properties['data']);
            $properties = array_merge($properties, $data);
            unset($properties['data'], $data);
        }
         
        return parent::getInstance($modx,$className,$properties);
    } 
}

return 'ShopmodxGroupeditUpdatefromgridProcessor';