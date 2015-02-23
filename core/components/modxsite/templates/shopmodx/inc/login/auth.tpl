{*if $modx->user->isAuthenticated($modx->context->key)*}
{if $modx->user->id}

    {include "inc/login/logout.tpl"}
    
{else}
    <div class="entrance-modal">
        <a href="javascript:;" class="pseudo" role="button" data-toggle="modal" data-target="#LoginModal">Вход</a>
    </div>
{/if}