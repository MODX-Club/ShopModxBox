{*
    Основаная информация товара.
    Инклюдится в конечных карточках.
*}

{block name=params}
    
    {$options = "&q=100&w=200"}

{/block}


{$image = $object.image|default:$object.imageDefault}

{$src = $modx->runSnippet('phpthumbof', [
    "input" => $image,
    "options"   => $options
])}


{block name=cart}

    <div data-smodx-productcls="listproduct">
        <form action="" method="post">
            <input type="hidden" name="product_id" value="{$object.product_id}"/>
            <input type="hidden" name="action" value="add_product"/>
            <table>
                <tbody>
                    <tr>
                        <td>Наименование: </td>
                        <td><a href="{$object.uri}" title="{$object.pagetitle|@escape}">{$object.pagetitle}</a></td>
                    </tr>
                    <tr>
                        <td>Категория: </td>
                        <td><a href="{$object.category_uri}" title="{$object.category_title|@escape}">{$object.category_title}</a></td>
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
                            <button class="btn btn-success" type="submit">Купить</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>     
    </div>

{/block}
