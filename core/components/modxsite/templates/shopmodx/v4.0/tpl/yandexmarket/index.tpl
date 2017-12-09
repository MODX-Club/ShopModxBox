<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">

{*
    Выгрузка в Яндекс.Маркет
    
    Внимание!!!
    Строго соблюдайте очередность XML-параметров. Это требование Яндекс.Маркета
*}

{processor action="web/yandexmarket/products/getdata" ns="modxsite" assign=result}{config name="site_url" assign=site_url}

<yml_catalog date="{date('Y-m-d H:i')}">
    <shop>
        <name>{config name="site_name"}</name>
        <company>{config name="site_name"}</company>
        <url>{$site_url}</url>
        <platform>{config name="settings_version"}</platform>
        <version>{config name="emailsender"}</version>
        <agency>MODX-клуб</agency>
        <email>info@modxclub.ru</email>

        
        {* Валюты *}
        <currencies>
            {processor action="web/currencies/getdata" ns="modxsite" params="limit=`0`" assign=currencies_result}
            {foreach $currencies_result.object as $currency}
                <currency id="{$currency.pagetitle}" rate="{$currency.tvs.exchange_rate.value|default:1}"/>
            {/foreach}
        </currencies>
        
        {* Категории *}
        <categories>
            {* Получаем системную настройку корневого раздела каталога *}
            {config name="yandexmarket.catalog_root" assign=catalog_root}
            
            {* Пытаемся получить документ каталога*}
            {assign var=catalog_root_doc value=$modx->getObject('modResource', $catalog_root)}
            {if $catalog_root_doc}
                <category id="{$catalog_root_doc->id}">{$catalog_root_doc->pagetitle}</category>
                
                {* Ищем все дочерние разделы*}
                {processor action="web/catalog/category/getdata" ns="modxsite" assign=categories_result}
                {foreach $categories_result.object as $category}
                    <category id="{$category.id}" parentId="{$category.parent}">{$category.pagetitle}</category>
                {/foreach}
            {/if}
        </categories>


        {* Товары *}
        <offers>
            {foreach $result.object as $object}
                <offer id="{$object.object_id}" available="true">
                    <url>{$object.url}</url>
                    <price>{$object.sm_price}</price>
                    <currencyId>{$object.currency_code}</currencyId>
                    <categoryId>{$object.parent}</categoryId>
                    <picture>{$object.image}</picture>
                    {*
                        Можно купить в магазине
                        <store>false</store>
                    *}
                    {*
                        Есть самовывоз
                        <pickup>true</pickup>
                    *}
                    {*
                        Есть доставка
                        <delivery>true</delivery>
                    *}
                    {*
                        Стоимость доставки в своем регионе
                        <local_delivery_cost>500</local_delivery_cost>
                    *}
                    <name>{$object.pagetitle}</name>
                    {*
                        Производитель
                        <vendor>WINDSOR</vendor>
                    *}
                    <description>{$object.description}</description>
                    {*
                        Произвольные параметры
                        <param name="Название">Значение</param>
                        <param name="Название">Значение</param>
                        <param name="Название">Значение</param>
                    *}
                </offer>
            {/foreach}
        </offers>
    </shop>
</yml_catalog>
