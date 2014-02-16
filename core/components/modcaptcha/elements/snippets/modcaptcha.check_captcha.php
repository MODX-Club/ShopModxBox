<?php
if(empty($scriptProperties['captcha_key'])){
    $scriptProperties['captcha_key'] = $modx->getOption('modcaptcha.session_id', null, 'php_captcha');
}

$key = $scriptProperties['captcha_key'];

if(empty($scriptProperties['code'])){
    $param = "_".strtoupper($method);
    
    if(isset($$param)){
        $p = $$param;
        
        $scriptProperties['code'] = (!empty($p[$key]) ? $p[$key] : "");
    }
}

if(!$path = $modx->getOption('modcaptcha.core_path')){
    $path = $modx->getOption('core_path', null, MODX_CORE_PATH). 'components/modcaptcha/';
}

if(!$response = $modx->runProcessor('modcaptcha/web/check', $scriptProperties, array(
    'processors_path' => $path.'processors/',
))){
    $modx->log(xPDO::LOG_LEVEL_ERROR, "[modCaptcha - modcaptcha.check_captcha snippet] - Can not execute processor");
    return '';
}

if($response->isError()){
    return '';
}

// else
return 'true';