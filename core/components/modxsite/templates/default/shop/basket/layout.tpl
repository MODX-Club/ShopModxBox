{* Базовый шаблон для вывода корзины *}
{* depricated processor action="web/basket/entity/getdata" ns="basket" assign=basket_result*}

{processor action="basket/web/orders/products/getdata" ns="basket" assign=basket_result}

{*<pre>{print_R($basket_result)}</pre>*}

{if $basket_result.success && $basket_result.object}
    {assign var=total value=$basket_result.object.total}
    {assign var=sum value=$basket_result.object.sum}
{/if}

{block name=basket_content}{/block}