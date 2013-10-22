<?php

require_once dirname(dirname(__FILE__)).'/session.class.php';

class modWebBasketBasketbackProcessor extends SiteSession{
    protected $session_key = 'Basket';
    public function initialize() {
        $this->initSessionData();
        
        $this->setDefaultProperties(array(
            'quantity'  => 1,   
        ));
        
        $this->setProperties(array(
            'ShopProcessorsPath'    => MODX_CORE_PATH . 'components/modxsite/processors/', 
        ));
        
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
        $this->flush();
        if($this->getSessionData()){
            return $this->failure('Не удалось очистить корзину');
        }
        return $this->success("Корзина успешно очищена");
    }
    
    protected function removeProduct(){
        $product_key = $this->getProperty('product_key');
        $this->remove($product_key);
        return $this->success('Товар успешно удален из корзины');
    }
    
    protected function remove($product_key){
        unset($_SESSION[$this->session_key][$product_key]);
        return;
    }


    /*
     * Add
     */
    protected function OnAdd(){
        // Ищем товар по параметрам
        if(!$product_id = $this->getProperty('product_id')){
            return $this->failure('Не был получен ID товара');
        }
        
        // Набираем параметры поиска
        $conditions = array();
        
        // Создаем ключ товара
        if(!$key = $this->createProductKey(array(
            'product_id' => $product_id,
        ))){
            return $this->failure('Не был получен ключ товара');
        }
        
        
        // Смотрим товар в сессии, или получаем новый.
        if(!$product = $this->getSessionData($key)){
            $where = array(
                "id" =>  $product_id,   
            );
            
            
            
            // Пытаемся получить один товар
            if(!$response = $this->modx->runProcessor('web/catalog/products/getdata', array(
                'limit'     => 1,
                'where'     => $where,
                'conditions' => $conditions,
                'getPage'   => false,
                'page'      => 0,
            ), array(
                'processors_path' => $this->getProperty('ShopProcessorsPath'),
            ))){
                return $this->failure('Не удалось выполнить запрос');
            }
            
            
            if($response->isError()){
                return $response->getResponse();
            }
            
            if(!$object = $response->getObject() OR !$product = current($object)){
                return $this->failure('Не был получен товар 2');
            }
            
            $product = array(
                'quantity'      => 0,
                'product_id'    => $product['object_id'],
            );
        }
        
        
        // Накручиваем кол-во товаров определенного размера
        $product['quantity'] += $this->getProperty('quantity', 1);
        
        // Сохраняем данные в сессию
        $this->setSessionData($key, $product);
        
        return $this->success('Товар успешно добавлен в корзину');
    }
    
    /*
     * OnGetData
     */
    protected function OnGetData(){
        $data = array(
            'total' => 0,
            'sum'   => 0,
            'products'  => array(),
        );
        // Получаем всю информацию из сессии
        if($products = $this->getSessionData()){
            
            $IDs = array();
            $prices = array();
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
            }
            
            // Подсчитываем общую сумму
            foreach($products as $product){
                $data['sum'] += ($prices[$product['product_id']] * $product['quantity']);
                //print_r($product);
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
        if(empty($_POST['quantity']) OR !$data = $_POST['quantity'] OR !is_array($data)){
            return $this->failure('Не были получены данные');
        }
        foreach($data as $key => $quantity){
            $q = (int)$quantity;
            
            // Если число положительное, пересчитываем корзину
            if($q > 0){
                if($product = $this->getSessionData($key)){
                    $product['quantity'] = $q;
                    $this->setSessionData($key, $product);
                }
            }
            // Иначе удаляем
            else{
                $this->remove($key);
            }
        }
        return $this->success('Корзина успешно пересчитана');
    }
}

return 'modWebBasketBasketbackProcessor';