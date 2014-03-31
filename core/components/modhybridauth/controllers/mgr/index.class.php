<?php

class ControllersMgrManagerController extends modExtraManagerController{
    protected static $namespace = 'modhybridauth';
    
    function __construct(modX &$modx, $config = array()) {
        parent::__construct($modx, $config);
        $namespace = self::$namespace;
        $this->config['namespace_assets_path'] = $modx->call('modNamespace','translatePath',array(&$modx, $this->config['namespace_assets_path']));
        $this->config['assets'] = 
        $this->config['assets_url'] = 
        $modx->getOption("{$namespace}.manager_url", null, $modx->getOption('manager_url')."components/{$namespace}/");
        $this->config['connector_url'] = $this->config['assets_url'].'connectors/';
    }
    
    public static function getInstance(modX &$modx, $className, array $config = array()) {
        $className = __CLASS__;
        return new $className($modx, $config);
    }
    
    public function getOption($key, $options = null, $default = null, $skipEmpty = false){
        $options = array_merge($this->config, (array)$options);
        return $this->modx->getOption($key, $options, $default, $skipEmpty);
    }

    public function getLanguageTopics() {
        $topics = (array) parent::getLanguageTopics();
        return array_merge($topics, array(
            self::$namespace.":default",    
        ));
    }

    function loadCustomCssJs(){
        parent::loadCustomCssJs();
        
        $assets_url = $this->getOption('assets_url');
        $this->modx->regClientStartupScript($assets_url."js/modHybridAuth.js");
        $this->modx->regClientStartupScript($assets_url."js/widgets/panel.js");
        
        $attrs = $this->modx->user->getAttributes(array(),'', true);
        $policies = array();
        if(!empty($attrs['modAccessContext']['mgr'])){
            foreach($attrs['modAccessContext']['mgr'] as $attr){
                foreach($attr['policy'] as $policy => $value){
                    if(empty($policies[$policy])){
                        $policies[$policy] = $value;
                    }
                }
            }
        }
        
        $this->modx->regClientStartupScript('<script type="text/javascript">
            modHybridAuth.policies = '. $this->modx->toJSON($policies).';
        </script>', true);
        
        return;
    }
    
    public function getTemplatesPaths($coreOnly = false) {
        $paths = parent::getTemplatesPaths($coreOnly);
        $paths[] = $this->getOption('namespace_path')."templates/default/";
        return $paths;
    }
    
    public function getPageTitle(){
        return  $this->modx->lexicon('modhybridauth');
    }
    
    public function getTemplateFile() {
        return 'index.tpl';
    }
}
?>
