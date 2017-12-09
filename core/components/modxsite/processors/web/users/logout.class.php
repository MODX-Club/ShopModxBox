<?php

require_once MODX_PROCESSORS_PATH . 'security/logout.class.php';

class modWebUsersLogoutProcessor extends modSecurityLogoutProcessor{

    public function initialize() {

        $this->setProperties(array(
            "login_context" => "web",
            "add_contexts" => "mgr",
        ));

        return parent::initialize();
    }

}

return 'modWebUsersLogoutProcessor';
