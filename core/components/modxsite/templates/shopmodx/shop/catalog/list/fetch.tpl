{if $result.success}

    <div class="row-fluid">
        {assign var=i value=1}
        {assign var=total value=count($result.object)}
        
        {foreach $result.object as $object}
            {include file="shop/products/list/layout.tpl"}
            
            {* Если счетчик кратный трем, то добавляем еще один блок *}
            {if $i%3 == 0 && $i != $total}
                </div>
                <div class="row-fluid">
            {/if}
            
            {assign var=i value=$i+1}
            
        {/foreach}
        
    </div>
    
    <div class="row-fluid">
        <div class="span12">        
            
            {ph name="page.nav"}
        
        </div>
    </div>
    
{else}

    <div class="row-fluid">
        <div class="span12">
        
            <div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4>Товары не были получены</h4>
            </div>
            
        </div>
    </div>
    
{/if}