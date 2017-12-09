<?php

/*
    Получаем данные всех пользователей
*/

require_once dirname(dirname(dirname(__FILE__))) . '/getdata.class.php';

class modWebSocietyUsersGetdataProcessor extends modWebGetdataProcessor{
    
    public $classKey = 'modUser';
    
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            # "sort"  => "SocietyProfile.createdon",
            "sort"  => "",
            "dir"   => "ASC",
            "showinactive"  => false,
            "showblocked"   => true,
            "cache"             => true,
            'cache_prefix'      => $this->modx->context->key . "/" . get_class($this) . '/getdata/' . $this->modx->user->id. "/", 
        ));
        
        return parent::initialize();
    }
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        $alias = $c->getAlias();
        
        $c->innerJoin('modUserProfile', "Profile");
        $c->leftJoin('SocietyUserProfile', "SocietyProfile");
        
        $where = array(
            // "SocietyProfile.id:!=" => null,
        );
        
        if(!$this->getProperty('showinactive')){
            $where['active'] = 1;
        }
        
        if(!$this->getProperty('showblocked')){
            $where['Profile.blocked'] = 0;
            $c->where(array(
                "Profile.blockeduntil"   => 0,
                "OR:Profile.blockeduntil:<" => time(),
            ));
        }
        
        /*
            Если не указана сортировка, сортируем по рангу
        */
        if(!$this->getProperty('sort')){
            $c->leftJoin('modUserGroupMember', 'admin_group', "admin_group.user_group = 15 AND {$alias}.id = admin_group.member");
            $c->leftJoin('modUserGroupMember', 'zayadly_banschik_group', "zayadly_banschik_group.user_group = 21 AND {$alias}.id = zayadly_banschik_group.member");
            $c->leftJoin('modUserGroupMember', 'predstavitel_group', "predstavitel_group.user_group = 22 AND {$alias}.id = predstavitel_group.member");
            # $c->select(array(
            #     "if(admin_group.id is not null, 100, 
            #         if(zayadly_banschik_group.id is not null, 90, 
            #             0
            #         )
            #     )as rankk",
            # ));
            # $c->sortby('1', 'desc');
            
            $c->sortby("if(admin_group.id is not null, 100, 
                    if(zayadly_banschik_group.id is not null, 90, 
                        if(predstavitel_group.id is not null, 80, 
                            0
                        )
                    )
                )", 'desc');
                
            $c->sortby('SocietyProfile.createdon', 'ASC'); 
            
                # $c->prepare();
                # print $c->toSQL();
                # exit;
        }
        # if($this->modx->hasPermission('Debug')){
        #     
        # }
        
        
        if($where){
            $c->where($where);
        }
        
        /*
        
        $c->prepare();
        
        print $c->toSQL();
        
        exit;
        
        */
        return $c;
    }
    
    
    public function setSelection(xPDOQuery $c){
        $c = parent::setSelection($c);
        
        $c->select(array(
            "Profile.fullname",
            "Profile.blocked",
            "Profile.blockeduntil",
            "Profile.photo",
            "Profile.email",
            "SocietyProfile.createdon as regdate",
        ));
        
        return $c;
    }
    
    public function afterIteration( array $list){
        $list = parent::afterIteration($list);
        
        $avatars_url = $this->getSourcePath(15);
         
        foreach($list as & $l){
            if(empty($l['photo'])){
                $l['photo'] = "anonymous.jpg";
            }
            $l['photo'] = $avatars_url . $l['photo'];
        }
        
        return $list;
    }
    
}


return 'modWebSocietyUsersGetdataProcessor';
