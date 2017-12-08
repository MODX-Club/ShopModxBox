<?php 
print '<pre>';
ini_set('display_errors', 1);
$modx->switchContext('web');
$modx->setLogLevel(3);
$modx->setLogTarget('HTML');
 
$namespace = 'modxsite';        // Неймспейс комопонента

$params = array(  
);

if(!$response = $modx->runProcessor('web/resources/getdata',
$params
, array(
'processors_path' => $modx->getObject('modNamespace', $namespace)->getCorePath().'processors/',
))){
print "Не удалось выполнить процессор";
return;
}
  

print_r($response->getResponse());

// $objects = $response->getObject();
// foreach($objects as $object){
// }