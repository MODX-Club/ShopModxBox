<?php
$success = true;

$modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);
$modx->setLogTarget('FILE');

/*
    Try to get user by login
*/
if(empty($scriptProperties['fields']['register.user']) OR !$scriptProperties['fields']['register.user'] instanceOf modUser){
    $error = "User was not recived";
    $modx->log(xPDO::LOG_LEVEL_ERROR,$error);
    return $error;    
}

//  else 
$user = & $scriptProperties['fields']['register.user'];

 
// else
if(!empty($_SESSION['social_profile'])){
    $provider_name = $_SESSION['social_profile']['provider'];
    $profile = $_SESSION['social_profile']['profile'];
    
    // Try to get active provider
    if(!$provider = $modx->getObject('modHybridAuthProvider', array(
        "enabled"   => 1,
        "name"      => $provider_name,
    ))){
        $modx->log(xPDO::LOG_LEVEL_ERROR, "[modHybridAuth] Could not get active provider with identifier '{$provider_name}'");
        return $success;
    }
    
    // else
    
    // Check for existsing social profile
    $q = $modx->newQuery('modHybridAuthUserProfile',array(
        'identifier'    => $profile['identifier'],
        'provider' => $provider->get('id'),
    ));
    $q->limit(1);
    
    // if profile not exists, create them
    if(!$modx->getCount('modHybridAuthUserProfile', $q)){
        $socialProfile = $modx->newObject('modHybridAuthUserProfile', $profile);
        $socialProfile->addOne($provider);
        $socialProfile->addOne($user);
        if(!$socialProfile->save()){
            $modx->log(xPDO::LOG_LEVEL_ERROR, "[modHybridAuth] Could not save social profile for user with id '{$user->id}'");
            $modx->log(xPDO::LOG_LEVEL_ERROR, print_r($profile, true));
            return $success;
        }
    }
    else{
        $modx->log(xPDO::LOG_LEVEL_INFO, "[modHybridAuth] Social profile with identifier '{$profile['identifier']}' and provider '{$provider_name}' already exists.");
    }
}

if($user){
    $user->addSessionContext($modx->context->key);
}

return $success;