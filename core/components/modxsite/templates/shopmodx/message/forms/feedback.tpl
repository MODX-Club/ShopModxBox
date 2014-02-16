<h3>Сообщение с сайта</h3>

<table>
    <tbody>
        {foreach $properties as $field => $value}
            {if !is_array($value) && !is_object($value)}
                <tr>
                    <td style="font-weight:bold;">{$field}</td>
                    <td>{$value}</td>
                </tr>
            {/if}
        {/foreach}
    </tbody>
</table>

<br />
<br />
<hr />

<a href="{$modx->getOption('site_url')}" >{$modx->getOption('site_name')}</a>
