<?php


require_once MODX_PROCESSORS_PATH . 'security/login.class.php';

class modSiteWebUsersLoginProcessor extends modSecurityLoginProcessor{
     

    public function initialize(){

        $this->setDefaultProperties(array(
            "username"      => $this->getProperty('login'),
        ));

        $properties = array(
            "username"      => $this->getProperty('username'), 
            "password"      => $this->getProperty('password'), 
            "rememberme"    => $this->getProperty('rememberme'), 
            "sms_code"      => $this->getProperty('sms_code'),
            "useSmsAuth"    => false,
        ); 
        
        $this->properties = array();
        
        $this->setProperties($properties);

        return parent::initialize();
    }
    
    
    public function process() {
        $preventLogin = $this->beforeLogin();
        if (!empty($preventLogin)) {
            return $this->failure($preventLogin);
        }

        $canLogin = $this->fireOnAuthenticationEvent();
        
        # var_dump($canLogin);
        
        $preventLogin = $this->checkPassword($canLogin);
        
        # var_dump($preventLogin);
        
        if (!empty($preventLogin)) {
            return $this->failure($preventLogin);
        }
        
        
        # var_dump($sms_code);
        
        /*
            Если указан код, проверяем его.
            Если нет, отправляем сообщение.
            Проверяем и по частоте отправлений
        */
        
        if(
            $phone = trim($this->user->Profile->phone)
            AND $this->getProperty("useSmsAuth")
            AND $smsGate = $this->modx->getService("smsGate")
        ){

            if(empty($_SESSION['sms_code'])){
                $_SESSION['sms_code'] = array();
            }

            $sms_code = trim($this->getProperty("sms_code"));

            # if($this->modx->getCount())



            if(empty($sms_code)){

                // Проверяем когда последний раз отправлялось SMS
                $q = $this->modx->newQuery("smsGateItem", array(
                    "user_id"   => $this->user->id,
                ));

                $alias = $q->getAlias();

                $minutes = 5;

                $qq = clone $q;

                $qq->where(array(
                    "{$alias}.createdon > now() - interval {$minutes} minute",
                ));

                if($this->modx->getCount("smsGateItem", $qq)){
                    return $this->failure("SMS отправлять можно не чаще чем раз в {$minutes} минут");
                }

                $qqq = clone $q;

                $qqq->where(array(
                    "{$alias}.createdon >= date_format(now(), '%Y-%m-%d')",
                ));

                $limit = 5;

                if($this->modx->getCount("smsGateItem", $qqq) >= $limit){
                    return $this->failure("В сутки можно отправить максимум {$limit} SMS");
                }

                // Пытаемся отправить смс
                $code = rand(100000,999999);

                $response = $smsGate->send($phone, "Ваш код авторизации: {$code}", $this->user->id);

                if(empty($response['success']) OR $response['success'] !== true){

                    if(empty($response['message'])){
                        $response['message'] = "Ошибка отправки SMS";
                    }
                    return $response;
                }

                // else
                $_SESSION['sms_code'][] = $code;

                return $this->failure("Необходимо ввести смс-код", array(
                    "error_code"    => 'need_sms',
                ));

            }
            else{
                if(!in_array($sms_code, $_SESSION['sms_code'])){
                    return $this->failure("Неверный смс-код", array());
                }
            }

            if(empty($_SESSION['sms_code'])){
                return $this->failure("Вам отправлен смс-код", array(
                    "error_code"    => 'need_sms',
                ));
            }
        }
        

        $response = $this->afterLogin();
        return $this->cleanup($response);
    }
    
    
    public function cleanup($response) {
        unset($_SESSION['sms_code']);

        return $this->success('Вы успешно авторизованы', $response);
    }
        
}

return 'modSiteWebUsersLoginProcessor';



