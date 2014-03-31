<?php

$xpdo_meta_map = array (
    'modUser' =>
    array (
        0 => 'modHybridAuthUser',
    ),
);

$this->map['modUser']['composites']['SocialProfile'] = array(
    'class' => 'modHybridAuthUserProfile',
    'local' => 'id',
    'foreign' => 'internalKey',
    'cardinality' => 'many',
    'owner' => 'local',
);