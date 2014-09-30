<?php
$scriptProperties = array_merge((array)$scriptProperties, (array)$_REQUEST);

$namespace = 'shopmodxunitpay';
$processor = "{$namespace}/web/payments/create";
if(
    !$response = $modx->runProcessor(
        $processor,
        $scriptProperties, 
        array(
            'processors_path' => $modx->getObject('modNamespace', $namespace)->getCorePath().'processors/',
        )
    )
){
    $modx->log(xPDO::LOG_LEVEL_ERROR, "[- snippet unitpay.payResult -] Error execute processor {$processor}");
    return "ERROR";
}
 
# if($response->isError()){}

return $response->getMessage();