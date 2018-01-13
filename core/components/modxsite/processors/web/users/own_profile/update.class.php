<?php

/*
    Обновление пользователя.
    Надо быть очень внимательным, чтобы никто не мог перегрузить права пользователя (группы) или типа того
*/

require_once __DIR__ . '/../object.class.php';

class modWebUsersOwnprofileUpdateProcessor extends modWebUsersObjectProcessor{
    
    
    protected $cleanupOutput = false;

    public function checkPermissions(){
        
        return $this->modx->user->id;
    }
    
    
    public function initialize(){


        $this->setProperties(array(
            "new_object"   => false,        // Флаг, что это новый объект
            "save_object"   => true,       // Флаг, что объект надо сохранять
        ));
        
        
        if(!$user_id = (int)$this->modx->user->id){
            return "Не был получен ID пользователя";
        }
        
        // else
        $this->setProperty('id', $user_id);

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


        foreach($this->properties as $name => $value){

            if(in_array($name, array(
                "username",
                "cachepwd",
                "class_key",
                "active",
                "hash_class",
                "salt",
                "primary_group",
                "session_stale",
                "sudo",
                "createdon",
            ))){
                $this->unsetProperty($name);
            }
        }

        // $this->modx->log(1, print_r($this->properties, 1), "FILE");
        

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


        return parent::beforeSave();
    }
    
}

return 'modWebUsersObjectProcessor';

