{*
    Оплата
*}
{$modx->regClientCSS("{$template_url}css/robokassa.css")}

{if !$smarty.get.order_id}
    <h3 class="error">Не был указан ID заказа</h3>
{else}
    
    {* Пытаемся получить заказ *}
    {assign var=params value=[
        "order_id"  => $smarty.get.order_id
    ]}
    
    {processor action="web/orders/getlist" ns="basket" params=$params assign=result}
    
    {if $result.success && $result.object}
        {assign var=order value=$result.object[0]} 
        
        {snippet name="robokassa.getButton" params="shp_order=`{$smarty.get.order_id}`&out_sum=`{$order.sum}`"}
        
    {else}
        <h3 class="error">Не был получен заказ</h3>
    {/if} 
    
{/if}
