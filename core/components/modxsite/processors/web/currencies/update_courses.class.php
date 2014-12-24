<?php

/*
    Обновляем курсы валют
*/

class modWebCurrenciesUpdatecoursesProcessor extends modProcessor{
    
    protected $currencies = array();
    
    
    public function initialize(){
        
        $this->setProperties(array(
            "exchange_rate_tv"  => (int)$this->modx->getOption('shopmodx.exchange_rate_tv'),        // TV-ка, куда будет писаться курс
        ));
        
        if(!$this->getProperty('exchange_rate_tv')){
            $error = "Не указан ID TV-параметра курса валют";
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[- ". __CLASS__ ." -] {$error}");
            return $error;
        }
        
        return parent::initialize() && !$this->hasErrors();
    }
    
    
    public function process(){
           
        $date_req = date('d/m/Y');
        $url = "http://www.cbr.ru/scripts/XML_daily.asp?date_req={$date_req}";
 
        if(!$xml = simplexml_load_file($url)){
            $error = "Не удалось получить курсы валют";
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[- ".__CLASS__." -] {$error}");
            return $this->failure($error);
        }
         
        //else
        
        // Получаем документы валют 
        
        $currenciesQuery = $this->prepareGetCurrenciesQuery();
        
        foreach($this->modx->getIterator('modResource', $currenciesQuery) as $currency){
            $this->currencies[$currency->pagetitle] = $currency;
        }
        
        if(!$this->currencies){
            $error = "Не удалось получить документы валют";
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[- ".__CLASS__." -] {$error}");
            return $this->failure($error);
        }
        
         
        
        # foreach($currencies as $k => $currency){
        #     print "\n$k";
        # }
        
        # exit;
        
        foreach($xml->Valute as $item){
            
            # print get_class($v);
            # 
            # exit;
            $this->update_course($item);
            # if($v->CharCode == 'USD'){
            #     if($course = round((float)str_replace(",", ".", $v->Value), 2)){
            #         $this->update_course(772, $course);
            #     }
            # }
            # else if($v->CharCode == 'EUR'){
            #     if($course = round((float)str_replace(",", ".", $v->Value), 2)){
            #         $this->update_course(773, $course);
            #     }
            # }
        }
        
        // Очищаем кеш
        $this->modx->cacheManager->refresh();
        
        return $this->success("Курсы успешно обновлены");
    }
    
    
    protected function & prepareGetCurrenciesQuery(){
        $where = array(
            "class_key" => "ShopmodxResourceCurrency",
        );
        
        $q = $this->modx->newQuery('modResource');
        
        // Исключаем валюту по умолчанию
        if($default_currency_id = (int)$this->modx->getOption('shopmodx.default_currency')){
            $where['id:!='] = $default_currency_id;
        }
        
        $q->where($where);
        # $q->prepare();
        # print $q->toSQL();
        # exit;
        return $q;
    }
    
    
    protected function update_course(SimpleXMLElement & $item){
        $exchange_rate_tv = $this->getProperty('exchange_rate_tv');
        if(
            $code = (string)$item->CharCode
            AND $Nominal = (int)$item->Nominal
            AND !empty($this->currencies[$code])
            AND $course = round((float)str_replace(",", ".", $item->Value) / $Nominal, 2)
        ){
            $this->currencies[$code]->setTVValue($exchange_rate_tv, $course);
        }
        
        # if($doc = $this->modx->getObject('modResource', $id)){
        #     $doc->setTVValue(42, $course);
        # }
        
        return;
    }
    
}


return 'modWebCurrenciesUpdatecoursesProcessor';
