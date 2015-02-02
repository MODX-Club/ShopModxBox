{extends file="tpl/index.tpl"}
 
{block name=Breadcrumbs}{/block}

{block name=content}
    <div class="lk-content"> 
        <div class="row">
            <div class="col-md-2 sidebar">
                {snippet name=Wayfinder params="startId=`110`&outerClass=`nav nav-sidebar nav-tabs nav-stacked`"}  
            </div>
            <div class="col-md-10">
                {$smarty.block.parent}
            </div>
        </div>
    </div>
{/block}
 