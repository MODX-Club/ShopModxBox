<?php

/*
Процессор, определяющий по запрошенному действию какой процессор выполнять
*/


class modWebPublicActionProcessor extends modProcessor{
    
    protected static $actualClassName;
    
    public static function getInstance(modX &$modx,$className,$properties = array()) {

        // Здесь мы имеем возможность переопределить реальный класс процессора
        if(!empty($properties['pub_action']) && !self::$actualClassName){
             
            switch($properties['pub_action']){
                
                case 'currencies/update_courses':
                    require_once dirname(dirname(__FILE__)) . '/currencies/update_courses.class.php';
                    self::$actualClassName = "modWebCurrenciesUpdatecoursesProcessor";
                    break;
                
                case 'login':
                    require_once dirname(dirname(__FILE__)) . '/users/login.class.php';
                    self::$actualClassName = "modWebUsersLoginProcessor";
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

return 'modWebPublicActionProcessor';