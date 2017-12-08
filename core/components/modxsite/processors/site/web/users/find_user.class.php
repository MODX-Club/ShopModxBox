<?php

/*
 * Поиск профиля по юзернейму (для формы авторизации)
 * */

require_once __DIR__ . '/getdata.class.php';

class modSiteWebUsersFinduserProcessor extends modSiteWebUsersGetdataProcessor{


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

return 'modSiteWebUsersFinduserProcessor';

