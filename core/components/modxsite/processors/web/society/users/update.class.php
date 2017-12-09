<?php

/*
    Обновление пользователя.
    Надо быть очень внимательным, чтобы никто не мог перегрузить права пользователя (группы) или типа того
*/

require_once dirname(__FILE__) . '/_validator.class.php';

abstract class modWebSocietyUsersUpdateProcessor extends modObjectUpdateProcessor{
    
    public $classKey = 'modUser';
    public $profile = null;
    
    
    public function checkPermissions(){
        
        if(!$this->modx->user->id){
            return false;
        }
        
        return parent::checkPermissions();
    }
    
    
    public function initialize(){
        
        if(!$user_id = (int)$this->getProperty('user_id')){
            return "Не был получен ID пользователя";
        }
        
        // else
        $this->setProperty('id', $user_id);
        
        return parent::initialize();
    }
    
    
    public function beforeSet(){
        
        $this->profile =& $this->object->Profile;
        
        return parent::beforeSet();
    }
    
    
    public function beforeSave(){
        $user = & $this->object;
        
        $this->profile->fromArray($this->getProperties());
        
        if($new_password = trim($this->getProperty('new_password'))){
            $password_min_length = $this->modx->getOption('password_min_length', null, 6);
            if(mb_strlen($new_password, 'utf-8') < $password_min_length){
                $error = "Пароль должен быть не менее {$password_min_length} символов";
                $this->addFieldError('new_password', $error);
            }
            else{
                $user->set('password', $new_password);
            }
        }
        
        $validator = new modWebSocietyUsersValidator($this);
        
        $ok = $validator->validate();
        
        if($ok !== true){
            return $ok;
        }
        
        # 
        # // Уведомления
        # 
        
        # if($notices = (array)$this->getProperty('notices')){
        #     foreach($notices as $notice_id){
        #         if($notice = $this->modx->getObject('SocietyNoticeType', $notices)){
        #             
        #         }
        #     }
        # }
        
        if($notices = (array)$this->getProperty('notices', array())){
            $notices = array_flip($notices);
        }
        
        // print_r($notices);
        $userNotices = (array)$user->Notices;
        
        foreach($userNotices as & $userNotice){
            
            if(array_key_exists($userNotice->notice_id, $notices)){
                $userNotice->active = 1;
                unset($notices[$userNotice->notice_id]);
            }
            else{
                $userNotice->active = 0;
            }
            
            // print_r($userNotice->toArray());
        }
         
        
        # print_r($notices);
        
        foreach($notices as $notice_id => $val){
            if($notice = $this->modx->getObject('SocietyNoticeType', $notice_id)){
                $newUserNotice = $this->modx->newObject('SocietyNoticeUser', array(
                    "active"    => 1,
                ));
                $newUserNotice->User = $user;
                $newUserNotice->NoticeType = $notice;
                $userNotices[] = $newUserNotice;
                
                # print_r($newUserNotice->NoticeType->toArray());
                # 
                # return;
            }
            # else{
            #     print "Error";
            #     exit;
            # }
        }
        
        $user->Notices = $userNotices;
        
        # exit;
        # foreach($userNotices as $userNotice2){
        #     print_r($userNotice2->toArray());
        # }
        
        # return;
        
        
        ####################################
        
        # return 'Debug';
        return parent::beforeSave();
    }
    
    public function afterSave(){
        
        $this->modx->removeCollection('SocietyNoticeUser', array(
            "active"    => 0,
        ));
        
        return parent::afterSave();
    }
}

return 'modWebSocietyUsersUpdateProcessor';

