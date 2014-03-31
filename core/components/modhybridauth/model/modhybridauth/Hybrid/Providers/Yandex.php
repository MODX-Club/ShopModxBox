<?php
class Hybrid_Providers_Yandex extends Hybrid_Provider_Model_OAuth2 { 
    
    function initialize() {
        parent::initialize();
        $this->api->api_base_url    = "https://login.yandex.ru/info";
        $this->api->authorize_url   = "https://oauth.yandex.ru/authorize";
        $this->api->token_url       = "https://oauth.yandex.ru/token"; 
        $this->api->sign_token_name = "oauth_token";
    }
    
    function getUserProfile() {
        $response = $this->api->api( "?format=json" ); 
        
        if ( !isset( $response->id ) ) {
          throw new Exception( "User profile request failed! {$this->providerId} returned an invalide response.", 6 );
        }
        
        $this->user->profile->identifier    = (property_exists($response,'id'))?$response->id:"";
        $this->user->profile->firstName     = (property_exists($response,'real_name'))?$response->real_name:"";
        $this->user->profile->displayName   = (property_exists($response,'display_name'))?$response->display_name:"";
        $this->user->profile->photoURL      = 'http://upics.yandex.net/'. $this->user->profile->identifier .'/normal';
        $this->user->profile->gender        = (property_exists($response,'sex'))?$response->sex:""; 
        $this->user->profile->email         = (property_exists($response,'default_email'))?$response->default_email:"";
        $this->user->profile->emailVerified = (property_exists($response,'default_email'))?$response->default_email:"";
        return $this->user->profile;
    }
} 