<?php

/*
    Регистрация пользователя.
    Емейл - это Логин.
    Пользователю отправляется емейл для активации
*/

require_once MODX_PROCESSORS_PATH . 'security/user/create.class.php';

class modWebUsersCreateProcessor extends modUserCreateProcessor{
    
    public $permission = '';
    
    public function initialize(){

        
        $this->setDefaultProperties(array(
            "passwordnotifymethod"  => "this",
            "passwordgenmethod"     => "g",
            "auto_auth"             => true,   // Автоматическая авторизация пользователя
            'active'                => true,
        ));
        
        $this->setProperties(array(
            "use_captcha"           => false,
            "groups"                => array(
                array(
                    "usergroup" => 10,
                    "role"      => 1,
                ),
            ),
            'email'                 => mb_convert_case(trim($this->getProperty('email')), MB_CASE_LOWER, 'utf-8'),
            "mail_template"         => "message/users/registration.tpl",
        ));
        
        
        $username = $this->getProperty("username");

        if(isset($username)){
            $this->setProperty("username", trim($username));
        }

        
        $confirmpassword = $this->getProperty('specifiedpassword');
        $this->setProperty('confirmpassword', $confirmpassword);


        return parent::initialize() && !$this->hasErrors();
    }
    
    protected function checkCaptcha(){
        
        // modCaptcha Extra required. http://modx.com/extras/package/modcaptcha
        
        $result = $this->modx->runSnippet('modcaptcha.check_captcha', array(
            "code"  => $this->getProperty('captcha'),
        ));
        
        if($result !== 'true'){
            $error = (!empty($result) ? $result : "Неверный проверочный код");
            $this->addFieldError('captcha', $error);
        }
        
        return;
    }
    
    
    public function beforeSet() {
        $user =& $this->object;
        
        if(
            !empty($_SESSION['social_profile'])
            AND !empty($_SESSION['social_profile']['profile'])
            AND !empty($_SESSION['social_profile']['provider'])
            AND $provider = $this->modx->getObject('modHybridAuthProvider', array(
                "name"  => $_SESSION['social_profile']['provider'],
            ))
        ){
            $profile = $_SESSION['social_profile']['profile'];
            $socialProfile = $this->modx->newObject('modHybridAuthUserProfile', $_SESSION['social_profile']['profile']);
            
            // Проверяем нет ли соцпрофиля с таким ID и провайдером
            $q = $this->modx->newQuery('modHybridAuthUserProfile', array(
                "identifier"    => $socialProfile->identifier,
                "provider"      => $provider->id,
            ));
            $q->limit(1);
            
            /*
                
            */
            if($this->modx->getCount('modHybridAuthUserProfile', $q)){
                return "Данный социальный профиль уже существует на сайте";
            }
            
            
            $this->setProperties(array(
                "active"    => 1,           // Делаем пользователя сразу активным
                "auto_auth" => 1,           // Автоматическая авторизация
                # "email" => $profile['email'],
            ));
            
            $socialProfile->Provider = $provider;
            $user->addMany($socialProfile);
            # print_r($socialProfile->toArray());
            # exit;
        }
        
        return parent::beforeSet();
    }
    
    
    public function beforeSave() {
         
        $user =& $this->object;
        
        if($this->getProperty('use_captcha') && !$user->SocialProfiles){
            $this->checkCaptcha(); 
        } 
        
        $ok = parent::beforeSave();

        if($ok !== true){
            return $ok;
        } 
        
        $profile =& $this->profile;
        $profile->fromArray(array(
            "fullname"  => $this->getProperty('fullname'),
        ));
        
        // Добавляем сразу все типы уведомлений
        // $notices = array();
        // foreach($this->modx->getCollection('SocietyNoticeType') as $noticeType){
        //     $notice = $this->modx->newObject('SocietyNoticeUser');
        //     $notice->NoticeType = $noticeType;
            
        //     # print_r($notice->toArray());
        //     $notices[] = $notice;
        // }
        // $user->Notices = $notices;
        
        return true;
    }
    
    
    public function failure($msg = '',$object = null) {
        
        if(!$msg){
            $msg = "Проверьте правильность заполнения данных";
        }
        
        return parent::failure($msg,$object);
    }
    
    
    public function cleanup(){
        
        $user = & $this->object;
        
        $mail_template = $this->getProperty("mail_template");

        // Проверяем отправку писем
        if($this->getProperty('passwordnotifymethod') == 'this'){
            
            if(empty($this->modx->smarty)){
                $this->modx->invokeEvent('OnHandleRequest');
            }
            
            // Генерация ссылки для активации
            
            $activation_key = md5($this->modx->site_id . $user->id . $user->Profile->email);

            $activate_link = $this->modx->getOption("site_url") . "profile/{$user->username}/activation/?k={$activation_key}";

            $this->modx->smarty->assign('activate_link', $activate_link);
            
            $site_name = $this->modx->getOption('site_name');
            
            $this->modx->smarty->assign('newPassword', $this->newPassword);
            $this->modx->smarty->assign('properties', $this->getProperties());
            $this->modx->smarty->assign('user', $user);


            $message = $this->modx->smarty->fetch($mail_template);
            $subject = "Регистрация на сайте {$site_name}";
             
            
            $user->sendEmail($message, array(
                "subject"   => $subject,
            ));
            
            
        }
        
        
        // Автоматическая авторизация прописана в action-процессоре
        if($this->getProperty('auto_auth')){
            $this->object->addSessionContext($this->modx->context->key);
        }
         
        
        // Если есть сессия социального профиля, очищаем
        if(isset($_SESSION['social_profile'])){
            unset($_SESSION['social_profile']);
            unset($_SESSION['HA::CONFIG']);
            unset($_SESSION['HA::STORE']);
        }
        
        // Если пользователь активен, то просто говорим, что он успешно зареген.
        // Если нет, то сооющаем, что надо активировать учетку
        if($user->active){
            $msg = 'Регистрация выполнена успешно.';
        }
        else{
            $msg = 'Регистрация выполнена успешно. На почтовый ящик вам выслана ссылка для активации.';
        }


        $this->modx->cacheManager->refresh();
        $this->modx->cacheManager->clearCache();
        
        
        return $this->success($msg, array(
            "id"    => $user->id,
        ));
    }
    
}


return 'modWebUsersCreateProcessor';

