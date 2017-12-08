<?php

require_once MODX_PROCESSORS_PATH . 'security/logout.class.php';

class modSiteWebUsersLogoutProcessor extends modSecurityLogoutProcessor{

//    public function initialize() {
//
//        $this->setProperties(array(
//            "login_context" => $this->modx->context->key,
//            "add_contexts" => "mgr",
//        ));
//
//        return parent::initialize();
//    }

}

return 'modSiteWebUsersLogoutProcessor';
