<?php

require_once dirname(dirname(__FILE__)) . '/site/web/getdata.class.php';

class modWebGetdataProcessor extends modSiteWebGetdataProcessor{
    
    public function initialize(){
        
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
