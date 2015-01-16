<?php
$_REQUEST['ctx'] = 'web';

define('MODX_REQP', false);
    
require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$_SERVER['HTTP_MODAUTH']= $modx->user->getUserToken($modx->context->get('key'));
 
$location = '';

/* handle request */
if(!$path = $modx->getOption('basket.core_path')){
    $path = $modx->getOption('core_path').'components/basket/';
}
$path .=  'processors/';


$_REQUEST['basket_action'] = $_POST['basket_action'] = (!empty($_REQUEST['action']) ? $_REQUEST['action'] : '');

$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location'  => $location,
    'action'    => 'basket/web/public/action',
));