<?php

/*
    Регистрация пользователя.
    Емейл - это Логин.
    Пользователю отправляется емейл для активации
*/

require_once MODX_PROCESSORS_PATH . 'security/user/create.class.php';

require_once dirname(__FILE__) . '/_validator.class.php';

class modWebSocietyUsersCreateProcessor extends modUserCreateProcessor{
    
    public $permission = '';
    
    public function initialize(){


        $request_body = file_get_contents('php://input');

        if($request_body AND $data = json_decode($request_body, 1)){
            $this->setProperties($data);
        }
        

        foreach($this->properties as $name => & $value){

            if(is_scalar($value)){

                switch((string)$value){

                    case 'true':

                        $value = true;

                        break;

                    case 'false':

                        $value = false;

                        break;

                    case '0':

                        $value = 0;

                        break;

                    case 'null':

                        $value = null;

                        break;

                    case 'undefined':

                        unset($this->properties[$name]);

                        break;
                }
            }

        }
        
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
                    "usergroup" => 12,
                    "role"      => 1,
                ),
            ),
            'email'                 => mb_convert_case($this->getProperty('email'), MB_CASE_LOWER, 'utf-8'),
            "mail_template"         => "messages/users/registration.tpl",
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
        
        if(!isset($user->delegate)){
            $this->addFieldError("delegate", "Укажите являетесь ли вы представителем компании");
        }

        $user->SocietyProfile = $this->modx->newObject('SocietyUserProfile', array(
            "createdon" => time(),
        ));
        
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
         
        // else
        
        $validator = new modWebSocietyUsersValidator($this);
        
        $ok = $validator->validate();
        
        if($ok !== true){
            return $ok;
        }
        
        # print '<pre>';
        
        // Добавляем сразу все типы уведомлений
        $notices = array();
        foreach($this->modx->getCollection('SocietyNoticeType') as $noticeType){
            $notice = $this->modx->newObject('SocietyNoticeUser');
            $notice->NoticeType = $noticeType;
            
            # print_r($notice->toArray());
            $notices[] = $notice;
        }
        $user->Notices = $notices;
        
        /*
        
        return "Ведутся работы";
        
        print_r($this->properties);
        
        print $activate_link;
            
        */

        // print_r($user->toArray());
        // return "Debug";
        
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
            // $activate_link = $this->modx->makeUrl(
            //     998, '', array(
            //         "u"     => $user->id,
            //         "k"     => md5($user->id . $user->Profile->email),
            //     ),
            //     'full'
            // );
            
            $activation_key = md5($user->id . $user->Profile->email);

            $activate_link = $this->modx->getOption("site_url") . "profile/{$user->username}/activation/?k={$activation_key}";

            $this->modx->smarty->assign('activate_link', $activate_link);
            
            $site_name = $this->modx->getOption('site_name');
            
            $this->modx->smarty->assign('newPassword', $this->newPassword);
            $this->modx->smarty->assign('properties', $this->getProperties());
            $this->modx->smarty->assign('user', $user);



//             if($this->getProperty("isOffer")){
                
//                 $date = date("m-d-Y", time() + (3600 * 24 * 7));
                
//                 $offer = <<<HTML
//                     <p>
                        
//                         Мы запустили обновленную версию нашего проекта: <a href="http://gorodskie-bani.ru/">http://gorodskie-bani.ru/</a>

//                     </p>

//                     <p>
//                         Теперь портал будет развиваться в формате глобального картографического сервиса с направленностью на обширную аудиторию. 
//                     </p>

//                     <p>
//                         В течение месяца будет опубликован функционал для самостоятельного добавления бань и саун на карте (что должно обеспечить значительную генерацию контента и увеличение посещаемости, вовлеченности и т.п.). Но будут вводиться и платные функции (индивидуальные иконки, расширенные карточки организаций, онлайн-формы, аналитика и т.п.). По аналитике у нас будут вообще очень интересные решения вплоть до возможности просмотреть в каких районах карты какое количество просмотров пользователей и т.п., а так же возможность видеть пользователей онлайн и возможность инициировать диалог с пользователем. Сейчас мы планируем вводить оплату за каждую услугу в отдельности, чтобы каждый сам решал какие бюджеты выделять на этот рекламный канал. По нашим оценкам средний чек нашего партнера будет составлять 3 - 10 тысяч рублей в месяц уже через 3 месяца (когда мы введем бОльшую часть запланированного функционала). Но на развитие проекта финансы нужны уже сейчас, поэтому у нас есть для Вас спецпредложение: премиальное размещение на год всего за 15 000 рублей разово. При оплате до {$date} мы разместим Вашу карточку, присвоим Вашей компании индивидуальную иконку на карте, а так же гарантируем предоставление всех вводимых на портале услуг в течение этого года бесплатно. Плюс ко всему этому гарантируется наше особое отношение к Вашей компании и максимальная лояльность во всем, включая пожелания к развитию проекта (кроме политики пользовательских отзывов и т.п., с этом у нас очень серьезно все.). Уверяю, это очень выгодное предложение. Вот динамика посещения портала: <a href="http://joxi.ru/Drlz7Mqc4JdNL2">http://joxi.ru/Drlz7Mqc4JdNL2</a> На этот новый год, уверен, будет более 2000 пользователей, а к следующему НГ не менее 10 000 (так как сейчас будет объектов добавляться много, соответственно больше материала для поискового трафика будет). У нас весь трафик белый, не покупной. Могу предоставить доступ в Яндекс.Метрику для просмотра статистики.
//                     </p>
                        

//                     <p>
                    
//                         Возможна оплата по договору.
                        
//                     </p>
// HTML;

//                 $this->modx->smarty->assign('text', $offer);


//                 $user->fromArray(array(
//                     "offer" => $offer,
//                     "offer_date"    => time(),
//                     "createdon"     => 0,
//                 ));

//                 $user->save();

//             }

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


return 'modWebSocietyUsersCreateProcessor';

