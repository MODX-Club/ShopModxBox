<?php

class modHybridAuthWebProfileAuthProcessor extends modProcessor{
    
    public function initialize(){
        
        $this->setProperties(array(
            "failure_page" => 107,        
        ));
        
        return parent::initialize();
    }    
    
    
    public function process(){
                
        $user_profile = array();
        //if( !empty( $_GET["action"] ) && $_GET["action"] == 'auth' && !empty($_GET["service"]) ) {
        if($provider = $this->getProperty('provider', false)) {
            try  {
                $config = $this->modx->modHybridAuth->getProvidersConfig();
                
                $hybridauth = new Hybrid_Auth( $config );
                
                $adapter = $hybridauth->authenticate($provider);
                $user_profile = $adapter->getUserProfile();
            } catch( Exception $e )  {
                $error = "<b>got an error!</b> " . $e->getMessage();
                
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, '[modHybridAuth] ' . $error);
                $url = $this->modx->makeUrl($this->getProperty('failure_page'), null, null, 'full');
                $this->modx->sendRedirect($url);
            }
            
            // Check is loggedin
            if ($this->modx->user->hasSessionContext($this->modx->context->key)) {
                $redirectTo = $this->modx->getOption('site_url'); 
                
                $this->modx->sendRedirect($redirectTo);
                return;
            }
            
            // else
            
            // Try to get user by social profile
            $q = $this->modx->newQuery('modUser');
            $q->innerJoin('modUserProfile', 'Profile');
            $q->innerJoin('modHybridAuthUserProfile', 'SocialProfile');
            $q->innerJoin('modHybridAuthProvider', 'Provider', "Provider.id=SocialProfile.provider");
            $q->where(array(
                "SocialProfile.identifier"  => $user_profile->identifier,
                "Provider.name"     => $provider,
                "modUser.active"    => 1,
                "Profile.blocked"   => 0,
            ));
            $q->limit(1);
            
            //$q->prepare();
            //$this->modx->log(1, $q->toSQL());
            
            if($user = $this->modx->getObject('modUser', $q)){
                $user->addSessionContext($this->modx->context->key);
                $redirectTo = $this->modx->getOption('site_url');
                $this->modx->sendRedirect($redirectTo);
                return;
            }
            
            // else return to redirect
            if($redirect_id = $this->getProperty('redirect_id') AND $redirect_url = $this->modx->makeUrl($redirect_id)){
                $this->modx->sendRedirect($redirect_url);
                return;
            }
        }
        else{
            $response = $this->modx->runProcessor('web/endpoint', 
                $this->getProperties(), 
                array(
                    'processors_path'   => $this->modx->modHybridAuth->getOption('processorsPath'),        
                )
            );
            return $response->getResponse();
        }
        
        return '';
    }
}

return 'modHybridAuthWebProfileAuthProcessor';