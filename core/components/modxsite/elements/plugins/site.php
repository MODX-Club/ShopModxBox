<?php
switch($modx->event->name){
    
    case 'OnHandleRequest':
        if($modx->context->key == 'mgr'){
            return;    
        } 
        
        
        /*
            Выход. Убивается сессия вообще, выйдет из всех контекстов, в том числе и mgr
        */
        if(isset($_GET['service'])){
            switch(strtolower($_GET['service'])){
                case 'logout':
                    $modx->user->endSession();
                    $modx->user = null;
                    $modx->getUser();
                    break;
            }
        }
        
        break;
        
    case 'OnManagerLogin':
        if($user){
           // Автоматически авторизуем в web
           if(!$user->isAuthenticated('web')){
               $user->addSessionContext('web');
           }
        }
        
        break;
        
    case 'OnDocFormSave':
    case 'OnSiteRefresh':
        
        // Сброс node-js - кеша 

        $site_url = trim($modx->getOption("site_url"), "/");
        
        $client = $modx->getService('rest.modRestCurlClient');
        $result = $client->request($site_url, '/api/', 'POST', $params = array(
            "operationName" => "clearCache",
        ), array(
            "contentType"       => "json",
        ));
        
        break;
          
}