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
        <div style="color:red;font-weight:bold;">{$order_result.message}</div>
        <div style="margin:0 0 15px;">
            {foreach $order_result.field_errors as $n => $err}
                <div>{$err}</div>
            {/foreach}
        </div>
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
            
        <form name="order_form" action="{link id=$modx->resource->id}?action=submit" method="post" class="form form-horizontal">
        
            <div class="control-group {if $order_result.field_errors.fullname}error{/if}">
                <label for="fullname">Имя</label>
                <div class="">
                    <input id="fullname" class="input-xxlarge" placeholder="Как вас зовут?" type="text" value="{if $request.fullname}{$request.fullname}{else}{$profile.fullname}{/if}" name="fullname">
                    {if $order_result.field_errors.fullname}
                        <span class="help-inline">{$order_result.field_errors.fullname}</span>
                    {/if}
                </div>
            </div>
            
        
            <div class="control-group {if $order_result.field_errors.email}error{/if}">
                <label for="email">Электронная почта</label>
                {if $profile.email}
                    <div class="">
                        <input type="hidden" value="{$profile.email}" name="email">
                        <input id="email" class="input-xxlarge" type="text" value="{$profile.email}" disabled>
                    </div>
                {else}
                    <div class="">
                        <input id="email" class="input-xxlarge" placeholder="Адрес электронной почты" type="text" value="{$request.email}" name="email">
                    </div>
                    {if $order_result.field_errors.email}
                        <span class="help-inline">{$order_result.field_errors.email}</span>
                    {/if}
                {/if}
            </div>
            
        
            <div class="control-group {if $order_result.field_errors.phone}error{/if}">
                <label for="phone">Телефон</label>
                <div class="">
                    <input id="phone" class="input-xxlarge" placeholder="Номер телефона для связи" type="text" value="{if $request.phone}{$request.phone}{else}{$profile.phone}{/if}" name="phone">
                    {if $order_result.field_errors.phone}
                        <span class="help-inline">{$order_result.field_errors.phone}</span>
                    {/if}
                </div>
            </div>
            
        
            <div class="control-group {if $order_result.field_errors.address}error{/if}">
                <label for="address">Адрес доставки</label>
                <div class="">
                    <textarea rows="10" id="address" class="input-xxlarge" placeholder="Адрес доставки" name="address">{if $request.address}{$request.address}{else}{$profile.address}{/if}</textarea>
                    {if $order_result.field_errors.address}
                        <span class="help-inline">{$order_result.field_errors.address}</span>
                    {/if}
                </div>
            </div>
            
        
            <div class="control-group">
                <label for="comments">Комментарий</label>
                <div class="">
                    <textarea rows="10" id="comments" class="input-xxlarge" placeholder="Ваши пожелания" name="comments">{$request.comments}</textarea>
                </div>
            </div>
            
        
            <div class="control-group">
                <input type="submit" class="btn" name="submit" value="Отправить" />
            </div>
            
        </form>    

    {else}
        <div class="alert alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Корзина пуста</h4>
        </div>
    {/if}

     
{else}
    <p><span class="success">{$order_result.message}</span></p>
    <p>Оплатить данный заказ вы можете в личном кабинете на странице <a href="{link id=111}">управления заказами</a> или по этой <a href="{link id=136}?order_id={$order_result.object.id}">ссылке</a></p>
{/if}
     
     
     