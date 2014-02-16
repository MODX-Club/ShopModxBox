<?php


     
class modxsite{
    public $modx = null;
    
    protected $processorsMap = array();


    function __construct(modX & $modx) {
        $this->modx = & $modx;
        $this->loadProcessor('modProcessor');
    }
    
    /*
     * @$fqn filename with separator like web.resource.create
     * $namespace optional.
     */
    public function loadProcessor($fqn, $namespace = ''){
        $class = null;
        $key = "{$fqn}|{$namespace}";
        if(!empty($this->processorsMap[$key])){
            $class = $this->processorsMap[$key];
        }
        else{
            if($namespace){
                if($ns = $this->modx->getObject('modNamespace', $namespace) AND $path = $ns->getCorePath()){
                    $class = $this->loadClass($fqn, "{$path}processors/", true, true);
                }
            }
            else{
                $class = $this->loadClass($fqn, '', false, true);
            }
            if($class){
                $this->processorsMap[$key] = $class;
            }
        }
        return $class;
    }
    
    public function loadClass($fqn, $path= '', $ignorePkg= false, $transient= false) {
        if (empty($fqn)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "No class specified for loadClass");
            return false;
        }
        if (!$transient) {
            $typePos= strrpos($fqn, '_' . $this->modx->config['dbtype']);
            if ($typePos !== false) {
                $fqn= substr($fqn, 0, $typePos);
        }
        }
        $pos= strrpos($fqn, '.');
        if ($pos === false) {
            $class= $fqn;
            if ($transient) {
                $fqn= strtolower($class);
            } else {
                $fqn= $this->modx->config['dbtype'] . '.' . strtolower($class);
            }
        } else {
            $class= substr($fqn, $pos +1);
            if ($transient) {
                $fqn= substr($fqn, 0, $pos) . '.' . strtolower($class);
            } else {
                $fqn= substr($fqn, 0, $pos) . '.' . $this->modx->config['dbtype'] . '.' . strtolower($class);
            }
        }
        // check if class exists
        if (!$transient && isset ($this->modx->map[$class])) return $class;
        $included= class_exists($class, false);
        if ($included) {
            if ($transient || (!$transient && isset ($this->modx->map[$class]))) {
                return $class;
            }
        }
        $classname= $class;
        if (!empty($path) || $ignorePkg) {
            $class= $this->_loadClass($class, $fqn, $included, $path, $transient);
        } elseif (isset ($this->modx->packages[$this->modx->package])) {
            $pqn= $this->modx->package . '.' . $fqn;
            if (!$pkgClass= $this->_loadClass($class, $pqn, $included, $this->modx->packages[$this->modx->package]['path'], $transient)) {
                foreach ($this->modx->packages as $pkg => $pkgDef) {
                    if ($pkg === $this->modx->package) continue;
                    $pqn= $pkg . '.' . $fqn;
                    if ($pkgClass= $this->_loadClass($class, $pqn, $included, $pkgDef['path'], $transient)) {
                        break;
                    }
                }
            }
            $class= $pkgClass;
        } else {
            $class= false;
        }
        if ($class === false) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "Could not load class: {$classname} from {$fqn}.");
        }
        return $class;
    }    
    
    
    protected function _loadClass($class, $fqn, $included= false, $path= '', $transient= false) {
        if (empty($path)) $path= XPDO_CORE_PATH;
        if (!$included) {
            /* turn to filesystem path and enforce all lower-case paths and filenames */
            $fqcn= str_replace('.', '/', $fqn) . '.class.php';
            /* include class */
            if (!file_exists($path . $fqcn)) return false;
            if (!$rt= include_once ($path . $fqcn)) {
                $this->modx->log(xPDO::LOG_LEVEL_WARN, "Could not load class: {$class} from {$path}{$fqcn}");
                $class= false;
            }else if(is_string($rt)){$class = $rt;}
        }
        if ($class && !$transient && !isset ($this->modx->map[$class])) {
            $mapfile= strtr($fqn, '.', '/') . '.map.inc.php';
            if (file_exists($path . $mapfile)) {
                $xpdo_meta_map= & $this->modx->map;
                $rt= include ($path . $mapfile);
                if (!$rt || !isset($this->modx->map[$class])) {
                    $this->modx->log(xPDO::LOG_LEVEL_WARN, "Could not load metadata map {$mapfile} for class {$class} from {$fqn}");
                } else {
                    if (!array_key_exists('fieldAliases', $this->modx->map[$class])) {
                        $this->modx->map[$class]['fieldAliases'] = array();
                    }
                }
            }
        }
        return $class;
    }    
}
