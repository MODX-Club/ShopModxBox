<?php

$properties = $modx->resource->getOne('Template')->getProperties();

if(!empty($properties['tpl'])){
    $tpl = $properties['tpl'];
}
else{
    $tpl = 'index.tpl';
}

if ($modx->resource->cacheable != '1') {
    $modx->smarty->caching = false;
}

if(!empty($properties['phptemplates.non-cached'])){
    $modx->smarty->compile_check = false;
    $modx->smarty->force_compile = true;
}

return $modx->smarty->fetch("tpl/{$tpl}");
