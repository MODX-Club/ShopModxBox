{* 
    Шаблон для вывода товара на собственной странице
*}

{extends file="shop/products/layout.tpl"}

{block name=cart}
    
    <div class="row">
        <div class="col-md-12">
            <a href="{$image}">
                <img src="{$src}" title="{$object.pagetitle|@escape}" align="left"/>
            </a>
        
            {$smarty.block.parent}
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            {$object.content}
        </div>
    </div>
    
{/block}

