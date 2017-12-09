<?php

/*
    Сбрасываем пароль
*/



class modWebSocietyUsersPasswordResetProcessor extends modObjectUpdateProcessor{
    
    public $classKey = 'modUser';
    protected $user;
    
    public function initialize(){
        
        if($username = base64_decode(urldecode($this->getProperty('lu')))){
            $this->setProperty('username', $username);
        }
        if($password = base64_decode(urldecode($this->getProperty('lp')))){
            $this->setProperty('password', $password);
        }
         
        if(!$this->object = $this->getUser()){
            return "Не был получен пользователь";
        }
        
        // else
        $this->user = & $this->object;
          
        return true;
    }
    
    
    public function getUser() {
        $user = null;
        /* get user from query params */
        if($username = $this->getProperty('username')){
            /* validate we have correct user */
            $user = $this->modx->getObject('modUser',array('username' => $username));
        }
        return $user;
    }
    
    
    
    /**
     * Validate password to prevent middleman attacks
     * @return boolean
     */
    public function verifyIdentity() {
        $cacheKey = 'login/resetpassword/'. $this->getProperty('username');
         
        $cachePass = $this->modx->cacheManager->get($cacheKey);
        $verified = $cachePass == $this->getProperty('password');
        $cachePass = $this->modx->cacheManager->get($cacheKey);
        return $verified;
    }

    /**
     * Erase the cached user data
     * @return void
     */
    public function eraseCache() {
        $cacheKey = 'login/resetpassword/'. $this->getProperty('username');
        $this->modx->cacheManager->delete($cacheKey);
    }
    
    
    public function beforeSave(){
        if(!$this->verifyIdentity()){
            return "Неверный ключ или пароль был изменен ранее";
        }
        
        // else
        $this->eraseCache();
        $this->object->Profile->set('password', $this->getProperty('password'));
        
        return parent::beforeSave();
    }
    
}


return 'modWebSocietyUsersPasswordResetProcessor';
