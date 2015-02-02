{block name=params}
    {$cols = 2}
    {$row_class = "row"}
{/block}
 

<div class="{$row_class}">
    {assign var=i value=1}
    {assign var=total value=count($result.object)}
    
    {foreach $result.object as $object}
        {include file="shop/products/list/layout.tpl"}
        
        {* Если счетчик кратный трем, то добавляем еще один блок *}
        {if $i%$cols == 0 && $i != $total}
            </div>
            <div class="{$row_class}">
        {/if}
        
        {assign var=i value=$i+1}
        
    {/foreach}
    
</div>