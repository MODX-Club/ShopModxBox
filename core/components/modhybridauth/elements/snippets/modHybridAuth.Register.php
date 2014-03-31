<?php
// Check is logged in

if ($modx->user->hasSessionContext($modx->context->key)) {
    $modx->sendRedirect($modx->getOption('site_url'));
    return;
}

// else

// Check for social profile data
if(!empty($_SESSION['social_profile'])){
    
    // If exists, fill form placeholders from this data
    $profile = $_SESSION['social_profile']['profile'];
    $modx->setPlaceholder("{$placeholderPrefix}username", $profile['displayName']);
    
    $fullname = array();
    if($profile['firstName']){
        $fullname[] = $profile['firstName'];
    }
    if($profile['lastName']){
        $fullname[] = $profile['lastName'];
    }
    if($fullname){
        $modx->setPlaceholder("{$placeholderPrefix}fullname", implode(" ", $fullname));
    }
    
    
    if($profile['email']){
        $modx->setPlaceholder("{$placeholderPrefix}email", $profile['email']);
    }
    
    /*print '<pre>';
    print_r($profile);
    print '</pre>';*/
}