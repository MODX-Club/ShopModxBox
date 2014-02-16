{*
    Все возможные данные для оформления письма:
    <pre>
        Order. Текущий объект заказа
        {print_r($order)}
        
        OrderData. Полные данные заказа (сумма заказа, товары и т.п.)
        {print_r($order_data)}
        
        Properties. Свойства текущего процессора, включая переданные данные из формы
        {print_r($properties)}
        
        Contractor. Объект контрагента (modUser)
        {print_r($Contractor)}
        
        ContractorProfile. Профиль контрагента (modUserProfile)
        {print_r($ContractorProfile)}
    </pre>
    
    Можно раскомментировать этот блок, и в письме вы получите все данные в структурированном виде.
*}

{config name=site_url assign=site_url}

<h3 style="margin: 20px 0 50px;">
    <p><a href="{config name=site_url}">{config name=site_name}</a>. Новый заказ.</p>
    <p>Номер заказа: <strong>{$order.id}</strong></p>
</h3>

<h4>Тип заказа: <b>{$properties.ordertype}</b></h4>

<p style="font-weight: bold;">Данные покупателя:</p>
<table border="1" width="90%">
    <tbody>  
        <tr>
            <td width="30%">ФИО: </td>
            <td>{$ContractorProfile.fullname}</td>
        </tr>
            
        <tr>
            <td width="30%">Email: </td>
            <td>{$ContractorProfile.email|default:$properties.email}</td>
        </tr>
            
        <tr>
            <td width="30%">Телефон: </td>
            <td>{$ContractorProfile.phone|default:$properties.phone}</td>
        </tr>
            
        <tr>
            <td width="30%">Мобильный телефон: </td>
            <td>{$ContractorProfile.mobilephone|default:$properties.mobilephone}</td>
        </tr>
            
        <tr>
            <td width="30%">Адрес доставки: </td>
            <td>{$properties.address}</td>
        </tr>
          
            
        <tr>
            <td width="30%">Комментарии: </td>
            <td>{$properties.comments}</td>
        </tr> 
        
        
    </tbody>
</table>


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
 
