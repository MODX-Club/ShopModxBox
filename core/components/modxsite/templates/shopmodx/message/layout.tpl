{*
    Базовый шаблон для писем
*}

{block name=params}
    {$site_name = $modx->getOption('site_name')}
    {$site_url = $modx->getOption('site_url')}
{/block}

{block name=body}
{/block}

<hr />
С уважением, администрация сайта <a href="{$site_url}">{$site_name}</a>
