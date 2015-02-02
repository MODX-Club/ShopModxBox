{*
    Расширенный шаблон.
    1. Без заголовка на странице
    2. Без хлебных крошек
*}
{extends file="layout.tpl"}

{*  Убираем вывод заголовка на странице *}
{block name=pagetitle}{/block}

{* Убираем хлебные крошки *}
{block name=Breadcrumbs}{/block}
