<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

/*
* modHybridAuth
* http://modxclub.ru/
* (c) 2013, Fi1osof
*/

/**
 * Hybrid_Endpoint class
 * 
 * Hybrid_Endpoint class provides a simple way to handle the OpenID and OAuth endpoint.
 */
class modHybridAuthWebEndpointProcessor extends modProcessor{
    //public static $request = NULL;
    protected $initDone = FALSE;
    
    
    public function initialize(){
        
        $this->setProperties(array(
            "failure_page" => 107,        
        ));
        
        return parent::initialize();
    }
    

    function __construct(modX & $modx,array $properties = array())
	{
        set_exception_handler(array($this, 'exceptionHandler'));
        
		parent::__construct( $modx, $properties); 
	}

    
    public function exceptionHandler(Exception $e) {
        $code = $e->getCode();
        if ($code < 5) {$level = modX::LOG_LEVEL_ERROR;}
        else {$level = modX::LOG_LEVEL_INFO;}
        
        $this->modx->log($level, '[modHybridAuth] ' . $e->getMessage());
        
        $url = $this->modx->makeUrl($this->getProperty('failure_page'), null, null, 'full');
        $this->modx->sendRedirect($url);
    }   
    

    public function process(){
        $output = '';
        
        if($this->getProperty('hauth_start') || $this->getProperty('hauth_done')){
            try {
        		$storage = new Hybrid_Storage(); 

    			// Check if Hybrid_Auth session already exist
				if ( !$storage->config( "CONFIG" ) ) { 
					header( "HTTP/1.0 404 Not Found" );
                    return "You cannot access this page directly.";
				}

				Hybrid_Auth::initialize( $storage->config( "CONFIG" ) );
			}
			catch ( Exception $e ){
				Hybrid_Logger::error( "Endpoint: Error while trying to init Hybrid_Auth" ); 

				header( "HTTP/1.0 404 Not Found" );
				return "Oophs. Error!" ;
			}
        }
        
		// If openid_policy requested, we return our policy document
        if($this->getProperty('get') == "openid_policy"){
            $output = $this->processOpenidPolicy();
        }

		// If openid_xrds requested, we return our XRDS document
        else if($this->getProperty('get') == "openid_xrds"){
            $output = $this->processOpenidXRDS();
        }

		// If we get a hauth_start
        else if($this->getProperty('hauth_start')){
            $output = $this->processAuthStart();
        }
        
		// Else if hauth.done
        else if($this->getProperty('hauth_done')){
            $output = $this->processAuthDone();
        }
        
		// Else we advertise our XRDS document, something supposed to be done from the Realm URL page
		else {
			$output = $this->processOpenidRealm();
		}
        
        return $output;
    }
 

	/**
	* Process OpenID policy request
	*/
	protected function processOpenidPolicy()
	{
		$output = '<html>
            <head> 
        		<title>OpenID Policy</title>
        	</head>
        	<body>
        		<!--
        			Set here your OpenID Policy,
        		-->
        	</body>
        </html>'; 
		return $output;
	}

	/**
	* Process OpenID XRDS request
	*/
    protected function processOpenidXRDS()
    {
		header("Content-Type: application/xrds+xml");
        
        $template = '<?xml version="1.0" encoding="UTF-8"?>
            <xrds:XRDS  
                xmlns:xrds="xri://$xrds"  
                xmlns:openid="http://openid.net/xmlns/1.0"  
                xmlns="xri://$xrd*($v*2.0)">  
                <XRD>  
                    <Service priority="1">  
                        <Type>http://specs.openid.net/auth/2.0/return_to</Type>  
                        <URI>{RETURN_TO_URL}</URI>  
                    </Service>  
                </XRD>  
            </xrds:XRDS>';
        
        $currentUrl = Hybrid_Auth::getCurrentUrl( false );
        $url = str_replace(
    			array("<", ">", "\"", "'", "&"), array("&lt;", "&gt;", "&quot;", "&apos;", "&amp;"), 
				$currentUrl
			);
        
		$output = str_replace
		(
			"{RETURN_TO_URL}",
			$url,
			file_get_contents($template)
		);
		return $output;
	}
    

	/**
	* Process OpenID realm request
	*/
    protected function processOpenidRealm()
    {
        $template = '<html> 
            <head>
        		<title>HybridAuth Endpoint</title>
        		<meta name="robots" content="NOINDEX, NOFOLLOW">
        		<meta http-equiv="X-XRDS-Location" content="{X_XRDS_LOCATION}" />
        	</head>
        	<body>
        		<h3 style="margin-bottom: 2px;">HybridAuth</h3> 
        		Open Source Social Sign On PHP Library. 
        		<br />
        		<a href="http://hybridauth.sourceforge.net/" style="color:green;text-decoration:none;">hybridauth.sourceforge.net/</a> 
        	</body>
        </html>
        ';
        
		$output = str_replace
		(
			"{X_XRDS_LOCATION}",
			htmlentities( Hybrid_Auth::getCurrentUrl( false ), ENT_QUOTES, 'UTF-8' ) . "?get=openid_xrds&v=" . Hybrid_Auth::$version,
			$template
		); 
        
		return $output;
	}

	/**
	* define:endpoint step 3.
	*/
    
    protected function processAuthStart()
    {

		$provider_id = trim($this->getProperty('hauth_start'));

		# check if page accessed directly
		if( ! Hybrid_Auth::storage()->get( "hauth_session.$provider_id.hauth_endpoint" ) ) {
			Hybrid_Logger::error( "Endpoint: hauth_endpoint parameter is not defined on hauth_start, halt login process!" );

			header( "HTTP/1.0 404 Not Found" );
			return "You cannot access this page directly.";
		}

		# define:hybrid.endpoint.php step 2.
		$hauth = Hybrid_Auth::setup( $provider_id );

		# if REQUESTed hauth_idprovider is wrong, session not created, etc. 
		if( ! $hauth ) {
			Hybrid_Logger::error( "Endpoint: Invalid parameter on hauth_start!" );

			header( "HTTP/1.0 404 Not Found" );
			return "Invalid parameter! Please return to the login page and try again.";
		}

		try {
			Hybrid_Logger::info( "Endpoint: call adapter [{$provider_id}] loginBegin()" );

			$hauth->adapter->loginBegin();
		}
		catch ( Exception $e ) {
			Hybrid_Logger::error( "Exception:" . $e->getMessage(), $e );
			Hybrid_Error::setError( $e->getMessage(), $e->getCode(), $e->getTraceAsString(), $e );

			$hauth->returnToCallbackUrl();
		}

		return '';
	}

	/**
	* define:endpoint step 3.1 and 3.2
	*/
    
    protected function processAuthDone()
    {
		$provider_id = trim($this->getProperty('hauth_done'));

		$hauth = Hybrid_Auth::setup( $provider_id );

		if( ! $hauth ) {
			Hybrid_Logger::error( "Endpoint: Invalid parameter on hauth_done!" ); 

			$hauth->adapter->setUserUnconnected();

			header("HTTP/1.0 404 Not Found"); 
			return "Invalid parameter! Please return to the login page and try again.";
		}

		try {
			Hybrid_Logger::info( "Endpoint: call adapter [{$provider_id}] loginFinish() " );

			$hauth->adapter->loginFinish(); 
		}
		catch( Exception $e ){
			Hybrid_Logger::error( "Exception:" . $e->getMessage(), $e );
			Hybrid_Error::setError( $e->getMessage(), $e->getCode(), $e->getTraceAsString(), $e );

			$hauth->adapter->setUserUnconnected();
		}
        
		Hybrid_Logger::info( "Endpoint: job done. retrun to callback url." );
        
        
        // Save profile data in session
        $profile = $hauth->adapter->getUserProfile();
        
        // Try to get user by social profile
        /*$q = $this->modx->newQuery('modUser');
        $q->innerJoin('modUserProfile', 'Profile');
        $q->innerJoin('modHybridAuthUserProfile', 'SocialProfile');
        $q->innerJoin('modHybridAuthProvider', 'Provider', "Provider.id=SocialProfile.provider");
        $q->where(array(
            "SocialProfile.identifier"  => $profile->identifier,
            "Provider.name"     => $provider,
            "modUser.active"    => 1,
            "Profile.blocked"   => 0,
        ));
        $q->limit(1);
        
        if($user = $this->modx->getObject('modUser', $q)){
            $user->addSessionContext($this->modx->context->key);
            $redirectTo = $this->modx->getOption('site_url');
            $this->modx->sendRedirect($redirectTo);
            return;
        }*/
        
        // else
        
        $_SESSION['social_profile'] = array(
            'provider'   => $provider_id,
            'profile'   => $this->modx->error->toArray($profile),
        );
        
        //$q->prepare();
        //$this->modx->log(1, $q->toSQL());
        
        // else
		$hauth->returnToCallbackUrl();
		return '';
	}
}

return 'modHybridAuthWebEndpointProcessor';
