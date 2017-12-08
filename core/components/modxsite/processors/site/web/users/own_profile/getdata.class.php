<?php

require_once __DIR__ . '/../getdata.class.php';

class modSiteWebUsersOwnprofileGetdataProcessor extends modSiteWebUsersGetdataProcessor{


    public function checkPermissions(){

        return $this->modx->user->id && parent::checkPermissions();
    }

    public function initialize(){

        $this->setDefaultProperties(array(
            "current"   => true,
        ));

        $this->unsetProperty("id");

        return parent::initialize();
    }

    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);

        $alias = $c->getAlias();

        $c->where(array(
            "id"    => $this->modx->user->id,
        ));

        return $c;
    }

    public function outputArray(array $array, $count = false){

        $result = parent::outputArray($array, $count);

        $policies = array();

        if($result['success']){

            $attrs = $this->modx->user->getAttributes(array(),'', true);
            if(!empty($attrs['modAccessContext']['web'])){
                foreach($attrs['modAccessContext']['web'] as $attr){
                    foreach($attr['policy'] as $policy => $value){
                        if(empty($policies[$policy])){
                            $policies[$policy] = $value;
                        }
                    }
                }
            }
        }

        $result['object']['policies'] = $policies;

        return $result;
    }

}

return 'modSiteWebUsersOwnprofileGetdataProcessor';
