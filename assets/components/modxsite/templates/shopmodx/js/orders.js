var isDefined = function (el){
    return typeof el !== 'undefined';
};
var applyIf = function (E,F){if(E){for(var e in F){if(!isDefined(E[e])){E[e]=F[e]}}}return E};


var orders = function(){
    var self = this;
    
    
    this.init = function(config){
        this.config = config || {};
        applyIf(this.config, {});
        
        this.addListeners();
    };
    
    this.addListeners = function(){
        // Добавляем события на кнопки действий
        this.addActionsListeners();
    };
    
    this.addActionsListeners = function(){
        $('a.action').bind('click', this, this.callback);
    };
    
    /*
        Универсальный метод для вызова внутри объекта
    */
    this.callback = function(e){
        var el = $(this);
        var method = el.attr('rel');
        if(!e.data[method]){
            console.log("Method '"+method+"' not exists");
            return false;
        }
        return e.data[method].call(e.data, el);
    };
    
    this.cancel = function(el){
        return this.request({
            data: {
                action: "update/cancel"
                ,order_id: this.getOrderIdByEl(el)
            }
            ,listeners:{
                success: function(){
                    window.location.reload();
                }
            }
        });
    };
    
    this.restore = function(el){
        return this.request({
            data: {
                action: "update/restore"
                ,order_id: this.getOrderIdByEl(el)
            }
            ,listeners:{
                success: function(){
                    window.location.reload();
                }
            }
        });
    };
    
    this.getOrderIdByEl = function(el){
        return el.parents('.order').attr('rel');
    };
    
    this.request = function(params){
        
        params = params || {};
        
        applyIf(params,{
            url: this.config.connectors_url + 'orders.php'
            ,type: "post"
            ,dataType: "JSON"
            ,error: function(){
                alert("Ошибка выполнения запроса");
            }
            ,success: function(r){
                if(!r.success){
                    alert(r.message || "Ошибка выполнения запроса");
                    return;
                }
                
                if(params.listeners.success){
                    params.listeners.success();
                }
                return;
            }
            ,data:{}
            ,listeners:{}
        });
        
        $.ajax(params);
        
    };
    //console.log(123);
    return this;
};

orders = new orders({});

