<div class="panel panel-default">
    
    <div class="panel-heading">
        <h3><a href="{$object.uri}" title="{$object.pagetitle}">{$object.pagetitle}</a></h3>
    </div>
    
    <div class="panel-body">
        {$object.summary}
    </div>
    
    <div class="panel-footer">
        <div class="row">
            <div class="col-xs-6">
                <i>{date('Y-m-d', $object.publishedon)}</i>
            </div>
            <div class="col-xs-6 text-right">
                <a href="{$object.uri}" title="{$object.pagetitle}">Читать новость полностью...</a>
            </div>
        </div>
    </div> 
</div>