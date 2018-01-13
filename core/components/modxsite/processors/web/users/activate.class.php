<?php


class modWebUsersActivateProcessor extends modObjectUpdateProcessor{
    
    public $classKey = 'modUser';
    
    
    public function initialize(){

        $id = '';

        if($user_id = (int)$this->getProperty('user_id')){
            $id = $user_id;
        }
        else if($username = trim($this->getProperty('username'))){
            $user = $this->modx->getObject("modUser", array(
                "username"  => $username,
            ));

            if($user){
                $id = $user->id;
            }

        }
        // else

        
        if(!$id){
            return "Не указан ID пользователя";
        }

        $this->setProperty('id', $id);
        
        if(!$key = $this->getProperty('key')){
            return "Не указан ключ";
        }
        
        $this->setProperties(array(
            "active"    => 1,
            "createdon" => time(),
            "auto_auth" => true,   // Автоматическая авторизация пользователя
        ));
        
        $this->setDefaultProperties(array(
        ));
        
        return parent::initialize();
    }
    
    
    public function beforeSet(){
        
        $user = & $this->object;
        $profile = & $user->Profile;
        
        // Проверяем ключ
        if($this->getProperty('key') != md5($this->modx->site_id . $user->id . $profile->email)){
            return "Неверная подпись";
        }
        
        // Проверяем активацию
        if($user->active){
            return "Пользователь был активирован ранее";
        }
        
        // Проверяем блокировку
        if($profile->blocked || ($profile->blockeduntil > time())){
            return "Пользователь заблокирован";
        }
        
        return parent::beforeSet();
    }
    
    
    public function cleanup(){ 
        // Автоматическая авторизация прописана в action-процессоре
        if($this->getProperty('auto_auth') && !$this->modx->user->id){
            $this->modx->user = & $this->object;
            $this->modx->user->addSessionContext($this->modx->context->key); 
        }

        $this->modx->cacheManager->refresh();
        $this->modx->cacheManager->clearCache();
        
        return $this->success('Пользователь успешно активирован', array(
            "id"    => $this->object->id,
        ));
    }
}


return 'modWebUsersActivateProcessor';

