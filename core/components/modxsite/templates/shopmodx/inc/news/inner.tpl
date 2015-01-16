<div style="margin: 0 0 50px;">
    <h3>{$object.pagetitle}</h3>
    <p><i>{date('Y-m-d', $object.publishedon)}</i></p>
    <div>
        {$object.summary}
        
        <p><a href="{$object.uri}" title="{$object.pagetitle}">Читать новость полностью...</a></p>
    </div>
</div>