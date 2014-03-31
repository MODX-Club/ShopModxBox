<?php
$xpdo_meta_map['modHybridAuthUser']= array (
    'package' => 'ModHybridAuth',
    'version' => '1.1',
    'extends' => 'modUser',
    'fields' => 
    array (
    ),
    'fieldMeta' => 
    array (),
    'composites'  => array (
        'SocialProfile' => array (
            'class' => 'modHybridAuthUserProfile',
            'local' => 'id',
            'foreign' => 'internalKey',
            'cardinality' => 'many',
            'owner' => 'local',
        ),
    ),
);
