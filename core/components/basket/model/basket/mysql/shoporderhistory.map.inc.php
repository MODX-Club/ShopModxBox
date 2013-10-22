<?php
$xpdo_meta_map['ShopOrderHistory']= array (
  'package' => 'Shop',
  'version' => '1.1',
  'extends' => 'ShopOrder',
  'table' => 'shop_orders_history',
  'fields' => 
  array (
    'order_id' => NULL,
  ),
  'fieldMeta' => 
  array (
    'order_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'order_id' => 
    array (
      'alias' => 'order_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'order_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
    'aggregates' => array(
        'Order' => array(
            'class' => 'ShopOrder',
            'owner'     => 'foreign',
            'local'     => 'order_id',
            'foreign'   => 'id',
            'cardinality' => 'one',
        ),
    ),
);
