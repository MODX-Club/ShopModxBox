{* Забиваем весь массив $_POST в переменную *}
{assign var=request value=$smarty.post}

{* Получаем профиль пользователя *}
{assign var=profile value=$modx->user->getOne('Profile')}
{if $profile}
    {assign var=profile value=$profile->toArray()}
{/if}

{* Если выполняется запрос, вызываем процессор *}
{if $smarty.post.submit}
    {*processor action="web/basket/order/submit" ns="basket" params=$smarty.post assign=order_result*}
    
    {processor action="basket/web/orders/submit" ns="basket" params=$smarty.post assign=order_result}
    
    
    {if !$order_result.success}
    
        <div class="alert alert-danger">
            {$order_result.message|default:"Ошибка выполнения запроса"}
        </div>
        
        {if $order_result.field_errors}
            <div class="alert alert-info">
                {foreach $order_result.field_errors as $n => $err}
                    <p>{$err}</p>
                {/foreach}
            </div>
        {/if}
        
    {/if}
    
    
    {* Способ увидеть весь ответ от процессора *}
    {*<pre>
        {print_r($order_result)}
    </pre>*}
{/if}
    

{* Вывод корзины *}
{if !$order_result.success}

    {* depricated processor action="web/basket/entity/getdata" ns="basket" assign=basket_result *}
    
    {*
    Сейчас нет необходимости получать данные корзины, так как мы не выводим товары.
    Просто проверим на наличие активного order_id.
    processor action="basket/web/orders/products/getdata" ns="basket" assign=basket_result}


    {if $basket_result.success && $basket_result.object}
        {assign var=total value=$basket_result.total}
        {assign var=sum value=$basket_result.sum}
    {/if
    
    {if $basket_result.success && $basket_result.object}
    *}


    {if $modx->basket->getActiveOrderID()}
     
        {* А здесь в форме мы выводим индивидуальные ошибки в каждом поле *}
            
        <form name="order_form" action="{link id=$modx->resource->id}?action=submit" method="post" class="form-horizontal">
        
            <div class="form-group {if $order_result.field_errors.fullname}has-error{/if}">
                <label for="fullname" class="col-md-3 control-label">Имя</label>
                <div class="col-md-5">
                    <input id="fullname" class="form-control" placeholder="Как вас зовут?" type="text" value="{if $request.fullname}{$request.fullname}{else}{$profile.fullname}{/if}" name="fullname">
                    {if $order_result.field_errors.fullname}
                        <span class="help-inline">{$order_result.field_errors.fullname}</span>
                    {/if}
                </div>
            </div>
            
        
            <div class="form-group {if $order_result.field_errors.email}has-error{/if}">
                <label for="email" class="col-md-3 control-label">Электронная почта</label>
                {if $profile.email}
                    <div class="col-md-5">
                        <input type="hidden" value="{$profile.email}" name="email">
                        <input id="email" class="form-control" type="text" value="{$profile.email}" disabled>
                    </div>
                {else}
                    <div class="col-md-5">
                        <input id="email" class="form-control" placeholder="Адрес электронной почты" type="text" value="{$request.email}" name="email">
                    </div>
                    {if $order_result.field_errors.email}
                        <span class="help-inline">{$order_result.field_errors.email}</span>
                    {/if}
                {/if}
            </div>
            
        
            <div class="form-group {if $order_result.field_errors.phone}has-error{/if}">
                <label for="phone" class="col-md-3 control-label">Телефон</label>
                <div class="col-md-5">
                    <input id="phone" class="form-control" placeholder="Номер телефона для связи" type="text" value="{if $request.phone}{$request.phone}{else}{$profile.phone}{/if}" name="phone">
                    {if $order_result.field_errors.phone}
                        <span class="help-inline">{$order_result.field_errors.phone}</span>
                    {/if}
                </div>
            </div>
            
        
            <div class="form-group {if $order_result.field_errors.address}has-error{/if}">
                <label for="address" class="col-md-3 control-label">Адрес доставки</label>
                <div class="col-md-5">
                    <textarea rows="10" id="address" class="form-control" placeholder="Адрес доставки" name="address">{if $request.address}{$request.address}{else}{$profile.address}{/if}</textarea>
                    {if $order_result.field_errors.address}
                        <span class="help-inline">{$order_result.field_errors.address}</span>
                    {/if}
                </div>
            </div>
            
        
            <div class="form-group">
                <label for="comments" class="col-md-3 control-label">Комментарий</label>
                <div class="col-md-5">
                    <textarea rows="10" id="comments" class="form-control" placeholder="Ваши пожелания" name="comments">{$request.comments}</textarea>
                </div>
            </div>
            
        
            <div class="form-group">
                <div class="col-md-3 col-md-offset-3">
                    <input type="submit" class="btn btn-success" name="submit" value="Отправить" />
                </div>
            </div>
            
        </form>    

    {else}
        <div class="alert alert-warning">
            <h4>Корзина пуста</h4>
        </div>
    {/if}

     
{else}
    
    <div class="alert alert-success">
        <strong>{$order_result.message}</strong>
        <p>Оплатить данный заказ вы можете в личном кабинете на странице <a href="{link id=111}">управления заказами</a> или по этой <a href="{link id=136}?order_id={$order_result.object.id}">ссылке</a></p>
    </div> 
    
{/if}
     
     
