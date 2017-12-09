<?php

/*
    Обновление пользователя.
    Надо быть очень внимательным, чтобы никто не мог перегрузить права пользователя (группы) или типа того
*/

// require_once dirname(__FILE__) . '/_validator.class.php';

class modWebUsersOwnprofileUpdateProcessor extends modObjectUpdateProcessor{
    
    public $classKey = 'modUser';
    public $profile = null;

    protected $images_path = 'assets/society/uploads/images/';
    
    public function checkPermissions(){
        
        if(!$this->modx->user->id){
            return false;
        }
        
        return parent::checkPermissions();
    }
    
    
    public function initialize(){

        $request_body = file_get_contents('php://input');

        if($request_body AND $data = json_decode($request_body, 1)){
            $this->setProperties($data);
        }
        
        foreach($this->properties as $field => & $value){

            if(!is_scalar($value)){
                continue;
            }

            $v = (string)$value;

            if($v === "null"){
                $value = null;
            }
            else if($v === "true"){
                $value = true;
            }
            else if($v === "false"){
                $value = false;
            }
            else if($v === "NaN"){
                unset($this->properties[$field]);
            }
            else if($v === "undefined"){
                unset($this->properties[$field]);
            }
        }

        unset($value);
        
        if(!$user_id = (int)$this->modx->user->id){
            return "Не был получен ID пользователя";
        }
        
        // else
        $this->setProperty('id', $user_id);

        // $properties = array(
        //     "fullname"   => $this->getProperty('fullname'),
        //     "new_password"   => $this->getProperty('new_password'),
        //     "notices"   => $this->getProperty('notices', array()),
        //     // "api_key"   => $this->getProperty('api_key'),
        //     "photo"   => $this->getProperty('photo'),
        //     "phone"   => preg_replace("/[^0-9]/", "", trim($this->getProperty('phone'))),
        // );

        
        foreach($this->properties as $name => $value){

            // print "\n$name";

            if(!in_array($name, array(
                "id",
                "fullname",
                "new_password",
                "notices",
                "image",
                "phone",
            ))){
                // $this->unsetProperty($name);
            }
        }
        
        // var_dump($this->properties);

        // print "dsf";
        // print_r($this->properties);
        // print "dsf";

        // $this->properties = array();

        // print_r($this->properties);

        # print_r($this->getProperties());

        if(
            $phone = $this->getProperty("phone")
            AND strlen($phone) != 11
        ){
            $this->addFieldError("phone", "Телефон должен содержать 11 цифр в международном стандарте. Пример +7(999)111-22-33");
        }
        
        if($email = $this->getProperty('email')){
            $this->setProperty('email', trim(mb_convert_case($email, MB_CASE_LOWER, 'utf-8'))); 
        }
        
        return parent::initialize();
    }
    
    
    public function beforeSet(){
        
        if(!$Profile =& $this->object->Profile){
            return "Не был получен профиль пользователя";
        }

        /*
         * Фото
         * */

        // if($image = $this->getProperty("photo")){
        //     if(preg_match("/data:image\/(\w+?);base64,(.+)/u", $image, $match)){

        //         $path = $this->images_path;

        //         $ext = $match[1];
        //         $images_dir = MODX_BASE_PATH ;
        //         $image_name = md5($image).".{$ext}";
        //         $relative_path = $path . $image_name;
        //         $full_path = $images_dir . $relative_path;

        //         if(!file_exists($full_path)){
        //             $fo = fopen($full_path, "wb");

        //             fwrite($fo, base64_decode($match[2]));

        //             fclose($fo);
        //         }

        //         if(file_exists($full_path)){
        //             $Profile->set("photo", $image_name);
        //             $this->setProperty("photo", $image_name);
        //         }
        //         else{
        //             return "Ошибка сохранения фото";
        //         }
        //     }
        // }

        return parent::beforeSet();
    }
    
    
    public function beforeSave(){
        $user = & $this->object;
        
        $Profile = $user->Profile;

        $Profile->fromArray($this->getProperties());
        
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
        
        // $validator = new modWebSocietyUsersValidator($this);
        
        // $ok = $validator->validate();
        
        // if($ok !== true){
        //     return $ok;
        // }

        // $profile = & $this->processor->profile;
          
        
        // Проверяем емейл
        if(
            $Profile->isDirty("email")
            AND !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $Profile->email)
        ){
            $this->addFieldError('email', 'Укажите корректный email');
        }

        if($image = $Profile->image){

            // $image = str_replace("//", );
            $image = preg_replace('/\/{2,}/', '/', $image);

            $image = preg_replace('/^\/?assets\/images\//', '', $image);

            $Profile->set("photo", $image);

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
        
        if($notices = (array)$this->getProperty('notices')){

            // print_r($notices);

            // return;

            // Это текущие настройки пользователя, могут не содержать все переданные настройки
            $userNotices = (array)$user->Notices;

            
            $newUserNotices = array();

            foreach($notices as $n){

                if($notice = $this->modx->getObject('SocietyNoticeType', $n['id'])){

                    
                    // print "\nnotice";

                    // print_r($notice->toArray());
                    # 
                    # return;

                    // global $notice;

                    // print_r($n);

                    $userNotice = $userNotices ? array_filter($userNotices, function($un) use ($notice){

                        // return $un->id == $notice->id;

                        // print_r($un);

                        return $un->notice_id == $notice->id ? $un : false;

                    }) : null;

                    $userNotice = $userNotice ? current($userNotice) : null;

                    // print "\nuserNotice";


                    if($userNotice){

                        // var_dump($userNotice);
                        // print_r($userNotice->toArray());

                        $userNotice->active = $n['active'];

                    }
                    else if($n['active']){

                        $userNotice = $this->modx->newObject('SocietyNoticeUser', array(
                            "active"    => 1,
                        ));
                        $userNotice->User = $user;
                        $userNotice->NoticeType = $notice;

                        // $userNotices[] = $userNotice;

                    }

                    if($userNotice){

                        $newUserNotices[] = $userNotice;

                    }

                }

                # else{
                #     print "Error";
                #     exit;
                # }
            }
            
            // $user->Notices = $userNotices;
            $user->Notices = $newUserNotices;
            
            # exit;

            // print "\n new notices";

            // foreach($newUserNotices as $userNotice2){
            //     print_r($userNotice2->toArray());
            // }
            
            // return;
        }


        
        
        ####################################

        // print_r($user->toArray());

        // return 'Debug';

        return parent::beforeSave();
    }
    
    public function afterSave(){
        
        $this->modx->removeCollection('SocietyNoticeUser', array(
            "active"    => 0,
        ));
        
        return parent::afterSave();
    }

    public function cleanup(){

        $object = & $this->object;

        // $result = parent::cleanup();

        // $result = $result['object'];

//         $photo = $object->Profile->photo;

// //        $data = $result;

//         $notices = array();

//         $q = $this->modx->newQuery("SocietyNoticeType");

//         $alias = $q->getAlias();

//         $q->leftJoin("SocietyNoticeUser", "NoticeUsers", "SocietyNoticeType.id = NoticeUsers.notice_id AND NoticeUsers.user_id = {$object->id}");

//         $q->select(array(
//             "{$alias}.id",
//             "type",
//             "comment",
//             "if(NoticeUsers.id is not null, 1, 0) as active",
//         ));

//         $q->sortby("rank", "ASC");

//         $s = $q->prepare();

//         $s->execute();

//         while($row = $s->fetch(PDO::FETCH_ASSOC)){
//             $notices[] = $row;
//         }

//         $data = array(
//             "id"  => $object->id,
//             "fullname"  => $object->Profile->fullname,
//             "photo" => $photo ? $this->images_path . $photo : "",
//             "username" => $object->username,
//             "api_key" => $object->api_key,
//             "notices" => $notices,
//         );
        
        $this->modx->cacheManager->refresh();
        $this->modx->cacheManager->clearCache();

        $data = $object->toArray();

        foreach($data as $name => $value){
            if(!array_key_exists($name, $this->properties)){
                unset($data[$name]);
            }
        }

        return $this->success("Профиль успешно обновлен", $data);
    }
}

return 'modWebUsersOwnprofileUpdateProcessor';

