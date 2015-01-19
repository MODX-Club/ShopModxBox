<div class="alert alert-danger">
    <strong>Доступ запрещен.</strong>
    {if !$modx->user->id}
        Попробуйте <a href="javascript:;" class="pseudo" role="button" class="pdeudo" data-toggle="modal" data-target="#LoginModal">авторизоваться</a>.
    {/if}
</div>