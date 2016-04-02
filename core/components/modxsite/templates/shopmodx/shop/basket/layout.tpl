{* Базовый шаблон для вывода корзины *}
{* depricated processor action="web/basket/entity/getdata" ns="basket" assign=basket_result*}

{block name=params}

    {$request = $smarty.request}
    {$request = array_diff($request, ['q'  => $request.q])}

    {$params = [
        "limit"     => 0,
        "new_object"    => false
    ]}
    {$processor = "shopmodx/orders/object"}
{/block}

{processor action=$processor ns="shopmodx" params=$params assign=basket_result}

{*<pre>{print_R($basket_result)}</pre>*}

{if $basket_result.success && $basket_result.object}
    {$Order = $basket_result.object}
    {$total = $basket_result.object.total}
    {$sum = $basket_result.object.sum}
{/if}

{block name=basket_content}{/block}
