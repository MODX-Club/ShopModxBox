<?php

if(empty($_REQUEST['ctx'])){
    $_REQUEST['ctx'] = 'web';    
}

if(!isset($location)){
    $location = '';    
}

// @session_cache_limiter('public');
define('MODX_REQP',false);
    
require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

//$modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);
//  $modx->setLogTarget("FILE");


if ($modx->user->hasSessionContext($modx->context->get('key'))) {
    $_SERVER['HTTP_MODAUTH'] = $_SESSION["modx.". $modx->context->get('key') .".user.token"];
} else {
    $_SESSION["modx." .$modx->context->get('key'). ".user.token"] = 0;
    $_SERVER['HTTP_MODAUTH'] = 0;
}

/* handle request */
if(!$path = $modx->getOption('modhybridauth.core_path')){
    $path = $modx->getOption('core_path').'components/modhybridauth/';
}
$path .= 'processors/web/';

$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => $location,
));