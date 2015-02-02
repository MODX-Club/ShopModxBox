{* Базовый шаблон для вывода корзины *}
{* depricated processor action="web/basket/getdata" ns="basket" assign=basket_result*}

{processor action="basket/web/orders/products/getdata" ns="basket" assign=basket_result}
{$modx->error->reset()} 

{if $basket_result.success && $basket_result.object}
    {assign var=total value=$basket_result.quantity}
    {assign var=sum value=$basket_result.sum}
{/if}


{* <pre style="position:absolute;width:50%;left:0;z-index:1000;">
    {print_r($basket_result)}
</pre> *}
 
<div data-smodx-basket="minibasket" class="basket">
    <a href="[[~82]]">{*Корзина*}

    <div data-smodx-data="cost" class="order_data">
        {if $basket_result.success && $basket_result.object}
            <div class="sum"><span class="num">{$total}</span> <span class="text">{$total|spell:"товар":"товара":"товаров"}</span> <span style="white-space:nowrap;"> <span class="cost">{$sum|number_format:0:".":" "}</span> <span class="currency">руб.</span></span></div>
        {else}
            <div class="sum"><span class="num"></span> <span class="text">Корзина пуста</span> <span style="white-space:nowrap;"> <span class="cost"></span> <span class="currency"></span></span></div>
        {/if}
    </div>
    
    {ph name="basket.action.success" assign=success}
    {ph name="basket.action.failure" assign=failure}
    
    {if $failure}
        {block name="error_logger"}    
            <div style="color:red;">{$failure}</div>    
        {/block}
    {/if}
    
    {if $success}
        {block name="error_logger"}    
            <div style="color:green;">{$success}</div>
        {/block}
    {/if}
    </a>    
</div>
  
