{extends file="message/users/layout.tpl"}
{block name=content}

    
	{$text}

    
    {if !$user->active}
    	<p>
        
        	Для вашего удобства мы уже зарегистрировали для вас специальную учетную запись. Для ее активации перейдите по ссылке <a href="{$activate_link}">{$activate_link}</a>
    		
    	</p>
    {/if}
    
    <p>
    	
	    Логин: {$properties.username}<br />
	    Пароль: {$newPassword}

    </p>

    {*
    Пароль вы сможете сменить пароль в личном кабинете. <br /><br />
    *}
    
{/block}