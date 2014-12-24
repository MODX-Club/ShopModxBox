{*
    Основаная информация товара.
    Инклюдится в конечных карточках.
*}

{if $object.image}
    {$src = $object.image}
{else}
    {$src = $object.imageDefault}
{/if}

{block name=cart}

    <div class="span4">
        <div class="row-fluid">
            <div class="span4">
            
                {*
                    Здесь нам не приходится проверять фото на наличие, так как это выполняется на уровне процессора.
                *}
                <a href="{$object.image}">
                    <img src="{snippet name=phpthumbof  params="input=`{$src}`&options=`w=200`"}" title="{field name=pagetitle}" align="left"/>
                </a>
                
            </div>   
            <div class="span8">
                
                <div data-smodx-productcls="listproduct">
                    <form action="" method="post">
                        <input type="hidden" name="product_id" value="{$object.product_id}"/>
                        <input type="hidden" name="action" value="add_product"/>
                        <table>
                            <tbody>
                                <tr>
                                    <td>Наименование: </td>
                                    <td><a href="{$object.uri}" title="{$object.pagetitle}">{$object.pagetitle}</a></td>
                                </tr>
                                <tr>
                                    <td>Категория: </td>
                                    <td><a href="{$object.category_uri}" title="{$object.category_title}">{$object.category_title}</a></td>
                                </tr>
                                <tr>
                                    <td>Цена: </td>
                                    <td>{$object.sm_price|number_format:2:".":" "} {$object.currency_code}</td>
                                </tr>
                                <tr>
                                    <td>Количество: </td>
                                    <td><input type="text" name="quantity" value="1" style="width: 50px;" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button class="btn" type="submit">Купить</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>     
                </div>
            </div>
        </div>
    </div> 

{/block}
