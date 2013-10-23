<?php

/*
    Процессор, определяющий по запрошенному действию какой процессор выполнять
*/

class modBasketWebPublicActionProcessor extends modProcessor{
    
    protected static $actualClassName;
    
    public static function getInstance(modX &$modx,$className,$properties = array()) {
        
        // Здесь мы имеем возможность переопределить реальный класс процессора
        if(!empty($properties['basket_action']) && !self::$actualClassName){
             
            switch($properties['basket_action']){
                
                case 'products_add': 
                case 'products/add': 
                    require dirname(dirname(__FILE__)) . '/orders/products/add.class.php';                    
                    self::$actualClassName =  'modBasketWebOrdersProductsAddProcessor';
                    break;
                
                case 'products_getdata':
                    require dirname(dirname(__FILE__)) . '/orders/products/getdata.class.php';                    
                    self::$actualClassName =  'modBasketWebOrdersProductsGetdataProcessor';
                    break;
                
                // Это чисто для Ajax-а. Состояние корзины
                case 'getdata':
                    require dirname(dirname(__FILE__)) . '/ajax/orders/getdata.class.php';                    
                    self::$actualClassName =  'modBasketWebAjaxOrdersGetdataProcessor';
                    break;
                
                case 'products_remove':
                case 'products/remove':
                    require dirname(dirname(__FILE__)) . '/orders/products/remove.class.php';                    
                    self::$actualClassName =  'modBasketWebOrdersProductsRemoveProcessor';
                    break;
                    
                case 'recalculate':
                    require dirname(dirname(__FILE__)) . '/orders/recalculate.class.php';                    
                    self::$actualClassName =  'modBasketWebOrdersRecalculateProcessor';
                    break;
                    
                case 'empty_basket':
                    require dirname(dirname(__FILE__)) . '/orders/empty.class.php';                    
                    self::$actualClassName =  'modBasketWebOrdersEmptyProcessor';
                    break;
                    
                
                default:;
            }
            
            
            /*
                Если переопределяете в дочернем процессоре,
                не забудьте скопировать и это
            */
            /*if($actualClassName){
                $className = $actualClassName;
                unset($properties['basket_action']);
            }*/
        }
        
        if(self::$actualClassName){
            $className = self::$actualClassName;
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

return 'modBasketWebPublicActionProcessor';
