{* Получаем товары*}
{processor action="web/catalog/products/getdata" ns="modxsite" params="limit=`6`&getPage=`1`" assign=result}

{* Набиваем через единый шаблон листинга *}
{include file="shop/catalog/list/fetch.tpl"}