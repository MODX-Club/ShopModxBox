{extends file="layout.tpl"}


{block name=content}
    {processor action="web/catalog/category/products/getdata" ns="modxsite" assign=result}

    {* Набиваем через единый шаблон листинга *}
    {include file="shop/catalog/list/fetch.tpl"}

{/block}