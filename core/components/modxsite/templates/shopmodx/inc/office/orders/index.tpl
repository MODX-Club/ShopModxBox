{*
    Управление заказами пользователя
*}

{*<style>
    table.orders{
        width: 100%;    
    }
    
    table.orders th
    ,table.orders td{
        padding: 2px 5px;
        border: 1px solid #d4d4d4;
    }
    
    
</style>
*}
{field name=uri assign=uri}


{*
    Обработчик запросов
*}
{if $smarty.request.action}
    {processor action="web/orders/actions/process" ns="basket" params=$smarty.request assign=actions_result}

    <pre>
        {print_r($actions_result)}
    </pre>
{/if}


{*
    Вывод таблицы заказов
*}
{processor action="web/orders/getlist" ns="basket" assign=result}

{*<pre>
    {print_r($result)}
</pre>*}

{if !$result.success}    
    <div class="row-fluid">
        <div class="span12">
        
            <div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4>Ошибка</h4>
                <p><span class="error">Ошибка! {$result.message}</span></p>
            </div>
            
        </div>
    </div>
{else if !$result.object}
    <div class="row-fluid">
        <div class="span12">
        
            <div class="alert alert-block">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <p>У вас нет еще ни одного заказа</p>
            </div>
            
        </div>
    </div>
{else}
    <div class="row-fluid">
        <div class="span12">
        
            <table class="orders table table-bordered">
                <tbody>
                    <tr>
                        <th class="title">№ заказа</th>
                        <th class="title">Количество позиций</th>
                        <th class="title">Количество товаров</th>
                        <th class="title">Дата создания</th>
                        <th class="title">Дата последнего изменения</th>
                        <th class="title">Сумма</th>
                        <th class="title">Статус</th>
                        <th class="title">Действие</th>
                    </tr>
                    
                    {foreach $result.object as $object}
                        <tr class="{if $object.status_id==8}success{else if $object.status_id==7}error{/if}" >
                            <td>{$object.id}</td>
                            <td>{$object.positions}</td>
                            <td>{$object.total}</td>
                            <td>{$object.createdon}</td>
                            <td>{$object.editedon}</td>
                            <td>{$object.sum}</td>
                            <td>{$object.status_str}</td>
                            <td>
                                {foreach $object.menu as $menu}
                                    <span class="action {$menu.handler}">
                                        <a href="{$uri}?action={$menu.handler}&order_id={$object.id}">{$menu.text}</a>
                                    </span>
                                {/foreach}
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
            
        </div>
    </div>
{/if}
