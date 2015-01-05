{* Получаем товары*}
{$params = [
    "limit" => 6,
    "getPage"   => 1,
    "page"      => (int)$smarty.get.page
]}
{processor action="web/catalog/products/getdata" ns="modxsite" params=$params assign=result}

{* Набиваем через единый шаблон листинга *}
{include file="shop/catalog/list/fetch.tpl"}