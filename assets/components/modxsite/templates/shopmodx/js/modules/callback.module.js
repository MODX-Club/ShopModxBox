Callback = function(config){
    config = config || {};
    this.config = F.applyIf(config,this.config);    
    Callback.superclass.constructor.call(this,config);
};
Callback.prototype = {
    callbacks:{}
    ,initialize:function(data,flag){
        var callback
            ,group
            ,flag = flag || ''
        ;
        if(data.p && typeof this.callbacks[data.p] === 'undefined') this.callbacks[data.p] = {};
        
        group = this.callbacks[data.p];
        
        if(data.c && typeof group[data.c] === 'undefined'){
            cb = $.Callbacks(flag);
            callback = {
                pub: cb.fire
                ,sub: cb.add
                ,unsub: cb.remove
            }
            if(data.p && data.c){                
                group[data.c] = callback;
            }
        }else{
            callback = group[data.c];
        }
        return callback;
    }    
};