{*
    Выводим новости с постраничностью
*}

{assign var=params value=[]}

{processor action="web/news/getdata" ns="modxsite" params=$params assign=result}


{* <pre>
    {print_r($result)}
</pre> *}


{foreach $result.object as $object}
    <div style="margin: 0 0 50px;">
        <h3>{$object.pagetitle}</h3>
        <div>
            {$object.summary}
            
            <p><a href="{$object.uri}" title="{$object.pagetitle}">Читать новость полностью...</a></p>
        </div>
    </div>
{/foreach}


[[+page.nav]]
