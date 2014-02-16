{config name=site_url assign=site_url}

<h3 style="margin: 20px 0 50px;">
    <p>Номер заказа: <strong>{$order.id}</strong></p>
</h3> 


<h3>Список товаров</h3>
<table border="1" width="90%">
    <tbody>
        <tr>
            <th>Фото</th>
            <th>Наименование</th>
            <th>Количество</th>
            <th>Цена</th>
            <th>Сумма</th>
        </tr>
        {foreach $order_data.object as $product}
            <tr>
                <td><img width="80" src="{$site_url}{$product.image|default:$product.imageDefault}" title="{$product.pagetitle}" />&nbsp;</td>
                <td><a href="{$site_url}{$product.uri}">{$product.pagetitle}</a></td>
                <td>{$product.quantity}&nbsp;</td>
                <td>{$product.price|number_format:2:",":" "} руб.</td>
                <td>{$product.quantity * $product.price|number_format:2:",":" "} руб.</td>
            </tr>
        {/foreach}
    </tbody>
</table>
