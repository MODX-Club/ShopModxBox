{*
    Оплата
*}
{$modx->regClientCSS("{$template_url}css/robokassa.css")}

{if !$smarty.get.order_id}
    <h3 class="error">Не был указан ID заказа</h3>
{else}
    
    {* Пытаемся получить заказ *}
    {$order_id = $smarty.get.order_id}
    {$params = [
        "order_id"  => $order_id
    ]}
    {processor action="basket/mgr/orders/products/getdata" ns="basket" params=$params assign=result}
    
    {if $result.success && $result.object}
        {$order = $result}
        
        {*
            UnitPay
        *} 
        {include file="{$modx->getOption('core_path')}components/shopmodxunitpay/templates/web/default/shopmodxunitpay/button.tpl" desc="Оплата заказа №{$order_id}" sum=$order.sum editable=false}
         
        
        {*
            Единая Касса
        *}
        {$ok = $modx->smarty->addTemplateDir("{$modx->getOption('core_path')}components/edinayakassa/templates/web/default/")}
        {snippet name="edinayakassa.getButton" params="&WMI_PAYMENT_AMOUNT=`1`&order_id=`{$smarty.get.order_id}`"}
        
        <br /><br />
        
        <h2>Робокасса</h2>
        {snippet name="robokassa.getButton" params="shp_order=`{$smarty.get.order_id}`&out_sum=`{$order.sum}`"}
        
    {else}
        <div class="alert alert-danger">
            Не был получен заказ
        </div>
    {/if} 
    
{/if}
