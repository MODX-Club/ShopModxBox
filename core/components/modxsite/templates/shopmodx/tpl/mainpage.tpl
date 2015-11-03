{* Шаблон главной страницы. Расширяет шаблон layout.tpl *}
{extends file="layout.tpl"}


{block name="title"}{$site_name}{/block}

{* Переопределяем блок контента *}
{block name=content append}

    <h2>Новинки</h2> 
    
    {processor action="web/catalog/products/hot/getdata" ns="modxsite" params="limit=`6`" assign=result}
    
    {* Набиваем через единый шаблон листинга *}
    {include file="shop/catalog/list/fetch.tpl"}
     
{/block}
