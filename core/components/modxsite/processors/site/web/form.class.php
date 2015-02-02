<?php

/*
    Process web forms
*/

class modSiteWebFormProcessor extends modProcessor{
    
    
    protected $manager_message_tpl = "modxsite/message/forms/default.tpl";
    
    protected $manager_group_ids = array(1);
    
    protected $use_captcha  = false;             // modCaptcha Extra required. http://modx.com/extras/package/modcaptcha
    
    protected $use_subprocessor = false;            // Execute subprocessor on success form
    protected $subprocessor = '';                       // For example "site/web/getdata"
    protected $subprocessor_namespace = 'modxsite';
    
    
    public function initialize(){
        
        $this->setProperties(
            $this->getInitializeProperties()
        );
        
        if($email = mb_strtolower(trim($this->getProperty('email')))){
            $this->setProperty('email', $email);
        }
        
        if($this->use_captcha){
            $this->checkCaptcha();
        }
        
        // Выполнеяем проверку данных
        if(!$this->validateFields()){
            return "Проверьте правильность заполнения формы";
        }
        
        return parent::initialize() && !$this->hasErrors();
    } 


    public function process(){
        $result = null;
        
        
        if($this->use_subprocessor){
            
            if($this->subprocessor && $this->subprocessor_namespace){
                
                $properties = $this->getProperties();
                
                if(!$response = $this->modx->runProcessor($this->subprocessor,
                    $properties, 
                    array(
                        'processors_path' => $this->modx->getObject('modNamespace', $this->subprocessor_namespace)->getCorePath().'processors/',
                    )
                )){
                    return $this->failure("Не удалось выполнить процессор");
                }
                
                // else
                
                if(!$result = $response->getResponse()){
                    return $this->failure('Error execute request');
                }
                
                if($response->isError()){
                    return $result;
                }
                
            }
            else{
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, '[- '.__CLASS__.' -]. Does not set $this->subprocessor or $this->subprocessor_namespace');
                return $this->failure('Error execute request');
            }
            
        }
        
        // Отправляем уведомления
        $this->sendNotification();
        
        
        if(!$this->use_subprocessor){
            $result = $this->success('');
        }
        
        return $this->cleanup($result);
    }
    
    
    protected function getInitializeProperties(){
        return array(
            "manager_group_ids"     => $this->manager_group_ids,
            "manager_mail_subject"  => $this->getManagerMailSubject(),
        );
    }
    
    
    protected function getManagerMailSubject(){
        $site_name = $this->modx->getOption('site_name');
        $subject = "Сообщение с сайта «{$site_name}»";
        return $subject;
    }
    
    
    protected function validateFields(){
        
        $fields = $this->getFields();
        
        foreach($fields as $field => $d){
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
    
    
    protected function checkCaptcha(){
        
        // modCaptcha Extra required. http://modx.com/extras/package/modcaptcha
        
        $result = $this->modx->runSnippet('modcaptcha.check_captcha', array(
            "code"  => $this->getProperty('captcha'),
        ));
        
        if($result !== 'true'){
            $error = (!empty($result) ? $result : "Неверный проверочный код");
            // Надо будет добавить лексиконы в modCaptcha
            $this->addFieldError('captcha', $error);
        }
        
        return;
    }
    
    /*
        Example: 
        $fields = array(
            'email' => array(
                'required' => true,
                'error_message' => 'Fill email',     
            ),
        );
    */
    protected function getFields(){
        $fields = array();
        return $fields;
    }
       
    
    
    /*
        Получаем группы, в которые надо добавить пользователя
    */
    protected function sendNotification(){
        
        // Набиваем данные в шаблонизатор
        $this->modx->smarty->assign('properties', $this->getProperties());
        
        // Отправляем письма менеджерам
        $this->sendManagersEmail();
        
        return;
    }
    
    
    // Отправляем письма менеджерам
    protected function sendManagersEmail(){
        if($message = $this->getMessage($this->manager_message_tpl)){
            /*
             * Получаем менеджеров, кому надо отправить уведомления
             */
            $q = $this->modx->newQuery('modUser');
            $q->innerJoin('modUserProfile', 'Profile');
            $q->innerJoin('modUserGroupMember', 'UserGroupMembers');
            $q->where(array(
                'active'        => 1,
                'Profile.blocked'       => 0,
                'Profile.email:!=' => '',
                'UserGroupMembers.user_group:in'   => (array)$this->getProperty('manager_group_ids', 1),
            )); 
            
            if($users = $this->modx->getCollection('modUser', $q)){
                foreach($users as $user){
                    $user->sendEmail($message, array(
                        'subject'   => $this->getProperty("manager_mail_subject"),
                    ));
                }    
            }
        }
        return;
    }
    
    
    // Получаем текст письма для уведомления контрагента
    protected function getMessage($tpl){
        return $this->modx->smarty->fetch($tpl);
    }
    
    
    public function cleanup($result){
        return $result;
    }    
    
    
}

return 'modSiteWebFormProcessor';
