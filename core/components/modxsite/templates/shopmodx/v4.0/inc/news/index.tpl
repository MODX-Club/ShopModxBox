{*
    Выводим новости с постраничностью
*}

{extends "common/list/list.tpl"}


{block name=params append}

    {$params = array_merge((array)$params, [
        "limit" => 5,
        "cache"     => 1 
    ])}
    
    {$processor = "web/news/getdata"} 
    
    {$inner_tpl = "inc/news/inner.tpl"}
    
{/block}
   