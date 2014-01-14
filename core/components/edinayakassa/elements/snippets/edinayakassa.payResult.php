<?php
$scriptProperties = array_merge((array)$scriptProperties, (array)$_POST);

$namespace = 'edinayakassa';
if(
    !$response = $modx->runProcessor(
        'edinayakassa/web/payments/create',
        $scriptProperties, 
        array(
            'processors_path' => $modx->getObject('modNamespace', $namespace)->getCorePath().'processors/',
        )
    )
    OR $response->isError()
){
    // return ($msg = $response->getMessage()) ? $msg :"Ошибка выполнения запроса";
    return 'WMI_RESULT=RETRY&WMI_DESCRIPTION=Error';
}
 
// return $response->getMessage();
return 'WMI_RESULT=OK&WMI_DESCRIPTION=Order successfully processed';