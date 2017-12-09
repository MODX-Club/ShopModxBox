<?php

require_once __DIR__ . '/object.class.php';

class modWebUsersUpdateProcessor extends modWebUsersObjectProcessor{
 
    public function initialize() {
        
        $this->setProperties(array(
            "new_object"   => false,        // Флаг, что это новый объект
            "save_object"   => true,       // Флаг, что объект надо сохранять
        ));

        return parent::initialize();
    }

}

return 'modWebUsersUpdateProcessor';

    