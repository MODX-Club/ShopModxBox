<?php
/*

    Устарел, так как теперь оформление заказа - просто смена его статуса
*/
require_once dirname(dirname(__FILE__)).'/basket.class.php';
class modWebBasketOrderCreateProcessor_depricated extends modWebBasketProcessor{
    
    protected $fields = array();


    public function initialize() {
        
        /*
            Сбрасываем, так как лезет непонятная ошибка 12.
            Где-то еррор уже фиксируется
        */
        $this->modx->error->reset();
        
        if(empty($_POST)){
            return;
        }
        
        $data = array();
        if(!empty($_POST)){
            $data = $_POST;
        }
        
        $this->setDefaultProperties($data);
        $this->setDefaultProperties(array(
            'ordertype' => 'default',    
        ));
        
        // Проверяем наличие товаров в корзине
        if(!$this->getSessionData()){
            return "Ваша корзина пуста";
        }
        
        return parent::initialize();
    }


    public function process() {
        switch ($this->getProperty('ordertype')){
            case 'default':
                $this->processDefaultOrder();
                break;
            
            default: return $this->failure('Неизвестный тип заказа');
        }
        if($this->hasErrors()){
            return $this->failure('Пожалуйста, исправьте ошибки в форме', $this->getProperties());
        }
        
        
        // Пытаемся создать заказ
        $created = $this->createOrder();
        if($created !== true){
            return $created;
        }
        
        
        // Отправляем письмо
        $this->sendNotification();
        
        
        // Сбрасываем корзину и сообщаем об успехе
        $this->flush();
        return $this->success('Ваш заказ успешно принят. Наши менеджеры свяжутся с вами в ближайшее время.'); 
    }
    
    protected function processDefaultOrder(){
        $this->fields = array(
            'name' => array(
                'name'      => 'ФИО',
                'required'  => true,
                'error_message' => 'Укажите имя',      
            ),
            'email' => array(
                'name'  => 'Емейл',
                'required' => true,
                'error_message' => 'Укажите емейл',     
            ),
            'phone' => array(
                'name'  => 'Сотовый телефон',
                'error_message' => 'Укажите телефон',     
                'required' => true,
            ),
            'order_address' => array(
                'name'  => 'Адрес доставки',
                'error_message' => 'Адрес доставки',     
                'required' => true,
            ),
        );
        
        if(!$this->validate($fields)){
            return false;
        }
        return true;
    }
    
    protected function validate(){
        foreach($this->fields as $field => $d){
            if($d['required'] && !$this->getProperty($field)){
                $error = !empty($d['error_message']) ? $d['error_message'] : 'Поле заполненно не корректно';
                $this->addFieldError($field, $error);
                continue;
            }
            switch ($field){
                case 'email':
                    if(!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $this->getProperty($field))){
                        $this->addFieldError($field, 'Укажите корректный емейл');
                    }
                    break;
                default: 
            }
        }
        return !$this->hasErrors();
    }


    // Создаем заказ
    protected function createOrder(){
        $this->getUser();
        
        $data = $this->getSessionData();
         
        $user = $this->getProperty('user');
        
        
        $properties = $this->getProperties();
        $properties['order_address'] = preg_replace("/[\r\n]+/","<br />\n",$this->getProperty('order_address'));
        $properties['comments'] = preg_replace("/[\r\n]+/","<br />\n",$this->getProperty('comments'));
        $properties['products'] = $data;
        $properties['created_by'] = $user->id;
        
        // Создаем заказ
        if(!$response = $this->modx->runProcessor('mgr/shop/order/create', $properties, array(
            'processors_path' => $this->modx->getObject('modNamespace', 'basket')->getCorePath(). 'processors/'
        ))){
            return $this->failure('Не удалось сохранить заказ');
        }
        
        if($response->isError()){
            return $response->getResponse();
        } 
        
        $object = $response->getObject();
        
        $this->setProperty('order_id', $object['id']);
        
        return true;
    }
    
    protected function getUser(){
                /*
         * Получаем пользователя по емейлу или создаем нового
         */
        $email = $this->getProperty('email');
        $c = $this->modx->newQuery('modUser');
        $c->innerJoin('modUserProfile', 'Profile');
        $c->where(array(
            'Profile.email:LIKE' => $email,
        ));
        if(!$user = $this->modx->getObject('modUser', $c)){
            $user = $this->modx->newObject('modUser', array(
                'active' => 1,
                'username' => $email,
            ));
            $user->set('id', 0);
            $profile = $this->modx->newObject('modUserProfile', array(
                'email' => $email,
                'password'  => md5(uniqid()),  
                'fullname'  => $this->getProperty('name'),
            ));
            if($phone = $this->getProperty('phone')){
                $profile->set('phone', $phone);
            }
            $memberGroup = $this->modx->newObject('modUserGroupMember', array(
                'user_group' => $this->modx->getOption('shop.customers_group', null, 0),    
            ));
            $user->addOne($profile);
            /*$memberGroup->addOne($user);
            $memberGroup->save();*/
            $user->addMany($memberGroup);
            $user->save();
        }
        /*
         * Создаем и сохраняем заказ
         */
         
        $this->setProperty('user', $user);
        return true;
    }

    protected function sendNotification(){ 
         
        $this->modx->smarty->assign('order_id', $this->getProperty('order_id'));
         
        
        // exit;
        $data = array();
        foreach($this->fields as $name => $field){
            $data[]=array(
                'title' => $field['name'],
                'value' => $this->getProperty($name, ''),
            );
        }
        $this->modx->smarty->assign('data', $data);
        $message = $this->modx->smarty->fetch('message/new_order.tpl');
        
        
        /*
         * Получаем менеджеров, кому надо отправить уведомления, 
         * и пользователя, подавшего заявку
         */
        $q = $this->modx->newQuery('modUser');
        $q->innerJoin('modUserProfile', 'Profile');
        $q->innerJoin('modUserGroupMember', 'UserGroupMembers');
        $q->where(array(
            'Profile.email:!=' => '',
            'UserGroupMembers.user_group'   => $this->modx->getOption('shop.managers_notify_group', null, 1),
        ));
        
        
        $user = $this->getProperty('user');
        if(!empty($user)){
            $q->orCondition(array(
                "id"    => $user->id, 
            ));
        }
        
        if($users = $this->modx->getCollection('modUser', $q)){
            foreach($users as $user){
                $user->sendEmail($message, array(
                    'subject'   => 'Новый заказ',
                ));
            }    
        }
        return true;
    }
}


class modWebBasketOrderCreateProcessor extends modWebBasketOrderCreateProcessor_depricated{
    
    public function initialize(){
        
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[- Basket -] Class '". __CLASS__ ."' is depricated. ".__FILE__.":".__LINE__);
        
        return parent::initialize();
    }
}


return 'modWebBasketOrderCreateProcessor';