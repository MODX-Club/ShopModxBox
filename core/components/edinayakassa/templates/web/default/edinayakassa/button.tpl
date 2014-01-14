<h2>Единая касса</h2>

<form class="payment" action="https://merchant.w1.ru/checkout/default.aspx" method="POST">

    {foreach $fields as $key => $val}
        {if is_array($val)}
            {foreach $val as $value}
            {/foreach}
        {else}
            <input name="{$key}" type="hidden" value="{$val}"/>
        {/if} 
    {/foreach}

    <input type="submit"/>
</form>
