{$assets_url = $modx->getOption('assets_url')}

<form id="loginLoginForm" class="form" action="[[~[[*id]]]]" method="post">
    <input type="hidden" name="pub_action" value="login" />
    <input type="hidden" name="returnUrl" value="{$request_uri}" />
    
    <div class="row">
        
        <div class="col-md-6">
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
        </div>
        <div class="col-md-6">
            
            <label >Вход через социальные сети</label> 
            <div> 
                <a href="{$assets_url}components/modhybridauth/connectors/profile/auth.php?provider=Twitter&redirect_id=104" alt="Twitter" title="Войти через Twitter" class="social Twitter"></a>
                <a href="{$assets_url}components/modhybridauth/connectors/profile/auth.php?provider=Facebook&redirect_id=104" alt="Facebook" title="Войти через Facebook" class="social Facebook"></a>
                <a href="{$assets_url}components/modhybridauth/connectors/profile/auth.php?provider=Google&redirect_id=104" alt="Google" title="Войти через Google" class="social Google"></a>
                <a href="{$assets_url}components/modhybridauth/connectors/profile/auth.php?provider=Yandex&redirect_id=104" alt="Yandex" title="Войти через Yandex" class="social Yandex"></a>
            </div>  
            
        </div>
        
        
        <div class="col-md-12">  
            <br /> 
            <span class="loginLoginButton">
                <button type="submit" name="Login" class="btn btn-success">Вход</button>
                <a href="{$modx->makeUrl(104)}" class="btn btn-primary">Регистрация</a>
                <a href="{$modx->makeUrl(108)}" class="btn btn-info">Забыли пароль?</a> 
            </span>
        </div>
        
    </div>

</form> 

{*

<div class="row row-fluid">
    <div class="col-md-12 span12">
        <div class="loginForm">
            <div class="loginMessage">[[+errors]]</div>
            <div class="loginLogin">
                <form class="loginLoginForm form" action="[[~[[*id]]]]" method="post">
                    <fieldset class="loginLoginFieldset">
                        <div class="control-group">
                            <label class="loginUsernameLabel" for="loginUsername" >[[%login.username]]</label> 
                            <div class="controls">
                                <input id="loginUsername" class="form-control loginUsername" type="text" name="username" />    
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="loginPasswordLabel" for="loginPassword">[[%login.password]]</label> 
                            <div class="controls">
                                <input id="loginPassword" class="form-control loginPassword" type="password" name="password" />
                            </div>
                        </div>
    
                        <input class="returnUrl" type="hidden" name="returnUrl" value="[[+request_uri]]" />
         
                        
                        <input class="loginLoginValue" type="hidden" name="service" value="login" />
                        <span class="loginLoginButton">
                            <button type="submit" name="Login" class="btn btn-primary">Вход</button>
                        </span>
                        
                        
                        
                        <a href="{$modx->makeUrl(104)}">Регистрация</a>
                        <a href="{$modx->makeUrl(108)}">Забыли пароль?</a>
                        
                        <div>
                            Вход через социальную сеть: 
                            <a href="{$assets_url}components/modhybridauth/connectors/profile/auth.php?provider=Twitter&redirect_id=104" alt="Twitter" title="Войти через Twitter" class="social Twitter"></a>
                            <a href="{$assets_url}components/modhybridauth/connectors/profile/auth.php?provider=Facebook&redirect_id=104" alt="Facebook" title="Войти через Facebook" class="social Facebook"></a>
                            <a href="{$assets_url}components/modhybridauth/connectors/profile/auth.php?provider=Google&redirect_id=104" alt="Google" title="Войти через Google" class="social Google"></a>
                            <a href="{$assets_url}components/modhybridauth/connectors/profile/auth.php?provider=Yandex&redirect_id=104" alt="Yandex" title="Войти через Yandex" class="social Yandex"></a>
                        </div>
                        
                    </fieldset>
                </form>        
            </div>
        </div>
    </div>
</div>
*}