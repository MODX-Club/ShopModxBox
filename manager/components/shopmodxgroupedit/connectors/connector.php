<?php

/**
 * Group Edit Connector
 *
 * @package group_edit
 */

if(!isset($location)){
    $location = '';
}
// $_REQUEST['ctx'] = 'katalog';

require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$corePath = $modx->getOption('core_path').'components/shopmodxgroupedit/';

// $modx->switchContext('katalog');

/* handle request */
$modx->request->handleRequest(array(
    'processors_path' => "{$corePath}processors/mgr/",
    'location' => $location
));

