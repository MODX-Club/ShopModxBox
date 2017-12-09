<?php

/*
    Получаем данные всех пользователей
*/

require_once __DIR__ . '/../../site/web/users/getdata.class.php';

class modWebUsersGetdataProcessor extends modSiteWebUsersGetdataProcessor{
    
    public $classKey = 'modUser';
    
    
    public function initialize(){

        $request_body = file_get_contents('php://input');

        if($request_body AND $data = json_decode($request_body, 1)){
            $this->setProperties($data);
        }
        
        foreach($this->properties as $field => & $value){

            if(!is_scalar($value)){
                continue;
            }

            $v = (string)$value;

            if($v === "null"){
                $value = null;
            }
            else if($v === "true"){
                $value = true;
            }
            else if($v === "false"){
                $value = false;
            }
            else if($v === "NaN"){
                unset($this->properties[$field]);
            }
            else if($v === "undefined"){
                unset($this->properties[$field]);
            }
        }

        
        $this->setDefaultProperties(array(
            "format" => "json",
            // "showinactive" => $this->modx->hasPermission("view_inactive_users"),
            // "showblocked" => $this->modx->hasPermission("view_blocked_users"),
            "showinactive" => true,
            "showblocked" => true,
            "ownProfile"   => false,
            "cache"             => true,
            'cache_prefix'      => $this->modx->context->key . "/" . get_class($this) . '/getdata/' . $this->modx->user->id. "/", 
        ));

        if($this->getProperty("ownProfile")){
            $this->setProperty("cache", false);
        }
        
        return parent::initialize();
    }

    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        $alias = $c->getAlias();
        
        $where = array(
        );

        if($ids = $this->getProperty("ids")){
            
            if(!is_array($ids)){
                $ids = explode(",", $ids);
            }

            $where["{$alias}.id:in"] = $ids;

        }

        
        if($this->getProperty("ownProfile")){

            $where['id'] = $this->modx->user->id;

        }
        
        // Только представители компаний
        if($this->getProperty("delegatesOnly")){
            $where['delegate'] = "1";
        }
        
        // Только представители компаний
        if($this->getProperty("createdByMe")){
            $where['createdby'] = $this->modx->user->id;
        }


        if($where){
            $c->where($where);
        }

        // $c->prepare();
        // print $c->toSQL();

        return $c;
    }

    public function setSelection(xPDOQuery $c)
    {
        $c = parent::setSelection($c);

        $c->select(array(
            "Profile.photo as image",
        ));

        return $c;
    }

    
    public function afterIteration( array $list){
        $list = parent::afterIteration($list);
        
        $avatars_url = $this->getSourcePath(15);
        
        $canViewAllData = $this->modx->hasPermission("canViewAllData");

        $ownProfile = $this->getProperty("ownProfile");

        foreach($list as & $l){
            if(empty($l['image'])){
                $l['image'] = "anonymous.jpg";

            }
            
            if(preg_match('/^lazy\//', $l['image'])){

                $l['image'] = trim('assets/images/' . $l['image'], '/');

            }
            else{
                
                $l['image'] = trim($avatars_url . $l['image'], '/');
            }



            if(!$canViewAllData){

                unset($l['offer']);
                unset($l['delegate']);
                unset($l['createdby']);
                unset($l['offer_date']);
                unset($l['offer_date']);
                unset($l['contract_date']);

            }



            if($ownProfile){

                /*
                 * Получаем информацию по настройкам уведомлений
                 * */
                $l['notices'] = array();
                $q = $this->modx->newQuery("SocietyNoticeType");

                $alias = $q->getAlias();

                $q->leftJoin("SocietyNoticeUser", "NoticeUsers", "SocietyNoticeType.id = NoticeUsers.notice_id AND NoticeUsers.user_id = {$l['id']}");

    //            $q->where(array(
    //                "NoticeUsers.user_id" => $l['id'],
    //            ));

                $q->select(array(
                    "{$alias}.id",
                    "type",
                    "comment",
                    "if(NoticeUsers.active is not null, NoticeUsers.active, 0) as active",
                ));

                $q->sortby("rank", "ASC");

                $s = $q->prepare();

                // print $q->toSQL();

                $s->execute();

                while($row = $s->fetch(PDO::FETCH_ASSOC)){
    //                print_r($row);

                    $l['notices'][] = $row;
                }

            }

        }
        
        return $list;
    }
}


return 'modWebUsersGetdataProcessor';
