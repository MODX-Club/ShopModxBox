<?php

require_once MODX_CORE_PATH . 'components/shopmodx/processors/shopmodx/orders/object.class.php';

class modWebOrdersObjectProcessor extends modShopmodxOrdersObjectProcessor{

    public function initialize(){

    	if(!$id = (int)$this->getProperty("id")){
    		return "Не был получен ID заказа";
    	}

    	$this->setProperty("order_id", $id);


        return parent::initialize();
    }


    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);

        $alias = $c->getAlias();

        $c->leftJoin("modUser", "Contractor", "Contractor.id = {$alias}.contractor");
        $c->leftJoin("modUserProfile", "ContractorProfile", "Contractor.id = ContractorProfile.internalKey");

        $c->select(array(
            "ContractorProfile.fullname as contractor_fullname",
            "ContractorProfile.email as contractor_email",
            "ContractorProfile.phone as contractor_phone",
        ));
 

        // $c->prepare();
        // print $c->toSQL();

        return $c;
    }

}

return 'modWebOrdersObjectProcessor';