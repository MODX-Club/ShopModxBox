[[!modHybridAuth.Register?placeholderPrefix=`reg.`]]

[[!Register?
    &submitVar=`registerbtn`
    &activationEmailSubject=`Thanks for Registering!`
    &activationResourceId=`105`
    &submittedResourceId=`106`
    &activation=``
    &usergroups=`10:Member`
    &validate=`nospam:blank,
  username:required,
  password:required:minLength=^[[++password_min_length]]^,
  password_confirm:password_confirm=^password^,
  email:required:email`
    &placeholderPrefix=`reg.`
    &postHooks=`modHybridAuth.postHook`
]]
 
<div class="register">
    <div class="registerMessage">[[!+reg.error.message]]</div>
 
    <form class="form-horizontal" action="[[~[[*id]]]]" method="post">
        <input type="hidden" name="nospam" value="[[!+reg.nospam]]" />
     
        <div class="form-group">
            <label class="col-md-3 control-label" for="username">[[%register.username? &namespace=`login` &topic=`register`]]
                <span class="error">[[!+reg.error.username]]</span>
            </label>
            <div class="col-md-5">
                <input class="form-control" type="text" name="username" id="username" value="[[!+reg.username]]" />
            </div>
        </div>
 
        <div class="form-group">
            <label class="col-md-3 control-label" for="password">[[%register.password]]
                <span class="error">[[!+reg.error.password]]</span>
            </label>
            <div class="col-md-5">
                <input class="form-control" type="password" name="password" id="password" value="[[!+reg.password]]" />
            </div>
        </div>
 
        <div class="form-group">
            <label class="col-md-3 control-label" for="password_confirm">[[%register.password_confirm]]
                <span class="error">[[!+reg.error.password_confirm]]</span>
            </label>
            <div class="col-md-5">
                <input class="form-control" type="password" name="password_confirm" id="password_confirm" value="[[!+reg.password_confirm]]" />
            </div>
        </div>
 
        <div class="form-group">
            <label class="col-md-3 control-label" for="email">[[%register.email]]
                <span class="error">[[!+reg.error.email]]</span>
            </label>
            <div class="col-md-5">
                <input class="form-control" type="text" name="email" id="email" value="[[!+reg.email]]" />
            </div>
        </div>
 
        <div class="form-group">
            <label class="col-md-3 control-label" for="fullname">[[%register.fullname]]
                <span class="error">[[!+reg.error.fullname]]</span>
            </label>
            <div class="col-md-5">
                <input class="form-control" type="text" name="fullname" id="fullname" value="[[!+reg.fullname]]" />
            </div>
        </div>
 
        <br class="clear" />
        <div class="form-group">
            <div class="col-md-3 col-md-offset-3">
            <input class="btn btn-primary" type="submit" name="registerbtn" value="[[%register.register]]" />
        </div>
    </form>
</div>