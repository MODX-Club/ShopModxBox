{*
    Base template for listing
*}
 

{block name=params}
    
    {$params = [
        "limit"     => 12,
        "page"      => (int)$smarty.get.page,
        "cache"     => 1
    ]}
    
    {$processor = "web/getdata"}
    {$ns = "modxsite"}
    
    {$outer_tpl = "common/list/outer.tpl"}
    {$inner_tpl = "common/list/inner.tpl"}
    
    {$show_no_records_error = true}
    {$no_records_error = "Записи не были получены"}
    
{/block}

{block name=request}
    {processor action=$processor ns=$ns params=$params assign=result}
{/block}

{block name=fetch}

    {* Набиваем через единый шаблон листинга *}
    {include $outer_tpl}
    
{/block}

{block name=pagination}
    {include "common/pagination/pagination.tpl"}
{/block}
 




