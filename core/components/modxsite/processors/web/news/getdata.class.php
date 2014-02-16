<?php
/*
    Новости
*/

require_once dirname(dirname(__FILE__)) . '/resources/getdata.class.php';

class modWebNewsGetdataProcessor extends modWebResourcesGetdataProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "sort"      => "{$this->classKey}.publishedon",
            "dir"       => "DESC",
            "parent"    => 5,
            "getPage"  => true,
            "limit"    => 3,
            "cache_prefix" => "news/",
            "summary"  => true,
            "truncLen" => 500,
        ));
         
        
        return parent::initialize();
    }
}

return "modWebNewsGetdataProcessor";