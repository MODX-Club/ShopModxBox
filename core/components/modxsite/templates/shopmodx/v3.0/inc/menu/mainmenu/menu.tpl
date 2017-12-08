{* Главное меню *}

{block menu_params}
    
    {$params = [
        "parentRowTpl" => '@CODE <li[[+wf.id]][[+wf.classes]]>
            <a href="[[+wf.link]]" title="[[+wf.title]]" [[+wf.attributes]] class="dropdown-toggle" data-toggle="dropdown">[[+wf.linktext]] <span class="caret"></span></a>
            [[+wf.wrapper]]
        </li>',
        "outerClass" => 'nav navbar-nav',
        "innerClass" => 'dropdown-menu'
    ]}

{/block}

{block menu_wrapper}

    <div class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse">
                {snippet name="Wayfinder@MainMenu" params=$params as_tag=!$main_menu_is_cached}
            </div>
        </div>
    </div>
    
{/block}
 
