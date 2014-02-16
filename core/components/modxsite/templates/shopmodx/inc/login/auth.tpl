{if $modx->user->isAuthenticated()}
    {chunk name="dbt.lgnLogoutTpl"}
{else}
    <div class="entrance-modal">
        <a href="#modal-entrance" class="pseudo" role="button" class="pdeudo" data-toggle="modal">Вход</a>
    </div>
{/if}