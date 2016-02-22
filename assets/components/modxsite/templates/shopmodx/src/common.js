require('es5-shim');
var $ = require('jQuery');
var Request = require("./utils/request");
var Env = require('./constants/Env');

Request = Object.create(Request).init({
  connectorsUrl: Env.siteConnectorsUrl,
  connector: Env.connector
});

window.ShopMODX = {
    Request: Request,
    Env: Env
};

var clearErrors = function(){
    $(this).parents('.form-group:first').removeClass('has-error');
};

$(function() {
    
    /*
        Сбрасываем подсветку ошибок
    */
    $('form').find('input,select,textarea')
        .on('focus', clearErrors)
        .on('select', clearErrors)
        .on('click', clearErrors)
        .on('change', clearErrors)
        ;
        
        
    /*
        Ajax-формы с аттачем
    */
    $('form[data-action] [type=submit]').on('click', function(el){
        var $this = $(this);
        var form = $this.parents('form:first');
        var action = form.data('action');
        
        var counter = {};
        var goal = '';
        
        if(action != ''){
            
            if(!form.data('in_request')){
                form.data('in_request', true);
                
                if(counter && goal){
                    counter.reachGoal(goal + '-form-send');
                }
                
                var m_data = new FormData(form[0]);   
                
                $.ajax({
                    url: '/assets/components/shopmodx/connectors/connector.php?pub_action=' + action,
                    data: m_data,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    dataType:'json',
                    success: function(response){
                        // console.log(response);
                        try{
                            if(response.success != true){
                                
                                $(response.data).each(function(index, value){
                                    if(value != ''){
                                        alertify.warning(value.msg);
                                    }
                                    
                                    form.find('[name="'+value.id+'"]').parents('.form-group:first').addClass('has-error');
                                });
                                
                                alertify.error(response.message || 'Ошибка выполнения запроса');
                                
                                return;
                            }
                            
                            if(counter && goal){
                                counter.reachGoal(goal + '-success');
                            }
                            
                            // else
                            alertify.success(response.message);
                            form[0].reset();
                            $this.parents('.modal:first').modal('hide');
                        }
                        catch(e){
                            alert('Ошибка выполнения запроса');
                        }
                    }
                    ,error: function(){
                        alertify.error('Ошибка выполнения запроса');
                    }
                    ,complete: function(){
                        form.data('in_request', false);
                    }
                });       
                
            }
        }
        
        return false;
    });

    $('[data-smodx-behav="recount"]').remove();
  

  $("#loginLoginForm [type=submit]").on('click', function() {
    var form = $(this).parents('form:first');
    var data = [];

    form.serialize().split('&').map(function(node) {
      if (node.match('action')) return;
      this.push(node);
    }, data);

    Request.run('login', data.join('&'))
      .then(function(resp) {
        if (resp.success) {
          var url = window.location.pathname;
          window.location.replace(url);
        }
      });

    return false;
  });


});

