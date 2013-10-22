<?php
switch($modx->event->name){
    case 'OnHandleRequest':
        //return;
        if($modx->context->key == 'mgr'){
            return;
        }
        
        if(!empty($_REQUEST['basket_action'])){
            
            $error = '';
            $success = '';   
              
            if($response = $modx->runProcessor($basket_processor, $_REQUEST, array(
                'processors_path' => $modx->getObject('modNamespace', $basket_namespace) -> getCorePath() .'processors/',    
            ))){
                if($response->isError()){
                    $error = $response->getMessage();
                    $modx->log(xPDO::LOG_LEVEL_ERROR, $error);
                    $modx->log(xPDO::LOG_LEVEL_ERROR, print_r($response->getAllErrors(), true));
                }
                else{
                    $success = $response->getMessage();
                }
            }
            else{
                $error = 'Не удалось выполнить процессор';
            }
            $modx->setPlaceholder('basket.action.success', $success);
            $modx->setPlaceholder('basket.action.failure', $error);
            
            
            return;
        }        
        
        
        break;
    
    case 'OnManagerPageInit':
        $assetsUrl = $modx->getOption('basket.manager_url',null,$modx->getOption('manager_url').'components/basket/') ;
        
        $config = array(
            'assets'    =>    $assetsUrl,  
            'assets_url'    =>    $assetsUrl,  
            'connector_url'    =>    $assetsUrl.'connectors/',  
        );
        
        $modx->regClientCSS($assetsUrl.'css/style.css');
        $modx->regClientStartupScript("{$assetsUrl}js/shop.js");
        $modx->regClientStartupScript("{$assetsUrl}js/widgets/orders/orderstatus.combo.js"); 
        $modx->regClientStartupScript(
                "<script type=\"text/javascript\">
                    Shop.config = ". $modx->toJSON($config).";
                 </script>
                "
        , true);
        break; 
}