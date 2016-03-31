<h2>UnitPay</h2>

<form action="{$modx->getOption('unitpay.pay_server')}" method="post">
    <label for="sum">Сумма платежа</label> 
    {if $editable}
        <input type="text" name="sum" value="{$sum}"/>
    {else}
        <p>{$sum}</p>
        <input type="hidden" name="sum" value="{$sum}"/>
    {/if}
    <input type="hidden" name="account" value="{$order_id}"><br>
    <input type="hidden" name="desc" value="{$desc}"><br>
    <input class="btn btn-primary" type="submit" value="Оплатить">
</form>
