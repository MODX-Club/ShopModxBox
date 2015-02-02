 
{if $result.success && $result.object && $result.total > $result.count}

    {$page = $result.page|default:1}
    {$limit = $result.limit|default:10}
    {$total = $result.total}
    
    {$get_params = $get_params|default:$smarty.get}
    
    {$params = []}
    {foreach $get_params as $k => $get_param}
        {if !in_array($k, ['q', 'page'])}
            {$params[$k] = $get_param}
        {/if}
    {/foreach}  
    
    {if !$page_url}
        {$id = $id|default:$modx->resource->id}
        {$page_url = $modx->makeUrl($id)}
    {/if} 
    
    {$pages_limit = $pages_limit|default:5}
    
    {$pages = ceil($total/$limit)}
    
    {$start_page = $page - $pages_limit}
    {if 1 > $start_page}
        {$start_page = 1}
    {/if}  
            
    <div style="text-align:center">
        <ul class="pagination"> 
            
            {$last_page = $page + $pages_limit - 1}
            
            {if $start_page > 1}
                {$get = $params}
                {if $url = http_build_query($get)}
                    {$url = "?{$url}"}
                {/if}
                <li class="control"><a href="{$page_url}{$url}">Первая</a></li>
            {/if}  
            
            {$i = 0}
            {while $pages >= $start_page && $pages_limit * 2 + 1 > $i}
                {$i = $i + 1}
                
                {$get = $params}
                
                {if $start_page != '1'}
                    {$get.page = $start_page}
                {/if} 
                
                {if $url = http_build_query($get)}
                    {$url = "?{$url}"}
                {/if}
                 
                <li class="{if $start_page == $page OR (!$page && $start_page == '1')}active{/if}"><a href="{$page_url}{$url}">{$start_page}</a></li>
                 
                {$start_page = $start_page + 1}
            
            {/while}
             
            
            {if $pages >= $start_page}
                {$get = $params}
                {$get.page = $pages}
                {$url = http_build_query($get)}
                {$url = "?{$url}"}
                <li class="control"><a href="{$page_url}{$url}">Последняя</a></li>
            {/if} 
        </ul>
    </div>
{/if}
