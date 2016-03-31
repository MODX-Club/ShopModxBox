<?php 
/*
    Шаблон письма с данными заказа
*/
// print '<pre>';
ini_set('display_errors', 1);
$modx->switchContext('web');
$modx->setLogLevel(3);
$modx->setLogTarget('HTML');

$modx->invokeEvent('OnHandleRequest');
 
$namespace = 'shopmodx';        // Неймспейс комопонента

// $order_id = 1;// ID заказа
$order_id = $this->modx->shopmodx->getActiveOrderID();// ID текущего активного заказа (если есть не оформленный заказ)

if(!$order = $this->modx->getObject('ShopmodxOrder', $order_id)){
    print "Ошибка! Не был получен объект заказа";
    return;
}

$params = array(  
    "order_id"  => $order->id,    
);

if(!$response = $modx->runProcessor('shopmodx/orders/products/getdata',
$params
, array(
'processors_path' => $modx->getObject('modNamespace', $namespace)->getCorePath().'processors/',
))){
print "Не удалось выполнить процессор";
return;
}
 
$memory = round(memory_get_usage(true)/1024/1024, 4).' Mb';
print "<div>Memory: {$memory}</div>";
$totalTime= (microtime(true) - $modx->startTime);
$queryTime= $modx->queryTime;
$queryTime= sprintf("%2.4f s", $queryTime);
$queries= isset ($modx->executedQueries) ? $modx->executedQueries : 0;
$totalTime= sprintf("%2.4f s", $totalTime);
$phpTime= $totalTime - $queryTime;
$phpTime= sprintf("%2.4f s", $phpTime);
print "<div>TotalTime: {$totalTime}</div>";

// Здесь можно вывести данные заказа в массиве
// print_r($response->getResponse());

// $objects = $response->getObject();
// foreach($objects as $object){
// }

$properties = array_merge((array)$params, $order->toArray());

$modx->smarty->assign('properties', $properties);
$modx->smarty->assign('order', $order->toArray());
$modx->smarty->assign('order_data', $response->getResponse());


// Получаем профиль заказчика
if(
    $contractor = $order->Contractor
    AND $profile = $contractor->Profile
){
    $this->modx->smarty->assign('Contractor', $contractor->toArray());
    $this->modx->smarty->assign('ContractorProfile', $profile->toArray());
    
}


// print $modx->smarty->fetch('message/order_submitted/layout.tpl');        // Базовый шаблон письма
// print $modx->smarty->fetch('message/order_submitted/contractor.tpl');    // Для клиента
print $modx->smarty->fetch('message/order_submitted/manager.tpl');          // Для менеджера