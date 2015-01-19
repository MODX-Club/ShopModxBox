{*
    Базовый шаблон сайта.
    Расширить можно в любом другом шаблоне просто прописав в начале файла {extends file="layout.tpl"}
    Статьи по modxSmarty: http://modxclub.ru/tag/modxSmarty/
*}
<!DOCTYPE html>

{block name=params}

{$site_name = $modx->getOption('site_name')}
{$cultureKey = $modx->getOption('cultureKey')}

{*
    container-fluid OR container
*}
{$container_class = "container"}

{/block}

<html lang="{$cultureKey}">

{block name=head}
    <head>
        
        {block name=meta}
            
            {* Заголовок специально в блок загнан, чтобы в разных шаблонах его можно было бы переопределить *}
            <title>{block name="title"}{$modx->resource->longtitle|default:$modx->resource->pagetitle} | {$site_name}{/block}</title>
            {snippet name="MetaX@MetaX"}
            
        {/block}
        
        
        
        {block name=jquery}
            <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        {/block}
        
        
        {block name=bootstrap}
        
            {* bootstrap meta *}
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            {* eof bootstrap meta *}
            
            {* bootstrap *}
            {*
                <link href="{$template_url}libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
                <link href="{$template_url}libs/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
                <script type="text/javascript" src="{$template_url}libs/bootstrap/js/bootstrap.min.js"></script>
            *}
            
            <!-- Latest compiled and minified CSS -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
            
            <!-- Optional theme -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
            
            <!-- Latest compiled and minified JavaScript -->
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
            
        {/block} 
        
        
        {block name=shopmodx_scripts}
            
            {* jGrowl *}
            <link href="{$template_url}libs/jgrowl/jquery.jgrowl.css" rel="stylesheet" type="text/css" />
            <script type="text/javascript" src="{$template_url}libs/jgrowl/jquery.jgrowl.js"></script>
            {* eof jGrowl *}
            
            {* placeholeder.js *}
            <script type="text/javascript" src="{$template_url}libs/placeholder/placeholder.js"></script>
            {* eof placeholeder.js *}
    
            {* custom scripts *}
            <script type="text/javascript" src="{$template_url}js/f.js"></script>
            <script type="text/javascript" src="{$template_url}js/shopmodx.js"></script>
            <script type="text/javascript" src="{$template_url}js/modules/informer.module.js"></script>
            <script type="text/javascript" src="{$template_url}js/modules/callback.module.js"></script>
            <script type="text/javascript" src="{$template_url}js/modules/request.module.js"></script>
            
            <script type="text/javascript" src="{$template_url}js/modules/shopmodx._callback.module.js"></script>
            <script type="text/javascript" src="{$template_url}js/modules/shopmodx.callback.module.js"></script>
            <script type="text/javascript" src="{$template_url}js/modules/shopmodx.request.module.js"></script>
            <script type="text/javascript" src="{$template_url}js/modules/shopmodx.informer.module.js"></script>
            
            <script type="text/javascript" src="{$template_url}js/widgets/shopmodx._product.widget.js"></script>
            <script type="text/javascript" src="{$template_url}js/widgets/shopmodx._basket.widget.js"></script>
            <script type="text/javascript" src="{$template_url}js/widgets/shopmodx._order.widget.js"></script>
            <script type="text/javascript" src="{$template_url}js/widgets/shopmodx.product.widget.js"></script>
            <script type="text/javascript" src="{$template_url}js/widgets/shopmodx.basket.widget.js"></script>
            <script type="text/javascript" src="{$template_url}js/widgets/shopmodx.order.widget.js"></script>
            
            <script type="text/javascript" src="{$template_url}js/events.js"></script>
            <script type="text/javascript" src="{$template_url}js/initialization.js"></script>
            {* eof custom scripts *}
            
        {/block} 
        
        
        {block name=fonts}
            {* fonts *}
            <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
            {* eof fonts *}
        {/block}
        
        
        {block name=styles}
        
            {* less *} 
            <link href="{$template_url}css/styles.less" rel="stylesheet/less" type="text/css" />
            <script type="text/javascript" src="{$template_url}libs/less/js/less-1.3.3.min.js"></script>
            {* eof less *}
            
            <link href="{$template_url}css/style.css" rel="stylesheet" type="text/css" />
        {/block}
        
        
        {block name=headers}{/block}
        
    </head>
{/block}

{* Разметка сетки отделена от разметки функц. блоков. Тем самым наш контент не привязан к сетке. Добавлены только теги header и footer. *}

{block name=body}
    <body id="doc_{$modx->resource->id}">
        <div class="{$container_class}">
            
            {block name=header}
            
                <header>   
                
                    <div class="row">
                        <div class="col-md-2">  
                            
                            <div class="logo">
                                <a href="{config name=site_url}" title="{config name=site_name}"></a>
                            </div>
                            
                        </div>
                        <div class="col-md-2 col-md-offset-6">
                        
                            [[!smarty?tpl=`inc/login/auth.tpl`]]
                            
                        </div>
                        <div class="col-md-2">
                            
                            [[!smarty?tpl=`shop/basket/entity/index.tpl`]]
                            
                        </div>
                    </div>
                    
                    {* 
                        @MainMenu - это набор параметров 
                        Подробная статья: http://modxclub.ru/blog/102.html
                    *}
                    {snippet name="Wayfinder@MainMenu"}
                    
                </header>
                
            {/block}
            
            {block name=pagetitle}
                <h1>{if $modx->resource->longtitle}{$modx->resource->longtitle}{else}{$modx->resource->pagetitle}{/if}</h1>
            {/block}
            
            {block name=Breadcrumbs}
                {snippet name="Breadcrumbs@Breadcrumbs"}
            {/block}
            
            {block name=content}
                {$modx->resource->content}
            {/block} 
            
            {block name=footer}
                <footer class="navbar navbar-default">
                    <div class="copy pull-right">
                        {*
                            Если вы создаете магазин на базе этой сборки или просто на базе shopModx,
                            пожалуйста, сохраняйте эти копирайты на сайте.
                            Наличие активных копирайтов на сайте гарантирует вам приоритетные ответы
                            на ваши вопросы по MODX Revolution и нашим модулям на сайте modxclub.ru,
                            а так же очищает вашу совесть и ставит жирный плюс в карму ;-)
                        *}
                        <a href="http://modxclub.ru" title="Клуб MODX-экспертов"><img src="{config name=assets_url}images/site/logos/modx_h30.jpg" /></a>
                        <a href="http://shopmodx.modxclub.ru" class="powby" title="ShopModx. Модуль для разработки интернет-магазинов на MODX Revolution"><img src="{$template_url}img/poweredby1.png" /></a></a>
                    </div>       
                </footer>
            {/block}
            
        </div>
    
    {* 
        modals 
        Модальное окно чисто для всплывашки. Проверка авторизации выше.
    *}
    
    {block name=modals}
        [[!smarty?tpl=`inc/modals/login.tpl`]]
    {/block}
    
    
    
    {block name=jivosite}
        
        {*
            Если у вас будут возникать вопросы по разработке,
            вы можете задавать их нам в чателку на сайте shopmodx.modxclub.ru
        *}
    
        {if $widget_id = $modx->getOption('jivosite.widget_id')}
            <!-- BEGIN JIVOSITE CODE -->
            <script type='text/javascript'>
            (function(){ var widget_id = '{$widget_id}';
            var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
            <!-- END JIVOSITE CODE -->
        {/if}
    {/block}
    
    {block name=footers}{/block}
    
    </body>
{/block}
</html>