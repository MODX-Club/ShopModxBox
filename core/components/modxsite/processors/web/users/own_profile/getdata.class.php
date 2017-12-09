<?php


require_once __DIR__ . '/../getdata.class.php';

class modWebUsersOwnprofileGetdataProcessor extends modWebUsersGetdataProcessor{

    
    public function initialize(){
 
        
        $this->setProperties(array(
            "showinactive" => true,
            "showblocked" => true,
            "ownProfile"   => true,
            "cache"             => false,
        ));

        return parent::initialize();
    }

}

return 'modWebUsersOwnprofileGetdataProcessor';