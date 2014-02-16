<?php
if(
    !$modx->hasPermission('Debug')
    OR $modx->context->key == 'mgr'
){
    return;
}

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);
$modx->setLogTarget('HTML');
$modx->setLogLevel(1);