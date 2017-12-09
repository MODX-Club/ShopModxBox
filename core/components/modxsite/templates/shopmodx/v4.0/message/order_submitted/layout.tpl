{extends file="message/layout.tpl"}

{block name=body}
    
    <h3 style="margin: 20px 0 50px;">
        Номер заказа: <strong>{$order.id}</strong>
    </h3> 
    
    {$smarty.block.child}
    
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
                    <td>{$product.price|number_format:2:",":" "} {$product.currency_code}</td>
                    <td>{$summ = $product.quantity * $product.price}{$summ|number_format:2:",":" "} {$product.currency_code}</td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    
    <br />
    <br />
    
    <strong>Итого: </strong> {if $order_data.discount}<s>{((float)$order_data.original_sum)|number_format:2:".":" "}</s> {$product.currency_code}<br /><strong>С учетом скидки {$order_data.discount}%: </strong>{/if} {((float)$order_data.sum)|number_format:2:".":" "} {$product.currency_code}


{/block}

