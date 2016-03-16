<?php

require_once MODX_PROCESSORS_PATH . 'system/phpthumb.class.php';

# class modSiteWebImagesThumbProcessor extends modProcessor{
class modSiteWebImagesThumbProcessor extends modSystemPhpThumbProcessor{

    
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            # Типы самбов для изображений
            # Например {"small":"&q=90&f=jpg&w=280&h=210","medium":"&q=90&f=jpg&w=600&h=375","big":"&q=90&f=jpg&w=1000&h=750"}
            "types" => $this->modx->getOption('modxsite.thumb_types', null, '{}'),
            
            # Сниппет обработки самбов
            # Если не заполнен, используется родной phpThumb MODX-а.
            "thumb_snippet" => $this->modx->getOption('modxsite.thumb_snippet', null),
        ));
        
        if(
            $types = $this->getProperty('types') 
            AND !is_array($types) 
        ){
            $this->setProperty('types', json_decode($types, 1));
        }
        
        return parent::initialize();
    }
    
    
    public function process(){
        
        if(!$types = (array)$this->getProperty('types')){
            
            return $this->failure("Не были получены типы изображений");
        }
        
        else if(!$type = $this->getProperty('type')){
            
            return $this->failure("Не указан тип изображения");
        }
        
        else if(!array_key_exists($type, $types)){
            
            return $this->failure("Указан неверный тип изображения");
        }
        else{
            $options = $types[$type];
            parse_str($options, $ptOptions);
            $this->setProperties($ptOptions);
        }
        
        
        if(!$src = $this->getProperty('src')){
            
            return $this->failure("Не был указан путь к изображению");
        }
        
        else{
            
            // else
            if($snippet = $this->getProperty('thumb_snippet')){
                
                $result = $this->modx->runSnippet($snippet, array(
                    'input' => $src,
                    "options"   => $options,
                ));
                
                if(
                    $result
                    AND $file = MODX_BASE_PATH . $result
                    AND file_exists($file)
                ){
                    
                    $basename = $this->getBasename();
                    
                    $date = gmdate("D, d M Y H:i:s \G\M\T");
                    $LastModified = gmdate("D, d M Y H:i:s \G\M\T", filemtime($file));
                    $Expires = gmdate("D, d M Y H:i:s \G\M\T", time() + (60 * 60 * 24 * 31));
                    
                    $content_type = mime_content_type($file);
                    
                    header("Content-Type: {$content_type}");
                    header('Content-Length: ' . filesize($file));
                    # header('Cache-Control: public');
                    # # header('Cache-Control: private');
                    header('Pragma: cache');
                    # # header('Pragma: ');
                    # # header("Date: {$date}");
                    # header("Date: ");
                    header("Content-Disposition: inline; filename={$basename}");
                    header("Last-Modified: {$LastModified}");
                    # header("Expires: {$Expires}");
                    # header("X-Powered-By: " . null);
                    readfile($file);
                    exit;
                }
                else{
                    
                    return $this->failure('Не был получен файл');
                }
            }
            else {
                
            }
            
        }
        
        
        # print '<pre>';
        # 
        # print_r($this->properties);
        # exit;
        
        
        # header('Status: 404 Not found');
        # return $this->failure($error);
        
        
        return parent::process();
    }
    
    
    protected function getBasename(){
        return basename($this->getProperty('src'));
    }
    
    
    public function failure($msg = '',$object = null) {
        header('Status: 404 Not found');
        # $this->modx->setLogTarget('HTML');
        $this->modx->log(1, $msg, '', __CLASS__, '', __LINE__);
        return parent::failure($msg,$object);
    }

}


return 'modSiteWebImagesThumbProcessor';
