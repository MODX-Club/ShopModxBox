{* 
    Шаблон для вывода карточки товара в списке
*}

{extends file="shop/products/layout.tpl"}


{block name=cart}

    <div class="col-md-{12/$cols}">
        <div class="row">
            <div class="col-md-4">
            
                {*
                    Здесь нам не приходится проверять фото на наличие, так как это выполняется на уровне процессора.
                *}
                <a href="{$image}">
                    <img src="{$src}" title="{$object.pagetitle}" align="left" class="img-responsive"/>
                </a>
                
            </div>   
            <div class="col-md-8">
            
                {$smarty.block.parent}
                
            </div>
        </div>
    </div> 
{/block}

