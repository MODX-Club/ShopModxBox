<?php
$xpdo_meta_map['ShopOrder']= array (
  'package' => 'Shop',
  'version' => '1.1',
  'table' => 'shop_orders',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'order_num' => NULL,
    'status' => NULL,
    'create_date' => NULL,
    'modify_date' => NULL,
    'ip' => NULL,
    'user_agent' => NULL,
    'seo_key' => NULL,
    'manager' => NULL,
    'created_by' => NULL,
    'modified_by' => NULL,
    'order_address' => NULL,
    'comments' => NULL,
  ),
  'fieldMeta' => 
  array (
    'order_num' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'index',
    ),
    'status' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'index',
    ),
    'create_date' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
    ),
    'modify_date' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'ip' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => false,
    ),
    'user_agent' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '1024',
      'phptype' => 'string',
      'null' => false,
    ),
    'seo_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
    ),
    'manager' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'index',
    ),
    'created_by' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'index',
    ),
    'modified_by' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'index',
    ),
    'order_address' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'comments' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'status' => 
    array (
      'alias' => 'status',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'status' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'order_num' => 
    array (
      'alias' => 'order_num',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'order_num' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'created_by' => 
    array (
      'alias' => 'created_by',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'created_by' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'modified_by' => 
    array (
      'alias' => 'modified_by',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'modified_by' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'manager' => 
    array (
      'alias' => 'manager',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'manager' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
  ),
  'composites' => array(
        'Products' => array(
            'class' => 'ShopOrderProduct',
            'owner'     => 'local',
            'local'     => 'id',
            'foreign'   => 'order_id',
            'cardinality' => 'many',
        ),  
        'History' => array(
            'class' => 'ShopOrderHistory',
            'owner'     => 'local',
            'local'     => 'id',
            'foreign'   => 'order_id',
            'cardinality' => 'many',
        ),  
    ),
    'aggregates' => array(
        'Status' => array(
            'class' => 'ShopOrderStatus',
            'owner'     => 'foreign',
            'local'     => 'status',
            'foreign'   => 'id',
            'cardinality' => 'one',
        ),
        'Manager' => array(
            'class' => 'modUser',
            'owner'     => 'foreign',
            'local'     => 'manager',
            'foreign'   => 'id',
            'cardinality' => 'one',
        ),
        'CreatedBy' => array(
            'class' => 'modUser',
            'owner'     => 'foreign',
            'local'     => 'created_by',
            'foreign'   => 'id',
            'cardinality' => 'one',
        ),
        'ModifiedBy' => array(
            'class' => 'modUser',
            'owner'     => 'foreign',
            'local'     => 'modified_by',
            'foreign'   => 'id',
            'cardinality' => 'one',
        ),
    ),
);
