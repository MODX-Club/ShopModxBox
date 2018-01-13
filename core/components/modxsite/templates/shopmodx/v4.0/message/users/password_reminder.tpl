{extends file="message/users/layout.tpl"}
{block name=content}

    Ваши данные авторизации на сайте «{$site_name}».<br /><br />

    Логин: {$username}<br />
    Пароль: {$password}<br />

{/block}