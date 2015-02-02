{* Шаблон товара *}
{extends file="layout.tpl"}

{block name=content}

    {*
        Есть два варианта получения данных товара:
        
        1.  Получить объект продукта через $modx->resource->getOne('Product')
        2.  Через вызов процессора. Второй способ используется чаще, так как в процессоре
            может быть использована более мощная логика, нежели просто получение свойств товара.
            К тому же единый на все процессор гарантирует актуальность данных и логики во всех местах вызова.
        Оба способа представлены здесь: http://modxclub.ru/blog/dokumentatsiya-dlya-spetsialistov/224.html
    *}
 
    {assign var=params value=[
        "where"=>["id"=>$modx->resource->id]
        ,"current"  => true
    ]}
    {processor action="web/catalog/products/getdata" ns="modxsite" params=$params assign=result}
    {if $result.success}
        {assign var=object value=$result.object}
    {/if}
     
    {include file="shop/products/page/layout.tpl"}
{/block}