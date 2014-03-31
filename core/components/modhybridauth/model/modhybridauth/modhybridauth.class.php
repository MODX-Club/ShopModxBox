<?php
/*
* http://modxclub.ru/
* (c) Fi1osof
*/

/*
    Classes:
    + modHybridAuth
    + Hybrid_Logger
    + Hybrid_Storage
*/

class modHybridAuth{
    
    public $modx = null;
    
    protected $config = array();
    
    protected $providers_config = array();
    
    
    public function __construct(modX &$modx,array $config = array()){
        $this->modx =& $modx;

        if(!$corePath = $this->modx->getOption('modhybridauth.core_path',$config)){
            $corePath = $this->modx->getOption('core_path').'components/modhybridauth/';
        };

        if(!$assetsPath = $this->modx->getOption('modhybridauth.assets_path',$config)){
            $assetsPath = $this->modx->getOption('assets_path').'components/modhybridauth/';
        };

        if(!$assetsUrl = $this->modx->getOption('modhybridauth.assets_url',$config)){
            $assetsUrl = $this->modx->getOption('assets_url').'components/modhybridauth/';
        };
        
        $connectorUrl = $assetsUrl.'connector.php';
        

        $this->config = array_merge(array(
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl.'css/',
            'jsUrl' => $assetsUrl.'js/',
            'imagesUrl' => $assetsUrl.'images/',
            
            'connectorUrl' => $connectorUrl,

            'corePath' => $corePath,
            'modelPath' => $corePath.'model/',
            'chunksPath' => $corePath.'elements/chunks/',
            'snippetsPath' => $corePath.'elements/snippets/',
            'processorsPath' => $corePath.'processors/',
        ),$config);
        
        
        /*$this->modx->setLogLevel(xPDO::LOG_LEVEL_INFO);
        $this->modx->setLogTarget('HTML');
        */
        
        $providers  = array();
        $key = "hybridauth_providers";
        
        if(!$providersCache = $modx->cacheManager->get($key)){
            if($providersCollection = $modx->getCollection('modHybridAuthProvider')){
                foreach($providersCollection as $provider){
                    $data = $provider->toArray();
                    $providers[$data['name']] = $data;
                }
                $modx->cacheManager->set($key, $providers);
            }
        }
        else{
            $providers = $providersCache;
        }
        
        if(!$base_url = $modx->getOption('modhybridauth.base_url')){
            $base_url = $modx->getOption('site_url'). 'assets/components/modhybridauth/connectors/profile/auth.php';
        }
        
        $this->providers_config = array(
            "base_url" => $base_url, 
            "providers" => $providers,
    	);
        
        $this->modx->loadClass('Hybrid_Auth', null, false, true);
    }
    
    
    /**
     * Get an option value for this instance.
     *
     * @param string $key The option key to retrieve a value for.
     * @param array|null $options An optional array to search for a value in first.
     * @param mixed $default A default value to return if no value is found; null is the default.
     * @return mixed The value of the option or the provided default if it is not set.
     */
    public function getOption($key, $options = null, $default = null) {
        if (is_array($options) && array_key_exists($key, $options)) {
            $value= $options[$key];
        } elseif (array_key_exists($key, $this->config)) {
            $value= $this->config[$key];
        } else {
            $value= $this->modx->getOption($key, null, $default);
        }
        return $value;
    }
    
    public function getProvidersConfig(){
        return $this->providers_config;
    }
}




/**
 * Debugging and Logging manager
 */
class Hybrid_Logger
{

	public static function debug( $message, $object = NULL )
	{
        self::log(xPDO::LOG_LEVEL_DEBUG, $message, $object);
	}

	public static function info( $message, $object = NULL )
	{ 
        self::log(xPDO::LOG_LEVEL_INFO, $message, $object);
	}

	public static function error($message, $object = NULL)
	{
        self::log(xPDO::LOG_LEVEL_ERROR, $message, $object);
	}

	public static function log($level, $message, $object = NULL)
	{
        global $modx;
		$message = "[modHybridAuth] -- " . $_SERVER['REMOTE_ADDR'] . " -- " . $message . " -- " . print_r($object, true); 
        $modx->log($level, $message);
	}
}

/**
 * HybridAuth storage manager
 */
class Hybrid_Storage 
{
    function __construct()
	{

		$this->config( "php_session_id", session_id() );
		$this->config( "version", Hybrid_Auth::$version );
	}

	public function config($key, $value=null) 
	{
		$key = strtolower( $key );  

		if( $value ){
			$_SESSION["HA::CONFIG"][$key] = serialize( $value ); 
		}
		elseif( isset( $_SESSION["HA::CONFIG"][$key] ) ){ 
			return unserialize( $_SESSION["HA::CONFIG"][$key] );  
		}

		return NULL; 
	}

	public function get($key) 
	{
		$key = strtolower( $key );  

		if( isset( $_SESSION["HA::STORE"], $_SESSION["HA::STORE"][$key] ) ){ 
			return unserialize( $_SESSION["HA::STORE"][$key] );  
		}

		return NULL; 
	}

	public function set( $key, $value )
	{
		$key = strtolower( $key ); 

		$_SESSION["HA::STORE"][$key] = serialize( $value ); 
	}

	function clear()
	{ 
		$_SESSION["HA::STORE"] = ARRAY(); 
	} 

	function delete($key)
	{
		$key = strtolower( $key );  

		if( isset( $_SESSION["HA::STORE"], $_SESSION["HA::STORE"][$key] ) ){
		    $f = $_SESSION['HA::STORE'];
		    unset($f[$key]);
		    $_SESSION["HA::STORE"] = $f;
		} 
	}

	function deleteMatch($key)
	{
		$key = strtolower( $key ); 

		if( isset( $_SESSION["HA::STORE"] ) && count( $_SESSION["HA::STORE"] ) ) {
		    $f = $_SESSION['HA::STORE'];
		    foreach( $f as $k => $v ){ 
				if( strstr( $k, $key ) ){
					unset( $f[ $k ] ); 
				}
			}
			$_SESSION["HA::STORE"] = $f;
			
		}
	}

	function getSessionData()
	{
		if( isset( $_SESSION["HA::STORE"] ) ){ 
			return serialize( $_SESSION["HA::STORE"] ); 
		}

		return NULL; 
	}

	function restoreSessionData( $sessiondata = NULL )
	{ 
		$_SESSION["HA::STORE"] = unserialize( $sessiondata );
	} 
}




