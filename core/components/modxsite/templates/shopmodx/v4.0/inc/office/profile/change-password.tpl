{$result = $modx->runSnippet('ChangePassword', [
    "submitVar" => "change-password",
    "placeholderPrefix" => "cp.",
    "validateOldPassword" => "1",
    "reloadOnSuccess" => "",
    "validate" => "nospam:blank",
    "successMessage" => "Пароль успешно изменен"
])}

{$request = []}
    
{if $smarty.request['change-password']}
    {$request = array_merge($request, $smarty.request)}
{/if}


<div class="">

    <div class="updprof-error">{$modx->getPlaceholder('cp.error_messag')}</div>
    <div class="updprof-success success">{$modx->getPlaceholder('cp.successMessage')}</div>
    <form class="form-horizontal" action="" method="post">
        <input type="hidden" name="nospam" value="" />
        <div class="form-group">
            <label for="password_old" class="col-md-3 control-label">Старый пароль</label>
            <div class="col-md-4">
                <input type="password" name="password_old" id="password_old" value="" />
                {if $error = $modx->getPlaceholder('cp.error.password_old')}
                    <div class="alert alert-danger">{$error}</div>
                {/if}
            </div>
        </div>
        <div class="form-group">
            <label for="password_new" class="col-md-3 control-label">Новый пароль</label>
            <div class="col-md-4">
                <input type="password" name="password_new" id="password_new" value="" />
                {if $error = $modx->getPlaceholder('cp.error.password_new')}
                    <div class="alert alert-danger">{$error}</div>
                {/if}
            </div>
        </div>
        <div class="form-group">
            <label for="password_new_confirm" class="col-md-3 control-label">Подтвердите новый пароль</label>
            <div class="col-md-4">
                <input type="password" name="password_new_confirm" id="password_new_confirm" value="" />
                {if $error = $modx->getPlaceholder('cp.error.password_new_confirm')}
                    <div class="alert alert-danger">{$error}</div>
                {/if}
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4 col-md-offset-3">
                <input class="btn btn-primary" type="submit" name="change-password" value="[[%login.change_password]]" />
            </div>
        </div>
    </form>
</div>
    