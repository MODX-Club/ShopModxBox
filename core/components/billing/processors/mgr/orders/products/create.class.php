<?php

/*
    Создаем запись Заказ-Товар
*/

class modMgrOrdersProductsCreateProcessor extends modObjectCreateProcessor{
    
    public $classKey = 'OrderProduct';
    
    public function initialize(){
        
        if(!(int)$this->getProperty('product_id')){
            return 'Не был указан ID товара';
        } 
        
        return parent::initialize();
    }
    
    public function beforeSet(){
        
        // Проверяем наличие товара
        if(!$product = $this->modx->getObject('ShopmodxProduct', (int)$this->getProperty('product_id'))){
            $error = 'Не был получен товар';
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Billing] - {$error}");
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), true));
            return $error;
        }
        
        // Если указан ID заказа, то пытаемся получить данный заказ.
        if($order_id = $this->getProperty('order_id')){
            
            // Если он не был получен, то возвращаем ошибку.
            if(!$this->modx->getObject('Order', $order_id)){
                $error = "Не был получен заказ";
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Billing] - {$error}");
                return $error;
            }
        }
        else{
            if(!$response = $this->modx->runProcessor('mgr/orders/create', array(
                
            ),array(
                "processors_path"  => dirname(dirname(dirname(dirname(__FILE__)))). '/',
            ))){
                $error = "Не был создан новый заказ";
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Billing] - {$error}");
                return $error;
            }
            
            // else
            if($response->isError() OR !$object = $response->getObject() OR !$order_id = $object['id']){
                $error = "Не был создан новый заказ";
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Billing] - {$error}");
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($response->getResponse(), 1));
                return $error;
            }
            
            // else
            // Устанавливаем значение ID заказа
            $this->setProperty('order_id', $order_id);
        }
        
        // Устанавливаем значение цены и валюты
        // В дальнейшем в этом месте можно будет вклиниться с переопределением цен
        $this->setPrices($product);
        
        return parent::beforeSet();
    }

    //переопределив эту функцию, можно изменить алгоритм определения цены (например, для товаров с опциями)
    protected function setPrices(ShopmodxProduct & $product){
        
        $data = array(
            'price' => $product->get('sm_price'),    
            'currency_id' => $product->get('sm_currency'),    
        );
        
        // Получаем данные товара с учетом курсов валют
        if(
            $response = $this->modx->runProcessor('web/catalog/products/getdata',
            array(
                "where" => array(
                    "id"    => $product->get('resource_id'),    
                ),
                "current"       => 1,
                "showhidden"    => 1,
                "showunpublished"    => 1,
            ), array(
            'processors_path' => MODX_CORE_PATH . 'components/modxsite/processors/',
            ))
            AND !$response->isError()
            AND $object = $response->getObject()
        ){
            $data = array(
                'price' => $object['sm_price'],    
                'currency_id' => $object['sm_currency'],    
            );
        }
        $this->modx->error->reset(); 
        
        $this->object->fromArray($data);
        
        return true;
    }
    
    public function cleanup() {
        $response = parent::cleanup();
        $response['message'] = $this->getProperty('success_message', 'Товар успешно добавлен');
        return $response;
    }    
}

return 'modMgrOrdersProductsCreateProcessor';
