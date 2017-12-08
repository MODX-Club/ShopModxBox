<?php

/*
Процессор, определяющий по запрошенному действию какой процессор выполнять
*/


class modSiteWebPublicActionProcessor extends modProcessor{
    
    protected static $actualClassName;
    
    public static function getInstance(modX &$modx,$className,$properties = array()) {

        // Здесь мы имеем возможность переопределить реальный класс процессора
        if(!empty($properties['pub_action']) && !self::$actualClassName){
             
            switch($properties['pub_action']){
                

                case 'login':
                    require_once __DIR__ . '/../users/login.class.php';
                    self::$actualClassName = "modSiteWebUsersLoginProcessor";
                    break;

                case 'logout':
                    require_once __DIR__ . '/../users/logout.class.php';
                    self::$actualClassName = "modSiteWebUsersLogoutProcessor";
                    break;

                case 'users/find_user':
                    require __DIR__ . '/../users/find_user.class.php';
                    self::$actualClassName = 'modSiteWebUsersFinduserProcessor';
                    break;


                case 'users/get_own_data':
                    require __DIR__ . '/../users/own_profile/getdata.class.php';
                    self::$actualClassName = 'modSiteWebUsersOwnprofileGetdataProcessor';
                    break;

                default:;
            } 
        }
        
        if(self::$actualClassName){
            $className = self::$actualClassName;
            return $className::getInstance($modx,$className,$properties);
        }

        return parent::getInstance($modx,$className,$properties);
    }
    
    
    public function process(){
        
        $error = 'Действие не существует или не может быть выполнено';
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, __CLASS__ . " - {$error}");
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), true));
        return $this->failure($error);
    }
    
}

return 'modSiteWebPublicActionProcessor';