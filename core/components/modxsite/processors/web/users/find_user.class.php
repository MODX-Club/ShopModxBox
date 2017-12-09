<?php

/*
 * Поиск профиля по юзернейму (для формы авторизации)
 * */

require_once __DIR__ . '/getdata.class.php';

class modWebUsersFinduserProcessor extends modWebUsersGetdataProcessor{

    public function checkPermissions(){

    	// TODO
        // return $this->modx->user->id && parent::checkPermissions();

        return true;
    }  

    public function initialize(){

        if(!$this->getProperty('query')){
            return "Не указан логин или емейл";
        }

        $this->setDefaultProperties(array(
            "current" => true,
        ));

        return parent::initialize();
    }
}

return 'modWebUsersFinduserProcessor';

