<?php
$xpdo_meta_map['modHybridAuthProvider']= array (
  'package' => 'modHybridAuth',
  'version' => '1.1',
  'table' => 'modhybridauth_providers',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => NULL,
    'keys' => NULL,
    'scope' => NULL,
    'enabled' => '0',
    'class_key' => 'modHybridAuthProvider',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'index' => 'unique',
    ),
    'keys' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'JSON',
      'null' => false,
    ),
    'scope' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'enabled' => 
    array (
      'dbtype' => 'enum',
      'precision' => '\'1\',\'0\'',
      'phptype' => 'string',
      'null' => false,
      'default' => '0',
    ),
    'class_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => 'modHybridAuthProvider',
    ),
  ),
  'indexes' => 
  array (
    'name' => 
    array (
      'alias' => 'name',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'name' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
    'composites'  => array (
        'SocialProfile' => array (
            'class' => 'modHybridAuthUserProfile',
            'local' => 'id',
            'foreign' => 'provider',
            'cardinality' => 'many',
            'owner' => 'local',
        ),
    ),
);
