<?php

require_once MODX_CORE_PATH . 'components/shopmodx/processors/shopmodx/orderstatus/getdata.class.php';

class modWebOrdersStatusesGetdataProcessor extends modShopmodxOrderstatusGetdataProcessor{

  public function initialize() {

    $this->setDefaultProperties(array(
      "format"  => "json",
    ));

    return parent::initialize();
    
  }
    
}

return 'modWebOrdersStatusesGetdataProcessor';
