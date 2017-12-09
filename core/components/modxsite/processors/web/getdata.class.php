<?php

require_once dirname(dirname(__FILE__)) . '/site/web/getdata.class.php';

class modWebGetdataProcessor extends modSiteWebGetdataProcessor{
    
    public function initialize(){

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
        
        $this->setDefaultProperties(array(
    		"format" => "json",
    		"count"	=> 0,
            "cache"             => true,
            'cache_prefix'      => $this->modx->context->key . "/" . get_class($this) . '/getdata/' . $this->modx->user->id. "/",  
        ));
        
    	
        // print_r($this->properties);
    	// $this->modx->log(1, print_r($this->properties, 1), "FILE");

        return parent::initialize();
    }
        
}

return 'modWebGetdataProcessor';
