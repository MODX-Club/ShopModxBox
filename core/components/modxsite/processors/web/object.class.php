<?php

require_once __DIR__ . '/../site/web/object.class.php';

abstract class modWebObjectProcessor extends modSiteWebObjectProcessor{
    
    public function checkPermissions() {
        return $this->modx->user->id && parent::checkPermissions();
    }


    public function initialize() {

        $request_body = file_get_contents('php://input');

        if($request_body AND $data = json_decode($request_body, 1)){
            $this->setProperties($data);
        }
        
        foreach($this->properties as $field => & $value){

            if(!is_scalar($value)){
                continue;
            }

            $v = (string)$value;

            if($v === "null"){
                $value = null;
            }
            else if($v === "true"){
                $value = true;
            }
            else if($v === "false"){
                $value = false;
            }
            else if($v === "NaN"){
                unset($this->properties[$field]);
            }
            else if($v === "undefined"){
                unset($this->properties[$field]);
            }
        }

        if(isset($this->properties['id'])){
            $this->properties['id'] = (int)$this->properties['id'];
        }
        
        return parent::initialize();
    }

    function clear_post_data(&$data){
        if(is_array($data)){
            foreach($data as $r_name => &$r_val){
                $this->clear_post_data($r_val);
                continue;
            }
        }
        else{
            $data =  str_replace(array('[', ']', '%5B', '%5D'), array('&#91;', '&#93;', '&#91;','&#93;',), $data);
        }
    }


    protected function createResource(array $data){

        $user_id = $this->modx->user->id;


        if(empty($data['pagetitle'])){
            $this->addFieldError("pagetitle", "Не заполнен заголовок");
        }

        if($this->hasErrors()){
            return null;
        }

        // else
        $data = array_merge(array(
            "published"     => true,
            "searchable"    => true,
            "show_in_tree"  => false,
            "isfolder"      => 1,
            "hidemenu"      => 0,
            "createdby"     => $user_id,
            "createdon"     => time(),
            "publishedby"   => $user_id,
            "publishedon"   => time(),
            "context_key"   => "web",
            "alias"         => $data['pagetitle'],
            "class_key"     => "modDocument",
        ), $data);

        $resource = $this->modx->newObject("modResource", $data); 

        // $resource->fromArray($data);

        /*
            Проверяем на конфликт УРЛов.
            Если есть конфликт, добавляем префикс по id
        */

        if($resource->isDuplicateAlias()){
            $q = $this->modx->newQuery("modResource");
            $q->select(array(
                "max(id)",
            ));

            $resource->alias .= "-" . ($this->modx->getValue($q->prepare()) + 1);

            $resource->uri = $resource->getAliasPath($resource->alias);
        }


        return $resource;
    }

    public function failure($msg = "", $object = array()){

        // var_dump($msg);
        // var_dump($object);
        // var_dump($this->modx->error->errors);

        if(
            empty($msg)
            AND !empty($this->modx->error->errors)
        ){
            $error = current($this->modx->error->errors);
            $msg = $error['msg'];
        }

        return parent::failure($msg, $object);
    }


    public function afterSave(){


        $this->modx->cacheManager->refresh();
        $this->modx->cacheManager->clearCache();

        return parent::afterSave();
    }
    
    public function cleanup() {

        /*
            Вычищаем все данные, которые не были переданы в запросе
        */
        foreach($this->object->_fields as $name => $value){

            // Пропускаем разрешенные поля
            if(in_array($name, array(
                "id",
            ))){
                continue;
            }

            if(!array_key_exists($name, $this->properties)){
                unset($this->object->_fields[$name]);
            }
        }

        unset($this->object->_fields['new_object']);
        unset($this->object->_fields['save_object']);

        return $this->success($this->getProperty("success_msg", 'Объект успешно сохранен'), $this->object);
    }
}

return 'modWebObjectProcessor';



// require_once __DIR__ . '/../site/web/object.class.php';

// abstract class modWebObjectProcessor extends modSiteWebObjectProcessor{

//     function checkPermissions(){

//         return $this->modx->user->id && parent::checkPermissions();
//     }

//     public function initialize() {


//         $request_body = file_get_contents('php://input');

//         if($request_body AND $data = json_decode($request_body, 1)){
//             $this->setProperties($data);
//         }

//         return parent::initialize();
//     }


//     public function afterSave(){


//         $this->modx->cacheManager->refresh();
//         $this->modx->cacheManager->clearCache();

//         return parent::afterSave();
//     }


    
//     public function cleanup() {

//         $object = & $this->object;

//         foreach($object->_fields as $name => $value){

//             // if(in_array($name, array(
//             //     "new_object",
//             //     "save_object",
//             // ))){
//             //     continue;
//             // }

//             if(!array_key_exists($name, $this->properties)){
            
        
//                 unset($object->_fields[$name]);

//             }

//         }

//         return parent::cleanup();
//     }
// }

// return 'modWebObjectProcessor';

