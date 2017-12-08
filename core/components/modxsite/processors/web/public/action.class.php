<?php

/*
Процессор, определяющий по запрошенному действию какой процессор выполнять
*/


class modWebPublicActionProcessor extends modProcessor{
    
    protected static $actualClassName;
    
    public static function getInstance(modX &$modx,$className,$properties = array()) {

        
        foreach($properties as $field => & $value){

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
                unset($properties[$field]);
            }
            else if($v === "undefined"){
                unset($properties[$field]);
            }
        }
        

        $request_body = file_get_contents('php://input');

        if($request_body AND $data = json_decode($request_body, 1)){
            $properties = array_merge($properties, $data);
        }


        $modx->log(1, print_r($properties, 1), "FILE");

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


                case 'getpage':
                    require_once dirname(dirname(__FILE__)) . '/import/getpage.class.php';
                    self::$actualClassName = "modWebImportGetpageProcessor";
                    break;


                case 'update_product':
                    require_once dirname(dirname(__FILE__)) . '/catalog/products/update.class.php';
                    self::$actualClassName = "modWebCatalogProductsUpdateProcessor";
                    break;

                case 'resources/getdata':
                    require_once dirname(dirname(__FILE__)) . '/resources/getdata.class.php';
                    self::$actualClassName = "modWebResourcesGetdataProcessor";
                    break;

                case 'catalog/categories/getdata':
                    require_once dirname(dirname(__FILE__)) . '/catalog/category/getdata.class.php';
                    self::$actualClassName = "modWebCatalogCategoryGetdataProcessor";
                    break;

                case 'catalog/products/getdata':
                    require_once dirname(dirname(__FILE__)) . '/catalog/products/getdata.class.php';
                    self::$actualClassName = "modWebCatalogProductsGetdataProcessor";
                    break;

                case 'form/callme':
                    require_once dirname(dirname(__FILE__)) . '/forms/callme.class.php';
                    self::$actualClassName = "modWebFormsCallmeProcessor";
                    break;
                    
                case 'orders/getdata':
                    require_once dirname(dirname(__FILE__)) . '/orders/getdata.class.php';                    
                    self::$actualClassName =  'modWebOrdersGetdataProcessor';
                    break;
                    
                case 'orders/object':
                    require_once dirname(dirname(__FILE__)) . '/orders/object.class.php';                    
                    self::$actualClassName =  'modWebOrdersObjectProcessor';
                    break;

                case 'users/getdata':
                    require __DIR__ . '/../users/getdata.class.php';
                    self::$actualClassName = 'modWebUsersGetdataProcessor';
                    break;

                case 'users/own_profile/getdata':
                    require __DIR__ . '/../users/own_profile/getdata.class.php';
                    self::$actualClassName = 'modWebUsersOwnprofileGetdataProcessor';
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