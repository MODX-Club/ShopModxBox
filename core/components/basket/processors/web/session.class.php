<?php

abstract class SiteSession_depricated extends modProcessor{
    protected $session_key = 'SiteSession';
    public function initialize() {
        $this->initSessionData();
        return parent::initialize();
    }
    
    protected function initSessionData(){
        if(!isset($_SESSION[$this->session_key])){
            $_SESSION[$this->session_key] = array();
        }
        return $_SESSION[$this->session_key];
    }
    
    protected function updateSessionData(){
        $data = array_merge((array)$this->getSessionData(), $this->getProperties());
        $_SESSION[$this->session_key] = $data;
        $this->setProperties($data);
        return true;
    }
    
    protected function setSessionData($key, $value){
        $_SESSION[$this->session_key][$key] = $value;
    }
        
    protected function getSessionData($key = null, $default = null){
        $value = null;
        // If key not set, return all data
        if($key === null){
            $value = $_SESSION[$this->session_key];
        }
        else if(isset($_SESSION[$this->session_key][$key])){
            $value = ($_SESSION[$this->session_key][$key] ? $_SESSION[$this->session_key][$key] : $default);
        }
        else{
            $value = $default;
        }
        return $value;
    }
    
    
    protected function remove($key){
        unset($_SESSION[$this->session_key][$key]);
        return;
    }
    
    protected function flush(){
        $_SESSION[$this->session_key] = array();
    }
}

abstract class SiteSession extends SiteSession_depricated{
    
    
    public function initialize(){
        
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[- Basket -] Class '". __CLASS__ ."' is depricated. ".__FILE__.":".__LINE__);
        
        return parent::initialize();
    }
    
}


return 'SiteSession';