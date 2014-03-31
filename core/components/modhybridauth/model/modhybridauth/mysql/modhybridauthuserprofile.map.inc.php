<?php
$xpdo_meta_map['modHybridAuthUserProfile']= array (
  'package' => 'ModHybridAuth',
  'version' => '1.1',
  'table' => 'modhybridauth_user_profile',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'internalKey' => NULL,
    'identifier' => NULL,
    'provider' => NULL,
    'profileURL' => NULL,
    'webSiteURL' => NULL,
    'photoURL' => NULL,
    'displayName' => NULL,
    'description' => NULL,
    'firstName' => NULL,
    'lastName' => NULL,
    'gender' => NULL,
    'language' => NULL,
    'age' => NULL,
    'birthDay' => NULL,
    'birthMonth' => NULL,
    'birthYear' => NULL,
    'email' => NULL,
    'emailVerified' => '0',
    'phone' => NULL,
    'address' => NULL,
    'country' => NULL,
    'region' => NULL,
    'city' => NULL,
    'zip' => NULL,
    'createdon' => 'CURRENT_TIMESTAMP',
    'extended' => NULL,
  ),
  'fieldMeta' => 
  array (
    'internalKey' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'identifier' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'index' => 'index',
    ),
    'provider' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
    'profileURL' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
    'webSiteURL' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
    'photoURL' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
    'displayName' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => true,
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'firstName' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
    ),
    'lastName' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
    ),
    'gender' => 
    array (
      'dbtype' => 'enum',
      'precision' => '\'female\',\'male\'',
      'phptype' => 'string',
      'null' => true,
    ),
    'language' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => true,
    ),
    'age' => 
    array (
      'dbtype' => 'int',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'birthDay' => 
    array (
      'dbtype' => 'int',
      'precision' => '2',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'birthMonth' => 
    array (
      'dbtype' => 'int',
      'precision' => '2',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'birthYear' => 
    array (
      'dbtype' => 'int',
      'precision' => '4',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'email' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
    'emailVerified' => 
    array (
      'dbtype' => 'enum',
      'precision' => '\'0\',\'1\'',
      'phptype' => 'string',
      'null'    => true,
      'default' => '0',
    ),
    'phone' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => true,
    ),
    'address' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
    'country' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
    ),
    'region' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
    ),
    'city' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
    ),
    'zip' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'createdon' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 'CURRENT_TIMESTAMP',
    ),
    'extended' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'JSON',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
    'identifier' => 
    array (
      'alias' => 'identifier',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'identifier' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'provider' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'internalKey' => 
    array (
      'alias' => 'internalKey',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'internalKey' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'provider' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),  
  'aggregates' =>
    array (
        'User' => array (
            'class' => 'modUser',
            'local' => 'internalKey',
            'foreign' => 'id',
            'cardinality' => 'one',
            'owner' => 'foreign',
        ),
        'Provider' => array (
            'class' => 'modHybridAuthProvider',
            'local' => 'provider',
            'foreign' => 'id',
            'cardinality' => 'one',
            'owner' => 'foreign',
        ),
    ),
);
