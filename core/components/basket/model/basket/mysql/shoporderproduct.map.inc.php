<?php
$xpdo_meta_map['ShopOrderProduct']= array (
  'package' => 'Shop',
  'version' => '1.1',
  'table' => 'shop_order_products',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'order_id' => NULL,
    'product_id' => NULL,
    'parent' => NULL,
    'quantity' => 0,
    'price' => NULL,
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
      'index' => 'pk',
    ),
    'product_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
    ),
    'parent' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'quantity' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'price' => 
    array (
      'dbtype' => 'double',
      'phptype' => 'float',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'order_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'product_id' => 
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
            'owner'     => 'foreigh',
            'local'     => 'order_id',
            'foreign'   => 'id',
            'cardinality' => 'one',
        ),  
        'ProductResource' => array(
            'class' => 'modResource',
            'owner'     => 'foreigh',
            'local'     => 'product_id',
            'foreign'   => 'id',
            'cardinality' => 'one',
        ),  
    ),
);
