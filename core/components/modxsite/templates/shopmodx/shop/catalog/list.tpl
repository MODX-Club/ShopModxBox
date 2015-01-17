{* Получаем товары*}
{extends "common/list/list.tpl"}


{block name=params append}

    {$params = array_merge((array)$params, [
        "limit"     => 6,
        "cache"     => 1,
        "sort"      => "modResource.id"
    ])}
    
    {$processor = "web/catalog/products/getdata"}
      
    {$no_records_error = "Товары не были получены"}
    
    {$outer_tpl = "shop/catalog/list/fetch.tpl"}
    
{/block}
 
