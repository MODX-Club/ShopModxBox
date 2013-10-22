<?php
$xpdo_meta_map = array ();

$this->map['modUser']['composites']['Order'] = array(
    'class' => 'Order',
    'local' => 'id',
    'foreign' => 'contractor',
    'cardinality' => 'many',
    'owner' => 'foreign',
);