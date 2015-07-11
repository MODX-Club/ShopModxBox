{block register_params}
    
    {$placeholderPrefix = "reg."}
    
    {$modHybridAuth_params = [
        "placeholderPrefix" => $placeholderPrefix
    ]}
    
    {$Register_params = [
        submitVar => "registerbtn",
        activationEmailSubject => "Thanks for Registering!",
        activationResourceId => "105",
        submittedResourceId => "106",
        activation => "",
        usergroups => "10:Member",
        validate => "nospam:blank,
            username:required,
            password:required:minLength=^{$modx->getPlaceholder('+password_min_length')}^,
            password_confirm:password_confirm=^password^,
            email:required:email",
        placeholderPrefix => $placeholderPrefix,
        postHooks => "modHybridAuth.postHook"
    ]}

{/block}

{block register_request}

    {snippet name="modHybridAuth.Register" params=$modHybridAuth_params}
    {snippet name="Register" params=$Register_params}
    
{/block}

{block register_form}
    <div class="row">
        <form class="form-horizontal col-md-9 col-lg-8" action="" method="post">
            <input type="hidden" name="nospam" value="{$modx->getPlaceholder('reg.nospam')}" />
             
            <div class="register panel panel-default">
                
                <div class="panel-heading">
                    Регистрация
                </div>
                
                <div class="panel-body">
                 
                    {if $message = $modx->getPlaceholder('reg.error.message')}
                        <div class="registerMessage alert alert-danger">{$message}</div>
                    {/if}
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="username">{$modx->lexicon('register.username', [
                            namespace=>"login", 
                            topic=>"register"
                        ])}</label>
                            
                        <div class="col-md-8">
                            <input class="form-control {if $username_error = $modx->getPlaceholder('reg.error.username')}has-error{/if}" type="text" name="username" id="username" value="{$modx->getPlaceholder('reg.username')}" />
                            <span class="error text-danger">{$username_error}</span>
                        </div>
                    </div>
             
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="password">{$modx->lexicon('register.password')}</label>
                        
                        <div class="col-md-8">
                            <input class="form-control {if $password_error = $modx->getPlaceholder('reg.error.password')}has-error{/if}" type="password" name="password" id="password" value="{$modx->getPlaceholder('reg.password')}" />
                            <span class="error text-danger">{$password_error}</span>
                        </div>
                    </div>
             
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="password_confirm">{$modx->lexicon('register.password_confirm')}
                        </label>
                        <div class="col-md-8">
                            <input class="form-control {if $password_confirm_error = $modx->getPlaceholder('reg.error.password_confirm')}has-error{/if}" type="password" name="password_confirm" id="password_confirm" value="{$modx->getPlaceholder('reg.password_confirm')}" />
                            <span class="error text-danger">{$password_confirm_error}</span>
                        </div>
                    </div>
             
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="email">{$modx->lexicon('register.email')}
                        </label>
                        <div class="col-md-8">
                            <input class="form-control {if $email_error = $modx->getPlaceholder('reg.error.email')}has-error{/if}" type="text" name="email" id="email" value="{$modx->getPlaceholder('reg.email')}" />
                            <span class="error text-danger">{$email_error}</span>
                        </div>
                    </div>
             
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="fullname">{$modx->lexicon('register.fullname')}
                        </label>
                        <div class="col-md-8">
                            <input class="form-control {if $fullname_error = $modx->getPlaceholder('reg.error.fullname')}has-error{/if}" type="text" name="fullname" id="fullname" value="{$modx->getPlaceholder('reg.fullname')}" />
                            <span class="error text-danger">{$fullname_error}</span>
                        </div>
                    </div>
                    
                </div>
            
          
                <div class="panel-footer">
                    <input class="btn btn-primary" type="submit" name="registerbtn" value="{$modx->lexicon('register.register')}" />
                </div>
            </div>
        </form>
    </div>
{/block}
