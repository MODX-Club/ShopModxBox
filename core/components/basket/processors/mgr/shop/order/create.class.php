<?php

/*
Создаем новый заказ с указанием товаров
*/

class modMgrShopOrderCreateProcessor extends modObjectCreateProcessor{
    public $classKey = 'ShopOrder';
    
    
    public function initialize(){
        $this->setDefaultProperties(array(
            'status'    => $this->modx->getOption('shop.order_default_status', null, 1), 
            'products'  => array(),     // Массив данных товаров
            'created_by'  => $this->modx->user->id,
            'ip' => $this->getUserIp(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'order_address' => '',
            'ShopProcessorsPath'    => MODX_CORE_PATH . 'components/modxsite/processors/',
        ));
        return parent::initialize();
    }
    
    public function beforeSet(){
        if(!$products = (array)$this->getProperty('products')){
            return 'Не были указаны товары';
        }
        
        // Собираем все ID-шники товаров
        foreach($products as $product){
            $IDs[] = $product['product_id'];
        }
        
        if(!$response = $this->modx->runProcessor('web/catalog/products/getdata', array(
            'limit' => 0,
            'where' => array(
                'id:IN' => $IDs    
            ),    
        ), array(
            'processors_path'  => $this->getProperty('ShopProcessorsPath')
        ))){
            $error = 'Не удалось выполнить запрос';
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $error);
            return $error;
        }
        
        
        if($response->isError()){
            if(!$error = $reponse->getMessage()){
                $error = "Ошибка выполнения запроса";
            }
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $error);
            return $error;
        }
        
        if(!$objects = $response->getObject()){
            $error = 'Не были получены товары';
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $error);
            return $error;
        }
        
        foreach($products as $product){
            $price = $objects[$product['product_id']]['sm_price'];
            $ShopOrderProduct = $this->modx->newObject('ShopOrderProduct');
            $ShopOrderProduct->set('product_id', $product['product_id']);
            $ShopOrderProduct->set('quantity', $product['quantity']);
            $ShopOrderProduct->set('price', $price);
            $this->object->addMany($ShopOrderProduct);
        }
        
        return true;
    }
    
    protected function getUserIp(){
        if (!empty($_SERVER["REMOTE_ADDR"])){ 
            return $_SERVER["REMOTE_ADDR"]; 
        } else if ( !empty($_SERVER["HTTP_X_FORWARDED_FOR"])){ 
            return $_SERVER["HTTP_X_FORWARDED_FOR"]; 
        } else if ( !empty($_SERVER["HTTP_CLIENT_IP"])){ 
            return $_SERVER["HTTP_CLIENT_IP"]; 
        }
        return '';
    }
}

return 'modMgrShopOrderCreateProcessor';