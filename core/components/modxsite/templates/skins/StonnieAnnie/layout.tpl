{*

    Данный шаблон будет расширять шаблон layout.tpl из основного скина сайта,
    название которого содержится в переменной modxSmarty.template
*}

{extends "[main]layout.tpl"}

{block name=styles append}
    <link href="{$pre_template_url}css/style.css" rel="stylesheet" type="text/css" />
{/block}

{block name=bootstrap}
        
    {* bootstrap meta *}
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="generator" content="Bootply" />
    {* eof bootstrap meta *}
    
    {* bootstrap *}
    
    <link href="{$pre_template_url}bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="{$pre_template_url}bootstrap/css/styles.css" rel="stylesheet">
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">
    <script type="text/javascript" src="{$pre_template_url}bootstrap/js/bootstrap.min.js"></script>
    
	<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
    
    {* Eof bootstrap *}
            
{/block} 