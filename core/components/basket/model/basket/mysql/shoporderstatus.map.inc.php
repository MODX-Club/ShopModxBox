<?php
$xpdo_meta_map['ShopOrderStatus']= array (
  'package' => 'Shop',
  'version' => '1.1',
  'table' => 'shop_order_statuses',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'status' => NULL,
  ),
  'fieldMeta' => 
  array (
    'status' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'index' => 'unique',
    ),
  ),
  'indexes' => 
  array (
    'status' => 
    array (
      'alias' => 'status',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'status' => 
        array (
          'length' => '50',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => array(
        'Orders' => array(
            'class' => 'ShopOrder',
            'owner'     => 'local',
            'local'     => 'id',
            'foreign'   => 'status',
            'cardinality' => 'many',
        ),
    ),
);
