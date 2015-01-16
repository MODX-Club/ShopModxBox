<!-- Button trigger modal -->
{*
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button>
*}

<!-- Modal -->
<div class="modal fade" id="LoginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 id="entrance">Вход</h3>
      </div>
      <div class="modal-body">
            {* [[!Login@modHybridAuth]] *}
            {include "inc/login/login.tpl"}
      </div> 
    </div>
  </div>
</div>


{*

<div id="modal-entrance" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="entrance" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="entrance">Вход</h3>
    </div>
    <div class="modal-body">
        [[!Login@modHybridAuth]]
    </div> 
</div>

*}

