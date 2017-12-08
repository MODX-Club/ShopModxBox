<?php

require_once dirname(dirname(dirname(__FILE__))).'/site/web/resources/getdata.class.php';

class modWebResourcesGetdataProcessor extends modSiteWebResourcesGetdataProcessor{
    

    public function initialize()
    {

        $this->setDefaultProperties(array(
            'format' => "json",
        ));

        return parent::initialize();
    }

}

return 'modWebResourcesGetdataProcessor';