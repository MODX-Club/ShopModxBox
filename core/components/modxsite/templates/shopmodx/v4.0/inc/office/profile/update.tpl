{$result = $modx->runSnippet('UpdateProfile', [
    "validate" => "fullname:required,email:required:email"
])}

{$request = [
    'fullname' => $modx->getPlaceholder('fullname'),
    'email' => $modx->getPlaceholder('email'),
    'phone' => $modx->getPlaceholder('phone'),
    'mobilephone' => $modx->getPlaceholder('mobilephone'),
    'fax' => $modx->getPlaceholder('fax'),
    'address' => $modx->getPlaceholder('address'),
    'country' => $modx->getPlaceholder('country'),
    'city' => $modx->getPlaceholder('city'),
    'state' => $modx->getPlaceholder('state'),
    'zip' => $modx->getPlaceholder('zip'),
    'website' => $modx->getPlaceholder('website')
    
]}
    
{if $smarty.request['login-updprof-btn']}
    {$request = array_merge($request, $smarty.request)}
{/if}

<div class="update-profile">
    <div class="updprof-error">{$modx->getPlaceholder('error.message')}</div>
    {*
        [[+login.update_success:if=`[[+login.update_success]]`:is=`1`:then=`[[%login.profile_updated? &namespace=`login` &topic=`updateprofile`]]`]]
    *}
    
    {if $modx->getPlaceholder('login.update_success') == '1'}
        <div class="alert alert-success">
            {$modx->lexicon('login.profile_updated')}
        </div>
    {/if}
 
    <form class="form-horizontal" action="" method="post">
        <input type="hidden" name="nospam" value="" />
 
        <div class="form-group">
            <label for="fullname" class="col-md-3 control-label">
                {$modx->lexicon('login.fullname')}
            </label>
            <div class="col-md-5">
                <input class="form-control" type="text" name="fullname" id="fullname" value="{$request.fullname}" />
                {if $error = $modx->getPlaceholder('error.fullname')}
                    <div class="alert alert-danger">{$error}</div>
                {/if}
            </div>
        </div>
 
        <div class="form-group">
            <label for="email" class="col-md-3 control-label">
                {$modx->lexicon('login.email')}
            </label>
            <div class="col-md-5">
                <input class="form-control" type="text" name="email" id="email" value="{$request.email}" />
                {if $error = $modx->getPlaceholder('error.email')}
                    <div class="alert alert-danger">{$error}</div>
                {/if}
            </div>
        </div>
 
        <div class="form-group">
            <label for="phone" class="col-md-3 control-label">
                {$modx->lexicon('login.phone')}
            </label>
            <div class="col-md-5">
                <input class="form-control" type="text" name="phone" id="phone" value="{$request.phone}" />
                {if $error = $modx->getPlaceholder('error.phone')}
                    <div class="alert alert-danger">{$error}</div>
                {/if}
            </div>
        </div>
 
        <div class="form-group">
            <label for="mobilephone" class="col-md-3 control-label">
                {$modx->lexicon('login.mobilephone')}
            </label>
            <div class="col-md-5">
                <input class="form-control" type="text" name="mobilephone" id="mobilephone" value="{$request.mobilephone}" />
                {if $error = $modx->getPlaceholder('error.mobilephone')}
                    <div class="alert alert-danger">{$error}</div>
                {/if}
            </div>
        </div>
 
        <div class="form-group">
            <label for="fax" class="col-md-3 control-label">
                {$modx->lexicon('login.fax')}
            </label>
            <div class="col-md-5">
                <input class="form-control" type="text" name="fax" id="fax" value="{$request.fax}" />
                {if $error = $modx->getPlaceholder('error.fax')}
                    <div class="alert alert-danger">{$error}</div>
                {/if}
            </div>
        </div>
 
        <div class="form-group">
            <label for="address" class="col-md-3 control-label">
                {$modx->lexicon('login.address')}
            </label>
            <div class="col-md-5">
                <input class="form-control" type="text" name="address" id="address" value="{$request.address}" />
                {if $error = $modx->getPlaceholder('error.address')}
                    <div class="alert alert-danger">{$error}</div>
                {/if}
            </div>
        </div>
 
        <div class="form-group">
            <label for="country" class="col-md-3 control-label">
                {$modx->lexicon('login.country')}
            </label>
            <div class="col-md-5">
                <input class="form-control" type="text" name="country" id="country" value="{$request.country}" />
                {if $error = $modx->getPlaceholder('error.country')}
                    <div class="alert alert-danger">{$error}</div>
                {/if}
            </div>
        </div>
 
        <div class="form-group">
            <label for="city" class="col-md-3 control-label">
                {$modx->lexicon('login.city')}
            </label>
            <div class="col-md-5">
                <input class="form-control" type="text" name="city" id="city" value="{$request.city}" />
                {if $error = $modx->getPlaceholder('error.city')}
                    <div class="alert alert-danger">{$error}</div>
                {/if}
            </div>
        </div>
 
        <div class="form-group">
            <label for="state" class="col-md-3 control-label">
                {$modx->lexicon('login.state')}
            </label>
            <div class="col-md-5">
                <input class="form-control" type="text" name="state" id="state" value="{$request.state}" />
                {if $error = $modx->getPlaceholder('error.state')}
                    <div class="alert alert-danger">{$error}</div>
                {/if}
            </div>
        </div>
 
        <div class="form-group">
            <label for="zip" class="col-md-3 control-label">
                {$modx->lexicon('login.zip')}
            </label>
            <div class="col-md-5">
                <input class="form-control" type="text" name="zip" id="zip" value="{$request.zip}" />
                {if $error = $modx->getPlaceholder('error.zip')}
                    <div class="alert alert-danger">{$error}</div>
                {/if}
            </div>
        </div>
 
        <div class="form-group">
            <label for="website" class="col-md-3 control-label">
                {$modx->lexicon('login.website')}
            </label>
            <div class="col-md-5">
                <input class="form-control" type="text" name="website" id="website" value="{$request.website}" />
                {if $error = $modx->getPlaceholder('error.website')}
                    <div class="alert alert-danger">{$error}</div>
                {/if}
            </div>
        </div>
 
        <br class="clear" />
 
        <div class="form-group">
            <div class="col-md-5 col-md-offset-3">
                <input class="btn btn-primary" type="submit" name="login-updprof-btn" value="[[!%login.update_profile]]" />
            </div>
        </div>
    </form>
    
   <br /> 
   <br /> 
   <br /> 
 
</div>