<!DOCTYPE html>



<html lang="ru">


    <head>
        
            <meta charset="UTF-8">
            
            <title>Печать заказа</title>
            <!-- base xhtml4 -->
            <base href="{$modx->config.site_url}" />
	        <meta name="robots" content="noindex, nofollow" />
        	  
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
            
            <!-- Optional theme -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
        
            <link rel="stylesheet" href="{$modx->config['modxSmarty.pre_template']}/css/style.css"/>
    </head>

    <body id="print">
        
        <div class="container">
            <div class="text-center">
                <h1 class="h2">Интернет-магазин "{$modx->config.site_name}"</h2>
                <p>{$modx->config.site_url}</p>
                <h3>Заказ №{$order.id}</h3>
            </div>
            
            <p>
                Время оформления заказа: <strong>{$order.createdon}</strong>
            </p>
            <p>
                Емейл: <strong>{$order.contractor_email}</strong>
            </p>
            
            {if $order.contractor_phone}
                <p>
                    Контактный телефон: <strong>{$order.contractor_phone}</strong>
                </p>
            {/if}
            <p>
                Адрес доставки: <strong>{$order.address}</strong>
            </p>
            <p>
                ФИО: <strong>{$order.contractor_fullname}</strong>
            </p>
            
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>
                            №
                        </th>
                        <th>
                            Фото
                        </th>
                        <th>
                            Наименование
                        </th>
                        <th>
                            Кол-во
                        </th>
                        <th>
                            Цена
                        </th>
                        <th>
                            Сумма
                        </th>
                    </tr>
                    {foreach $order_products as $object name=loop}
                        <tr>
                            <td>
                                {$smarty.foreach.loop.iteration}
                            </td>
                            <td>
                                <img src="{$object.image|default:$object.imageDefault}" width="60">
                            </td>
                            <td>
                                {$object.pagetitle}
                            </td>
                            <td>
                                {$object.quantity}
                            </td>
                            <td>
                                {$object.price}
                            </td>
                            <td>
                                {$object.price * $object.quantity}
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
            
            <div class="row">
                <div class="col-xs-6">
                    <p>
                        Доставил:__________________
                    </p>
                    <p>
                        Получил:__________________
                    </p>
                </div>
                <div class="col-xs-6">
                    
                    <div class="text-right">
                        {*
                        <h4>Итого: {if $order.discount}{$order.sum * (100 - $order.discount) / 100} ({$order.discount} % скидка){else}{$order.sum}{/if} рублей</h4>
                        *}
                         
                        
                        <strong>Итого: </strong> {if $order.discount}<s>{$order.sum}</s> руб.<br /><strong>С учетом скидки {$order.discount}%: </strong>{/if} {((float)$order.sum * ((100 - $order.discount) / 100))|number_format:2:".":" "} руб.
                    </div>
                </div>
            </div>
            
            
        </div>
        
    </body>
    <script>
        window.print();
    </script>
</html>