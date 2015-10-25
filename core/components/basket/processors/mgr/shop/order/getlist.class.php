<?php

require_once MODX_CORE_PATH . 'components/billing/processors/mgr/orders/grid/getlist.class.php';

class ShopOrdersGetlistProcessor extends modMgrOrdersGridGetlistProcessor{
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        $alias = $c->getAlias();
        
        $where = array();
        
        if($status = (int)$this->getProperty('status')){
            $where['status_id'] = $status;
        }
        
        if($contractor = (int)$this->getProperty('contractor')){
            $where['contractor'] = $contractor;
        }
        
        if($date_from = $this->getProperty('date_from')){
            $where['createdon:>='] = date('Y-m-d H:i:s', strtotime($this->getProperty('date_from')));
        }
        
        if($date_from = $this->getProperty('date_till')){
            $where['createdon:<='] = date('Y-m-d H:i:s', strtotime($this->getProperty('date_till')));
        }
                
        if($where){
            $c->where($where);
        }
        
        
        
        if($search = $this->getProperty('search')){
            $word = $this->modx->quote("%{$search}%");
            
            $q = $this->modx->newQuery('OrderProduct');
            $q->innerJoin('ShopmodxProduct', 'Product');
            $q->innerJoin('modResource', 'ResourceProduct', "ResourceProduct.id = Product.resource_id");
            
            $q_alias = $q->getAlias();
            
            $q->select(array(
                "{$q_alias}.order_id",
            ));
            
            $order_id = (int)$search;
            
            $q->where(array(
                "order_id = {$alias}.id 
                AND (order_id = {$order_id}
                    OR ResourceProduct.pagetitle LIKE {$word} 
                    OR ResourceProduct.longtitle LIKE {$word}
                    OR ResourceProduct.content LIKE {$word}
                )",
            ));
            
            $q->prepare();
            $sql = $q->toSQL();
            
            # print $sql;
            
            $conditions = [];
            
            $conditions[] = new xPDOQueryCondition(array(
                'sql' => "ContractorProfile.address LIKE {$word}",
            ));
            
            if($phone = preg_replace('/[^\+0-9\-\(\)]/', '', $search)){
                $phone = $this->modx->quote("%{$phone}%");
                
                $conditions[] = new xPDOQueryCondition(array(
                    'sql' => "REPLACE(ContractorProfile.phone, ' ', '') LIKE {$phone}",
                    'conjunction'   => $conditions ? "OR" : "AND",
                ));
            }
            
            $conditions[] = new xPDOQueryCondition(array(
                'sql' => "EXISTS ({$sql})",
                'conjunction'   => $conditions ? "OR" : "AND",
            ));
            
            $c->query['where'][] = $conditions;
            
            
            # $c->prepare();
            # print $c->toSQL();
        }
        
        
        return $c;
    }
    
}

return 'ShopOrdersGetlistProcessor';
