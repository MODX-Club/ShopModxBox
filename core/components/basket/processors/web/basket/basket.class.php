<?php

/*
    Устаревший. Не использовать!
    Все актуальные процессоры в processors/basket/
     
*/

require_once dirname(dirname(__FILE__)).'/session.class.php';

class modWebBasketBasketProcessor_depricated extends SiteSession{
    protected $session_key = 'Basket';
    
    protected $ShopProcessorsPath = '';
    protected $BillingProcessorsPath = '';
    
    public function initialize() {
        $this->initSessionData();
        
        $this->setDefaultProperties(array(
            'quantity'  => 1,
        ));
        
        
        $this->ShopProcessorsPath = MODX_CORE_PATH . 'components/modxsite/processors/';
        $this->BillingProcessorsPath = MODX_CORE_PATH . 'components/billing/processors/';
        
        return parent::initialize();
    }
    
    public function process() {
        
        switch($this->getProperty('action', '')){
            
            case '':
                return $this->failure('Не указано действие');
                break;
            
            case 'add_product':
                return $this->OnAdd();
                break;
            
            case 'getdata':
                return $this->OnGetData();
                break;
            
            case 'recalculate':
                return $this->OnRecalculate();
                break;
                
            case 'empty_basket':
                return $this->OnFlush();
                break;
                
            case 'remove_product':
                return $this->removeProduct();
                break;
            
            default: return $this->failure('Действие не допустимо');
        }
    }
    
    // Создаем ключ товара
    protected function createProductKey($params){
        $key = md5(json_encode($params));
        return $key;
    }
    
    /*
     * Flush
     */
    protected function OnFlush(){
        
        // Проверяем, есть ли активный заказ
        if($order_id = $this->getOrderID()){
            
            // Если есть, то пытаемся отменить заказ
            if(!$response = $this->modx->runProcessor('mgr/orders/status/update', array(
                'order_id'      => $order_id,
                'new_status'    => 7,
            ), array(
                'processors_path' => $this->BillingProcessorsPath,    
            ))){
                $error = "Ошибка выполнения запроса";
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Basket] - OnFlush - {$error}");
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), 1));
                return $this->failure($error);
            }
            
            if($response->isError()){
                return $response->getResponse();
            }
        }
        
        // Если все ОК, то сбрасываем сессию
        $this->flush();
        if($this->getSessionData()){
            return $this->failure('Не удалось очистить корзину');
        }
        
        return $this->success("Корзина успешно очищена");
    }
    
    /*
        Удаляем товар.
        Для экономии калорий просто перенаправляем запрос в метод $this->OnRecalculate(),
        так как пока смысл удаления и обнуления ничем не отличается, только 
        сообщение успешное выводим другое.
    */
    protected function removeProduct(){
        
        if(!$product_key = $this->getProperty('product_key')){
            return $this->failure('Не был получен ключ товара');
        }
        
        $this->setProperty('quantity', array(
            $product_key => 0,   
        ));
        
        $result = $this->OnRecalculate();
        
        if(!$result['success']){
            return $result;
        }
        
        // else
        return $this->success('Товар успешно удален из корзины', array(
            "key"  => $product_key,
        ));
    }


    /*
     * Add
     */
    protected function OnAdd(){
        $order_id = $this->getOrderID();
        $this->setProperty('order_id', $order_id);
        
        /*print $order_id;
        exit;*/
        
        // Ищем товар по параметрам
        if(!$product_id = $this->getProperty('product_id')){
            return $this->failure('Не был получен ID товара');
        }
         
        
        // Создаем ключ товара
        if(!$key = $this->createProductKey(array(
            'product_id' => $product_id,
        ))){
            return $this->failure('Не был получен ключ товара');
        }
        
        
        // Смотрим товар в сессии, или получаем новый.
        // class ShopmodxProduct
        if(!$product = $this->getSessionData($key)){
            $where = array(
                "Product.id" =>  $product_id,   
            ); 
            
            // Пытаемся получить один товар
            if(!$response = $this->modx->runProcessor('web/catalog/products/getdata', array(
                'limit'     => 1,
                'where'     => $where,
                'getPage'   => false,
                'page'      => 0,
            ), array(
                'processors_path' => $this->ShopProcessorsPath,
            ))){
                return $this->failure('Не удалось выполнить запрос');
            }
            
            if($response->isError()){
                return $response->getResponse();
            }
            
            if(!$object = $response->getObject() OR !$product = current($object)){
                return $this->failure('Не был получен товар');
            }
            
            
            $product = array(
                'quantity'      => 0,
                'product_id'    => $product['product_id'],
                'resource_id'   => $product['object_id'],
                'price'         => $product['sm_price'],
                'key'           => $key,
            );
            // print_r($product);
        }
        
        if(!$response = $this->modx->runProcessor('web/orders/products/add', $this->getProperties(), array(
            'processors_path' => $this->BillingProcessorsPath,    
        ))){
            $error = "Ошибка выполнения запроса";
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Basket] - Add - {$error}");
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), 1));
            return $this->failure($error);
        }
        
        
        if($response->isError()){
            return $response->getResponse();
        }
        
        if(!$object = $response->getObject()){
            $error = "Не был получен объект заказа";
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Basket] - Add - {$error}");
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), 1));
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($response->getResponse(), 1));
            return $this->failure($error);
        }
        
        // Если еще не был установлен ID заказа, устанавливаем
        if(!$order_id){
            $this->setOrderID($object['order_id']);
        }
        
        // Устанавливаем актуальное кол-во товаров определенного размера
        $product['quantity'] = $object['quantity'];
        
        // Сохраняем данные в сессию
        $this->setSessionData($key, $product);
        
        // print_r($_SESSION['Basket']);
        
        return $this->success('Товар успешно добавлен в корзину');
    }
    
    /*
     * OnGetData
     */
    protected function OnGetData(){
        if(!$order_id = $this->getOrderID()){
            return $this->failure('ID заказа отсутствует');
        }
        
        $data = array(
            'order_id'  => $order_id,
            'total'     => 0,
            'sum'       => 0,
            'products'  => array(),
        );
        // Получаем всю информацию из сессии
        if($products = $this->getProducts()){
            
            $IDs = array();
            /*$prices = array();
            foreach($products as $key => $product){
                $products[$key]['key'] = $key;
                $data['total'] += $product['quantity'];
                // Набиваем массив товаров, чтобы потом получить по ним дополнительную информацию
                $IDs[] = $product['product_id'];
            }
            // Делаем выборку всех товаров
            $c = $this->modx->newQuery('modResource', array(
                'id:IN' => array_unique($IDs),
            ));
            $c->innerJoin('ShopmodxProduct', 'Product');
            $c->select(array(
                'modResource.id',
                'sm_price as price',
            ));
            if($c->prepare() && $c->stmt->execute() && $rows = $c->stmt->fetchAll(PDO::FETCH_ASSOC)){
                foreach($rows as $row){
                    // 
                    $prices[$row['id']] = $row['price'];
                }
            }*/
            
            // print_r($prices);
            
            // Фиксируем общее количество позиций
            $data['positions'] = count($products);
            
            // Подсчитываем общую сумму
            foreach($products as $product){
                $data['sum'] += ($product['price'] * $product['quantity']);
                $data['total'] += $product['quantity'];
                // print_r($product);
                //print_r($IDs);
            }
            
            $data['products'] = $products;
        }     
        return $this->success('', $data);
    }
    
    /*
     * OnRecalculate
     * Пересчитываем корзину
     */
    protected function OnRecalculate(){
        
        if(!$data = $this->getProperty('quantity') OR !is_array($data)){
            return $this->failure('Не были получены данные');
        }
        
        // Получаем ID заказа
        if(!$order_id = $this->getOrderID()){
            return $this->failure('Не был получен ID заказа');
        }
        
        foreach($data as $key => $quantity){
            $q = (int)$quantity;
            
            // Если получен товар и количество отличается,
            // то обновляем данные
            if($product = $this->getSessionData($key) AND $product['quantity'] != $q){
                
                // Так как у всех процессоров обработчик ошибок единый, и мы 
                // работаем в цикле и с вложенными вызовами, на всякий случай
                // сбрасываем ошибки
                $this->modx->error->reset();
                
                // Пробуем обновить данные в базе данных
                if(!$response = $this->modx->runProcessor('mgr/orders/products/update_quantity', array(
                    'order_id'      => $order_id,
                    'product_id'    => $product['product_id'],
                    'quantity'      => $q,
                ), array(
                    'processors_path' => $this->BillingProcessorsPath,    
                ))){
                    $error = "Ошибка выполнения запроса";
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Basket] - OnRecalculate - {$error}");
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), 1));
                }
                
                // Если процессор выполнился успешно и был получен объект,
                // на всякий случай используем количество, которое указано в объекте
                else if(!$response->isError() AND $object = $response->getObject()){
                    $q = $object['quantity'];
                }
                
                // Если число положительное, пересчитываем корзину
                if($q > 0){
                    $product['quantity'] = $q;
                    $this->setSessionData($key, $product); 
                }
                // Иначе удаляем
                else{
                    $this->remove($key);
                }
            }
        }
        return $this->success('Корзина успешно пересчитана');
    }
    
    // Устанавливаем ID заказа 
    protected function setOrderID($order_id){
        return $this->setSessionData('order_id', $order_id);
    }
    
    // Получаем ID заказа 
    protected function getOrderID(){
        return $this->getSessionData('order_id', null);
    }
    
    protected function getProducts(){
        $products = array();
        
        foreach($this->getSessionData() as $key => $data){
            if(is_array($data) && !empty($data['product_id'])){
                $products[$key] = $data;
            }
        }
        
        return $products;
    }
}


class modWebBasketBasketProcessor extends modWebBasketBasketProcessor_depricated{
    
    
    public function initialize(){
        
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[- Basket -] Class '". __CLASS__ ."' is depricated. ".__FILE__.":".__LINE__);
        
        return parent::initialize();
    }
    
    public function process() {
         
        
        switch($this->getProperty('action', '')){
             
                
            case 'empty_basket':
                return $this->OnFlush();
                break; 
            
            default: return $this->failure('Действие не допустимо');
        }
    }
    
     
    protected function OnFlush(){
         
        if(!$response = $this->modx->runProcessor('basket/web/orders/empty', $this->getProperties(), array(
            'processors_path' => dirname(dirname(dirname(__FILE__))) . '/',    
        ))){
            $error = "Ошибка выполнения запроса";
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Basket] - OnFlush - {$error}");
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), 1));
            return $this->failure($error);
        }
        
        return $response->getResponse();
    } 
    
}

return 'modWebBasketBasketProcessor';



