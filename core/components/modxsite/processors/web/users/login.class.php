<?php

class modWebUsersLoginProcessor extends modProcessor{
     
     
    public function process(){
        
        $params = $this->getRequestParams(); 
        
        $response = $this->modx->runProcessor('security/login', $params);
        return $response->getResponse();
        
    }
    
    
    protected function getRequestParams(){
        
        $params = array(
            "username"      => $this->getProperty('username'), 
            "password"      => $this->getProperty('password'), 
            "rememberme"    => $this->getProperty('rememberme'), 
        );
        
        return $params;
    }
    
}

return 'modWebUsersLoginProcessor';


