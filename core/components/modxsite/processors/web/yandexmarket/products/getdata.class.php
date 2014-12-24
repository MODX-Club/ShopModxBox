<?php
/*
    Получаем все данные для выгрузки в маркет
*/

require_once dirname(dirname(dirname(__FILE__))).'/catalog/products/getdata.class.php';

class modWebYandexmarketProductsGetdataProcessor extends modWebCatalogProductsGetdataProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'limit'   => 0, 
            'image_url_schema'  => 'full',
        ));
        
        return parent::initialize();
    }
    
    # public function prepareQueryBeforeCount(xPDOQuery $c) {
    #     $c = parent::prepareQueryBeforeCount($c);
    #     $c->innerJoin('modResource', 'Currency', "Product.sm_currency=Currency.id");
    #     return $c;
    # }
    # 
    # protected function setSelection(xPDOQuery $c) {
    #     $c = parent::setSelection($c);
    #     $c->select(array(
    #         "Currency.pagetitle as currencyId",
    #     ));
    #     return $c;
    # }
    
    // 
    public function afterIteration(array $list){
        $list = parent::afterIteration($list);
        
        $site_url = $this->modx->runSnippet('getSourcePath');
        
        foreach($list as & $l){
            $l['url'] = $this->modx->makeUrl($l['object_id'], '', '', 'full');
        }
        return $list;
    }
}

return 'modWebYandexmarketProductsGetdataProcessor';