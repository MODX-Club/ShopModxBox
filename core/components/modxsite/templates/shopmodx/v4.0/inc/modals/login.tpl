{$assets_url = $modx->getOption('assets_url')}
{$current_doc_id = $modx->resource->id}

{block modal_block}
    <noindex>
        <!-- Modal -->
        <div class="modal fade" id="LoginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="loginLoginForm" class="form" action="" method="post">
                        <input type="hidden" name="pub_action" value="login" />
                        <input type="hidden" name="returnUrl" value="{$request_uri}" />
                    
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h3 id="entrance">Вход</h3>
                        </div>
                        
                        <div class="modal-body">
                            <div class="row">
                                
                                <div class="col-md-6">
                                    {block modal_left_block}
                                        <div class="loginLogin">
                                            <fieldset class="loginLoginFieldset">
                                                <div class="control-group">
                                                    <label class="loginUsernameLabel" for="loginUsername" >Имя пользователя</label> 
                                                    <div class="controls">
                                                        <input id="loginUsername" class="form-control loginUsername" type="text" name="username" />    
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="loginPasswordLabel" for="loginPassword">Пароль</label> 
                                                    <div class="controls">
                                                        <input id="loginPassword" class="form-control loginPassword" type="password" name="password" />
                                                    </div>
                                                </div>
                                                
                                            </fieldset>       
                                        </div>
                                    {/block}
                                </div>
                                <div class="col-md-6">
                                    {block modal_right_block}
                                        <label >Вход через социальные сети</label> 
                                        <div>
                                            <a href="{$assets_url}components/modhybridauth/connectors/profile/auth.php?provider=Twitter&redirect_id={$current_doc_id}" alt="Twitter" title="Войти через Twitter" class="social Twitter" rel="nofollow"></a>
                                            <a href="{$assets_url}components/modhybridauth/connectors/profile/auth.php?provider=Facebook&redirect_id={$current_doc_id}" alt="Facebook" title="Войти через Facebook" class="social Facebook" rel="nofollow"></a>
                                            <a href="{$assets_url}components/modhybridauth/connectors/profile/auth.php?provider=Google&redirect_id={$current_doc_id}" alt="Google" title="Войти через Google" class="social Google" rel="nofollow"></a>
                                            <a href="{$assets_url}components/modhybridauth/connectors/profile/auth.php?provider=Yandex&redirect_id={$current_doc_id}" alt="Yandex" title="Войти через Yandex" class="social Yandex" rel="nofollow"></a>
                                        </div>  
                                    {/block}
                                </div>
                                
                            </div>
                        </div> 
                        
                        <div class="modal-footer">
                            <div class="text-left">
                                <button type="submit" name="Login" class="btn btn-success">Вход</button>
                                <a href="{$modx->makeUrl(104)}" class="btn btn-primary" rel="nofollow">Регистрация</a>
                                <a href="{$modx->makeUrl(108)}" class="btn btn-info" rel="nofollow">Забыли пароль?</a> 
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </noindex>
{/block}