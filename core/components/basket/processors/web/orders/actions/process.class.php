<?php

/*
    Обработчик действий над заказами.
    В зависимости от того или иного действия возвращает какой-либо дочерний класс
*/

class modWebOrdersActionsProcessProcessor extends modProcessor{
    
    public static function getInstance(modX &$modx,$className,$properties = array()) {
        
        $modx->log(1, print_r($properties, 1));
        
        switch($properties['action']){
            case 'pay':
                $className = 'modWebOrdersActionsProcessProcessor_ActionPay';
                break;
                
            default:;
        }
        
        /** @var modProcessor $processor */
        $processor = new $className($modx,$properties);
        return $processor;
    }
    
    public function process(){
        return $this->failure('Действие не допустимо');
    }
    
}


/*
    Базовый процессор-обработчик с проверкой доступа к заказу
*/
abstract class modWebOrdersActionsProcessProcessor_Action extends modWebOrdersActionsProcessProcessor{
    
    protected $order = null;
    
    public function initialize(){
        //Проверяем пользователя
        if(!$user_id = $this->modx->user->id){
            return "Не был получен ID пользователя";
        }
        
        // Проверяем наличие ID заказа
        if(!$order_id = (int)$this->getProperty('order_id')){
            return "Не был получен ID заказа";
        }
        
        // Получаем заказ
        if(!$this->order = $this->modx->getObject('Order', $order_id)){
            return "Не был получен объект заказа";
        }
        
        // Проверяем доступ к заявке
        
        if($user_id != $this->order->get('contractor') AND $user_id != $this->order->get('createdby')){
            $error = "Нет доступа к заказу";
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[Basket - ".__CLASS__. "] {$error}");
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), true));
            return $error;
        }
        
        return parent::initialize();
    }
    
}


/*
    Оплата заказа
*/
class modWebOrdersActionsProcessProcessor_ActionPay extends modWebOrdersActionsProcessProcessor_Action{
    
    public function process(){
        
        // Редиректим на страницу оплаты
        $url = $this->modx->makeUrl(136);
        $params = array(
            'order_id' => (int)$this->getProperty('order_id'), 
        );
        $url .= "?" . http_build_query($params);
         
        return $this->modx->sendRedirect($url);
    }
}



return 'modWebOrdersActionsProcessProcessor';