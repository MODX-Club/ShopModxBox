<?php

/*
    Получаем данные всех пользователей
    Внимание! Этот процессор не выполняет проверки прав.
*/

require_once __DIR__ . '/../getdata.class.php';

class modSiteWebUsersGetdataProcessor extends modSiteWebGetdataProcessor{

    public $classKey = 'modUser';


    public function initialize()
    {

        $this->setDefaultProperties(array(
            "showinactive" => false,
            "showblocked" => false,
        ));

        return parent::initialize();
    }

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c = parent::prepareQueryBeforeCount($c);

        $c->innerJoin('modUserProfile', "Profile");

        $where = array();

        if (!$this->getProperty('showinactive')) {
            $where['active'] = 1;
        }

        if (!$this->getProperty('showblocked')) {
            $where['Profile.blocked'] = 0;
            $c->where(array(
                "Profile.blockeduntil" => 0,
                "OR:Profile.blockeduntil:<" => time(),
            ));
        }

        if ($query = $this->getProperty('query')) {
            $c->where(array(
                "username" => $query,
                "OR:Profile.email:=" => $query,
            ));
        }


        if ($where) {
            $c->where($where);
        }

        return $c;
    }


    public function setSelection(xPDOQuery $c)
    {
        $c = parent::setSelection($c);

        $c->select(array(
            "Profile.fullname",
            "Profile.blocked",
            "Profile.blockeduntil",
            "Profile.photo",
            "Profile.email",
        ));

        return $c;
    }

    public function afterIteration(array $list)
    {
        $list = parent::afterIteration($list);

        $avatars_url = $this->getSourcePath($this->modx->getOption('default_media_source', null, 1));

        $canViewAllData = $this->modx->hasPermission("users.view_all_data");

        foreach ($list as & $l) {
            if (empty(!$l['photo'])) {
                $l['photo'] = $avatars_url . $l['photo'];
            }

            unset(
                $l['password'],
                $l['hash_class'],
                $l['salt'],
                $l['cachepwd'],
                $l['class_key'],
                $l['remote_data'],
                $l['session_stale']
            );

            if (
                $this->modx->user->id != $l['id']
                AND !$canViewAllData
            ) {

                unset(
                    $l['email'],
                    $l['primary_group'],
                    $l['sudo']
                );
            }
        }

        return $list;
    }
}


return 'modSiteWebUsersGetdataProcessor';
