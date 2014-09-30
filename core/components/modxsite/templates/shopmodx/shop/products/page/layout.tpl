{* 
    Шаблон для вывода товара на собственной странице
*}

{extends file="shop/products/layout.tpl"}

{block name=cart}

    <div class="row-fluid">
        
        {$smarty.block.parent}
        
        {block name=product_content}
            <div class="span8">
                {$object.content}
            </span>
        {/block}
        
    </div>
    
{/block}

