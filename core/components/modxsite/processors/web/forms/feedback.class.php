<?php

/*
    Форма обратной связи
*/

require_once dirname(dirname(dirname(__FILE__))) . '/site/web/form.class.php';

class modWebFormsFeedbackProcessor extends modSiteWebFormProcessor{
    
    protected $manager_message_tpl = "message/forms/feedback.tpl";
    
    protected $use_captcha  = true;             // modCaptcha Extra required. http://modx.com/extras/package/modcaptcha
    
    
    public function initialize(){
        
        $this->manager_group_ids = array(
            $this->modx->getOption('shop.managers_notify_group', 1),
        );
        
        return parent::initialize();
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
        $fields = array_merge(
            parent::getFields(), array(
                'email' => array(
                    'required' => true,
                    'error_message' => 'Укажите емейл',     
                ), 
                'fullname' => array(
                    'required' => true,
                    'error_message' => 'Укажите имя',     
                ), 
                'message' => array(
                    'required' => true,
                    'error_message' => 'Заполните сообщение',     
                ), 
            )
        );
        return $fields;
    }
    
}


return 'modWebFormsFeedbackProcessor';

