<?php

/*
    Base class for get modResources data
*/

require_once dirname(dirname(__FILE__)).'/getdata.class.php';

class modSiteWebResourcesGetdataProcessor extends modSiteWebGetdataProcessor{
 
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'includeTVs'        => true,  
            'sort'              => "{$this->classKey}.menuindex",
            'dir'               => 'ASC',
            'showhidden'        => false,
            'showunpublished'   => false,
            'limit'             => 15,
            'summary'           => false,
            "makeLinks"         => false,   // Создает ссылки. Надо только для modWebLink
            
            
            /*
                Схема ссылки на картинку.  
                - false : Отсутствуе. Будет выдано значение TV-параметра как есть
                - base  : Будет сформировано с учетом УРЛ-а на медиасурс от корня сайта
                - full  : Будет сформирован полный путь, включая http://
                
            */
            'image_url_schema'      => 'base',     
        ));
        
        return parent::initialize();
    }  
    
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c = parent::prepareQueryBeforeCount($c);
        
        $where = array(
            'deleted'   => false,
        );
        
        if(!$this->getProperty('showhidden', false)){
            $where['hidemenu'] = 0;
        }
        
        if(!$this->getProperty('showunpublished', false)){
            $where['published'] = 1;
        }
        
        if($parent = (int)$this->getProperty('parent')){
            $where["parent"] = $parent;
        }
        
        $c->where($where);
        
        return $c;
    }
    
    
    
    protected function setSelection(xPDOQuery $c) {
        $c = parent::setSelection($c);
    
        if($this->getProperty('includeTVs')){
            $c->leftJoin('modTemplateVarResource', 'TemplateVarResources');
            $c->leftJoin('modTemplateVar', 'tv', "tv.id=TemplateVarResources.tmplvarid");
    
            $c->select(array(
                "tv.id as tv_id",
                'tv.name as tv_name',
                "TemplateVarResources.id as tv_value_id",
                "TemplateVarResources.value as tv_value",
            ));
        }
    
        return $c;
    }
    
    
    public function afterIteration(array $list){
        $list = parent::afterIteration($list);
         
        $makeLinks = $this->getProperty('makeLinks', false);
        
        if($makeLinks){
            $this->modx->getParser();
        }
        
        if($summary = $this->getProperty('summary')){
            $properties = $this->getProperties();
        }
        
        switch($this->getProperty('image_url_schema')){
            case 'base':
                $images_base_url = $this->getSourcePath();
                break;
                
            case 'full':
                $images_base_url = $this->modx->getOption('site_url');
                $images_base_url .= preg_replace("/^\/*/", "", $this->getSourcePath());
                break;
                
            default: $images_base_url = '';
        }
        
        foreach($list as & $l){  
            
            // Картинка
            $l['image'] = '';
            if(!empty($l['tvs']['image']['value'])){
                $l['image'] = $images_base_url . $l['tvs']['image']['value'];
            }
            else{
                $l['imageDefault'] = $images_base_url . 'products/No-Photo.jpg';
            }
            
            // Ссылки
            if($makeLinks){
                if($l['class_key'] == "modWebLink"){
                    $l['link'] = $this->makeUrl($l['content']);
                }
                else{
                    $l['link'] = $l['uri'];
                }
            }
            
            // Режем текст
            if($summary){
                $l['summary'] = '';
                $trunc = new truncate_modSiteWebResourcesGetdataProcessor($this->modx, array_merge($properties,array(
                    'resource'  => $l, 
                )));
                if($response = $trunc->run() AND !$response->isError()){
                    $l['summary'] = $response->getResponse();
                } 
            }
        }   
                
        return $list;
    }
    
    public function makeUrl($content) {
        $url = '';
        if (!empty ($content)) {
            if (!is_numeric($content)) {
                $maxIterations= $this->modx->getOption('parser_max_iterations', null, 10);
                $this->modx->parser->processElementTags(null, $content, true, true, '[[', ']]', array(), $maxIterations);
            }
            if (is_numeric($content)) {
                $url = $this->modx->makeUrl(intval($content), '', '', 'full');
            } else {
                $url = $content;
            }
        }
        return $url;
    }    
    
}



class truncate_modSiteWebResourcesGetdataProcessor extends modProcessor{
    
    var $summaryType, $link, $output_charset;
    
    public function initialize(){
        
        if(!$this->getProperty('resource')){
            return 'Не были получены данные ресурса';
        }
        
        $this->setDefaultProperties(array(
            'trunc'         => 1,
            'splitter'      => '<!-- splitter -->',
            'truncLen'      => 300,
            'truncOffset'   => 0,
            'truncsplit'    => '<!-- splitter -->',
            'truncChars'    => true,
            'output_charset'=> $this->modx->getOption('modx_charset'),
            'endTags'       => '',
        ));
        
        $this->output_charset = $this->getProperty('output_charset');
        
        return parent::initialize();
    }
    

    function html_substr($posttext, $minimum_length = 200, $length_offset = 20, $truncChars=false) {

        // $minimum_length:
        // The approximate length you want the concatenated text to be


        // $length_offset:
        // The variation in how long the text can be in this example text
        // length will be between 200 and 200-20=180 characters and the
        // character where the last tag ends

        // Reset tag counter & quote checker
        $tag_counter = 0;
        $quotes_on = FALSE;
        // Check if the text is too long
        if (mb_strlen($posttext, $this->output_charset) > $minimum_length && $truncChars != 1) {

            // Reset the tag_counter and pass through (part of) the entire text
            $c = 0;
            for ($i = 0; $i < mb_strlen($posttext, $this->output_charset); $i++) {
                // Load the current character and the next one
                // if the string has not arrived at the last character
                $current_char = mb_substr($posttext,$i,1, $this->output_charset);
                if ($i < mb_strlen($posttext) - 1) {
                    $next_char = mb_substr($posttext,$i + 1,1, $this->output_charset);
                }
                else {
                    $next_char = "";
                }
                // First check if quotes are on
                if (!$quotes_on) {
                    // Check if it's a tag
                    // On a "<" add 3 if it's an opening tag (like <a href...)
                    // or add only 1 if it's an ending tag (like </a>)
                    if ($current_char == '<') {
                        if ($next_char == '/') {
                            $tag_counter += 1;
                        }
                        else {
                            $tag_counter += 3;
                        }
                    }
                    // Slash signifies an ending (like </a> or ... />)
                    // substract 2
                    if ($current_char == '/' && $tag_counter <> 0) $tag_counter -= 2;
                    // On a ">" substract 1
                    if ($current_char == '>') $tag_counter -= 1;
                    // If quotes are encountered, start ignoring the tags
                    // (for directory slashes)
                    if ($current_char == '"') $quotes_on = TRUE;
                }
                else {
                    // IF quotes are encountered again, turn it back off
                    if ($current_char == '"') $quotes_on = FALSE;
                }

                // Count only the chars outside html tags
                if($tag_counter == 2 || $tag_counter == 0){
                    $c++;
                }

                // Check if the counter has reached the minimum length yet,
                // then wait for the tag_counter to become 0, and chop the string there
                if ($c > $minimum_length - $length_offset && $tag_counter == 0) {
                    $posttext = mb_substr($posttext,0,$i + 1, $this->output_charset);
                    return $posttext;
                }
            }
        }  
        return $this->textTrunc($posttext, $minimum_length + $length_offset);
    }

    function textTrunc($string, $limit, $break=". ") {
            // Original PHP code from The Art of Web: www.the-art-of-web.com
    
        // return with no change if string is shorter than $limit
        if(mb_strlen($string, $this->output_charset) <= $limit) return $string;
    
        $string = mb_substr($string, 0, $limit, $this->output_charset);
        if(false !== ($breakpoint = mb_strrpos($string, $break, 0, $this->output_charset))) {
          $string = mb_substr($string, 0, $breakpoint+1, $this->output_charset);
        }
    
        return $string;
    }

    function closeTags($text) {
        $debug = $this->getProperty('debug', false);
    
        $openPattern = "/<([^\/].*?)>/";
        $closePattern = "/<\/(.*?)>/";
        $endOpenPattern = "/<([^\/].*?)$/";
        $endClosePattern = "/<(\/.*?[^>])$/";
        $endTags = $this->getProperty('endTags');

        preg_match_all($openPattern, $text, $openTags);
        preg_match_all($closePattern, $text, $closeTags);

        if ($debug == 1) {
                print_r($openTags);
                print_r($closeTags);
        }

        $c = 0;
        $loopCounter = count($closeTags[1]); //used to prevent an infinite loop if the html is malformed
        while ($c < count($closeTags[1]) && $loopCounter) {
                $i = 0;
                while ($i < count($openTags[1])) {
                        $tag = trim($openTags[1][$i]);

                        if (mb_strstr($tag, ' ', false, $this->output_charset)) {
                                $tag = mb_substr($tag, 0, mb_strpos($tag, ' ', 0, $this->output_charset), $this->output_charset);
                        }
                        if ($debug == 1) {
                                echo $tag . '==' . $closeTags[1][$c] . "\n";
                        }
                        if ($tag == $closeTags[1][$c]) {
                                $openTags[1][$i] = '';
                                $c++;
                                break;
                        }
                        $i++;
                }
                $loopCounter--;
        }

        $results = $openTags[1];

        if (is_array($results)) {
                $results = array_reverse($results);

                foreach ($results as $tag) {
                        $tag = trim($tag);

                        if (mb_strstr($tag, ' ', false, $this->output_charset)) {
                                $tag = mb_substr($tag, 0, mb_strpos($tag, ' ',0 , $this->output_charset), $this->output_charset);
                        }
                        if (!mb_stristr($tag, 'br', false, $this->output_charset) && !mb_stristr($tag, 'img', false, $this->output_charset) && !empty ($tag)) {
                                $endTags .= '</' . $tag . '>';
                        }
                }
        }
        return $text . $endTags;
    }

    function process() {
        
        $resource = $this->getProperty('resource');
        $trunc = $this->getProperty('trunc');
        $splitter  = $this->getProperty('splitter');
        $truncLen = $this->getProperty('truncLen');
        $truncOffset =  $this->getProperty('truncOffset');
        $truncsplit = $this->getProperty('truncsplit');
        $truncChars = $this->getProperty('truncChars');
        $summary = '';
        
        $this->summaryType = "content";
        $this->link = false;
        $closeTags = true;
        // summary is turned off

        if ((strstr($resource['content'], $splitter)) && $truncsplit) {
                $summary = array ();

                // HTMLarea/XINHA encloses it in paragraph's
                $summary = explode('<p>' . $splitter . '</p>', $resource['content']);

                // For TinyMCE or if it isn't wrapped inside paragraph tags
                $summary = explode($splitter, $summary['0']);

                $summary = $summary['0'];
                $this->link = '[[~' . $resource['id'] . ']]';
                $this->summaryType = "content";
        
                // fall back to the summary text
        } else if (mb_strlen($resource['introtext'], $this->output_charset) > 0) {
                        $summary = $resource['introtext'];
                        $this->link = '[[~' . $resource['id'] . ']]';
                        $this->summaryType = "introtext";
                        $closeTags = false;
                        // fall back to the summary text count of characters
        } else if (mb_strlen($resource['content'], $this->output_charset) > $truncLen && $trunc == 1) {
                        $summary = $this->html_substr($resource['content'], $truncLen, $truncOffset, $truncChars);
                        $this->link = '[[~' . $resource['id'] . ']]';
                        $this->summaryType = "content";
                        // and back to where we started if all else fails (short post)
        } else {
                $summary = $resource['content'];
                $this->summaryType = "content";
                $this->link = false;
        }

        // Post-processing to clean up summaries
        $summary = ($closeTags === true) ? $this->closeTags($summary) : $summary;
        return $summary;
    }
}


return 'modSiteWebResourcesGetdataProcessor';