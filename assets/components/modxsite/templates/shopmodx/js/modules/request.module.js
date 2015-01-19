Request = function(config){
    config = config || {};
    this.config = F.applyIf(config,this.config); 
};
Request.prototype = {
    inRequest: false
    ,test: function(){ console.log('Request') }     // current request provider test
    ,run: function(params){        
        var scope = this
            ,Informer = this.config.InformerProvider
            ,Callback = this.config.CallbackProvider
            ,Request = this.config.RequestProvider
            ,callback
        ;
        
        F.apply(this.config,params);
        
        if(Request.checkRequest()) return;
        Request.startRequest();
		
        //	init callbacks
        Request.initCallbacks.call(Callback,this.config);        
        callback = Callback.callbacks[this.config.callbackName];
        
        if(typeof callback !== 'undefined' && typeof callback.onBefore !== 'undefined') callback.onBefore.pub(this.config);
        
        $.ajax({
			url: params.url || Request.getConnectorUrl.call(scope)
			,type: params.type || 'post'
			,dataType: params.dataType || 'JSON'
			,data:  params.data || {}
			,success: function(resp){
                Request.stopRequest();   
                var error = true;
                if(typeof callback !== 'undefined' && typeof callback.onSuccess !== 'undefined') callback.onSuccess.pub(resp);
                if(resp.success) error = false;
                 
                if(!scope.config.noMsg){
                    if(error || (!error && !scope.config.noSuccessMsg)){
                        Informer.showMsg(resp.message, error);
                    }
                }
			}
			,error: function(conn, err){
                Request.stopRequest();   
                var error = true;
                if(typeof callback !== 'undefined' && typeof callback.onFailure !== 'undefined') callback.onFailure.pub(conn,err);
				Informer.showMsg('Ошибка выполнения запроса',error);
			}
		});        
    }
    ,initCallbacks: function(config){
        var events = config.events
            ,flag = config.callbackFlag
        ;
        if(typeof events !== 'undefined'){
            for( var i in events ){
                var listeners = events[i];
                if(typeof this.callbacks[i] !== 'undefined') continue;
                if (typeof listeners === 'object'){
                    for(var j in listeners){
                        var event = this.initialize({p:i,c:j},flag); 
                        if (typeof listeners[j] === 'function') event.sub(listeners[j]);
                    }    
                }
            }
        }
        
    }
    ,checkRequest: function(){
        return this.inRequest
    }
    ,startRequest: function(){
        this.inRequest = true;    
    }
    ,stopRequest: function(){
        this.inRequest = false;
    }
    ,getConnectorUrl: function(){        
        connectorsUrl = this.config.connectorsUrl;
        connector = this.config.connector;
        return connectorsUrl+connector+'?action='+this.config.action;
    }
};