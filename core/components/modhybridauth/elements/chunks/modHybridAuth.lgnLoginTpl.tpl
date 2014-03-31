<div class="loginForm">
    <div class="loginMessage">[[+errors]]</div>
    <div class="loginLogin">
        <form class="loginLoginForm" action="[[~[[*id]]]]" method="post">
            <fieldset class="loginLoginFieldset">
                <legend class="loginLegend">[[+actionMsg]]</legend>
                <label class="loginUsernameLabel"><span class="label">[[%login.username]]</span>
                    <input class="loginUsername" type="text" name="username" />
                </label>
                
                <label class="loginPasswordLabel"><span class="label">[[%login.password]]</span>
                    <input class="loginPassword" type="password" name="password" />
                </label>
                <input class="returnUrl" type="hidden" name="returnUrl" value="[[+request_uri]]" />

                [[+login.recaptcha_html]]
                
                <input class="loginLoginValue" type="hidden" name="service" value="login" />
                <span class="loginLoginButton"><input type="submit" name="Login" value="[[+actionMsg]]" /></span>
                 
                <a href="[[~84]]">Регистрация</a>
                <a href="[[~93]]">Забыли пароль?</a>
                
                <div>
                    Вход через социальную сеть: 
                    <a href="[[++assets_url]]components/modhybridauth/connectors/profile/auth.php?provider=Twitter&redirect_id=84" alt="Twitter" title="Войти через Twitter" class="social Twitter"></a>
                    <a href="[[++assets_url]]components/modhybridauth/connectors/profile/auth.php?provider=Facebook&redirect_id=84" alt="Facebook" title="Войти через Facebook" class="social Facebook"></a>
                    <a href="[[++assets_url]]components/modhybridauth/connectors/profile/auth.php?provider=Google&redirect_id=84" alt="Google" title="Войти через Google" class="social Google"></a>
                </div>
                
            </fieldset>
        </form>
        
    </div>
</div>