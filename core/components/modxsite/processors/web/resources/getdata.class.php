<?php

require_once dirname(dirname(dirname(__FILE__))).'/site/web/resources/getdata.class.php';

class modWebResourcesGetdataProcessor extends modSiteWebResourcesGetdataProcessor{
    

    public function initialize()
    {

        $this->setDefaultProperties(array(
            'format' => "json",
            'context_key'       => '',
        ));

        return parent::initialize();
    }


    
    public function prepareQueryBeforeCount(xPDOQuery $c) {

        $c = parent::prepareQueryBeforeCount($c);
        
        $alias = $c->getAlias();

        $where = array();

        if($context_key = trim($this->getProperty("context_key"))){
        	$where['context_key'] = $context_key;
        }

        if($where){
        	$c->where($where);
        }
        
        return $c;
        
    }


}

return 'modWebResourcesGetdataProcessor';