<?php

require_once MODX_CORE_PATH . 'components/billing/processors/mgr/orders/grid/getlist.class.php';

class ShopOrdersGetlistProcessor extends modMgrOrdersGridGetlistProcessor{
}

return 'ShopOrdersGetlistProcessor';



class ShopOrdersGetlistProcessor_depricated extends modObjectGetlistProcessor{
    public $classKey = 'ShopOrder';
    public $defaultSortField = 'id';
    
    public function initialize(){
        $this->setDefaultProperties(array(
            'grid'  => true,
            "sort"  => "{$this->classKey}.id",
            "dir"   => "desc",
        ));
        return parent::initialize();
    }
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c->innerJoin('ShopOrderStatus', 'Status');
        $c->innerJoin('modUser', 'CreatedBy');
        $c->innerJoin('modUserProfile', 'CreatedByProfile', 'CreatedBy.id=CreatedByProfile.internalKey');
        $c->leftJoin('modUser', 'ModifiedBy');
        $c->leftJoin('modUserProfile', 'ModifiedByProfile', 'ModifiedBy.id=ModifiedByProfile.internalKey');
        $c->leftJoin('modUser', 'Manager');
        $c->leftJoin('modUserProfile', 'ManagerProfile', 'Manager.id=ManagerProfile.internalKey');
        $c->select(array(
            "Status.status as status_str",
            "CreatedByProfile.fullname  as CreatedBy_fullname",
            "if(CreatedByProfile.mobilephone != '', CreatedByProfile.mobilephone, CreatedByProfile.phone)     as CreatedBy_phone",
            "CreatedByProfile.email     as CreatedBy_email",
            "ManagerProfile.fullname    as Manager_fullname",
            "{$this->classKey}.*",
            "{$this->classKey}.id as order_id",
        ));
        
        
        // Проверяем право видеть все заявки
        if(!$this->modx->hasPermission('view_all_orders')){
            $c->andCondition(array(
                'status' => 1,
                'OR:manager:=' => $this->modx->user->id,
            ));
        }
        
        if($id = $this->getProperty('order_id')){
            $c->where(array(
                "{$this->classKey}.id" => $id,
            ));
        }
        
        /*$c->prepare();
        print $c->toSQL();*/
        return $c;
    }
    
    public function prepareRow($object){
        // print '<pre>';
        $row = parent::prepareRow($object);
        
        $menu = array();
        
        // Если статус Новый, то предлагаем принять в работу
        switch($row['status']){
            case '1':   // Новый
                $menu[] = array(
                    'text' => 'Принять заказ',
                    'handler'   => 'this.takeOrder',
                );
                break;
            default:
                $menu[] = array(
                    'text' => 'Изменить статус',
                    'handler'   => 'this.updateOrderStatus',
                );;
        }
        
        if($this->modx->hasPermission('delete_order')){
            $menu[] = array(
                'text' => 'Удалить заявку',
                'handler'   => 'this.deleteOrder',
            );
        }
        
        
        $row['menu'] = $menu;
        
        /*print_R($row);
        
        exit;*/
        return $row;
    }
    
    
    public function outputArray(array $array, $count = false){
        if(!$this->getProperty('grid')){
            return array(
                'success'   => true,
                'message'   => '',
                'total'     => $count,
                'object'    => $array,
            );
        }
        // else
        return parent::outputArray($array, $count);
    } 
}

return 'ShopOrdersGetlistProcessor';