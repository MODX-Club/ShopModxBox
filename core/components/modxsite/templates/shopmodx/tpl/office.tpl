{extends file="layout.tpl"}

{*block name=headers}
    <script type="text/javascript" src="{$template_url}js/orders.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            orders.init({
                connectors_url: '{config name=assets_url}components/basket/connectors/'
            });
        });
    </script>
{/block*}

{block name=Breadcrumbs}{/block}

{block name=content}
    <div class="lk-content">
    
        <div class="row-fluid">
            <div class="span2">
                {*<div class="well">*}
                    {snippet name=Wayfinder params="startId=`110`&outerClass=`nav nav-tabs nav-stacked`"}    
                {*</div>*}
            </div>
            <div class="span10">
                {field name=content}
            </div>
        </div>
    </div>
{/block}
 