{if $result.success && $result.object}

    {foreach $result.object as $object}
        {include file=$inner_tpl}
    {/foreach}
        
{else}
    
    {block name=no_records}
        
        {if $show_no_records_error}
            <div class="alert alert-warning">
                <h4>{$result.message|default:$no_records_error}</h4>
            </div>
        {/if}
    
    {/block}
    
{/if}