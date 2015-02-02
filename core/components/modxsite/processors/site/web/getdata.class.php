<?php
/*
 * return products data array
 */

require_once dirname(__FILE__).'/getlist.class.php';

class modSiteWebGetdataProcessor extends modSiteWebGetlistProcessor{

    
    
    public function iterate(array $data) {
        $list = $this->beforeIteration($data['results']);
        $list = $this->afterIteration($list);
        return $list;
    } 
    
    
    public function afterIteration(array $list) {
        $_list = parent::afterIteration($list);
        $list = array();
        
        foreach($_list as & $l){
            $l['id'] = $l['object_id'];
            $list[$l['id']] = $l;
        }
        
        return $list;
    }
    
    
    protected function getResults(xPDOQuery & $c){
        $list = array();
        $this->currentIndex = 0;
        if($c->prepare()){
            if($c->stmt->execute()){
                while($row = $c->stmt->fetch(PDO::FETCH_ASSOC)){
                    $object_id = $row['object_id'];
                    if(empty($list[$object_id])){
                        $list[$object_id] = $row;
                        $list[$object_id]['tvs'] = array();
                        $this->currentIndex++;
                    }
                    if(!empty($row['tv_name'])){
                        $list[$object_id]['tvs'][$row['tv_name']] = array(
                            'tv_id'    => $row['tv_id'],
                            'value_id'    => $row['tv_value_id'],
                            'value'    => $row['tv_value'],
                        );
                    }
                }
            }
            else{
                if($c->stmt->errorCode() !== "00000"){
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, __CLASS__);
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($c->stmt->errorInfo(), true));
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, $c->toSQL());
                }
            }
        }
        return $list;
    }
}

return 'modSiteWebGetdataProcessor';
