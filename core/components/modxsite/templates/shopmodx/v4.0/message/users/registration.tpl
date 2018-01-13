{extends file="message/users/layout.tpl"}

{block name=content}

    Вы зарегистрировались на сайте «{$site_name}».<br /><br />
    
    {if !$user->active}
        Для активации вашей учетной записи перейдите, пожалуйста, по ссылке <a href="{$activate_link}">{$activate_link}</a><br /><br />
    {/if}
    
    Логин: {$properties.username}<br />
    Пароль: {$newPassword}<br />

    {*
    Пароль вы сможете сменить пароль в личном кабинете. <br /><br />
    *}
    
{/block}