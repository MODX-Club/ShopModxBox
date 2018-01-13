{extends "message/layout.tpl"}

{block name=params append}

    {if !empty($auth_user_id)}
        {$auth_email = $modx->getObject('modUser', $auth_user_id)->Profile->email}
        {$auth_link_salt = $modx->getOption('modsociety.auth_link_salt')}
        {$str = "{$auth_user_id}{$modx->site_id}{$auth_link_salt}"}

    {/if}

{/block}


{block body}

  {block name=header}
      Здравствуйте!<br /><br />
  {/block}

  {block name=content}{/block}

{/block}