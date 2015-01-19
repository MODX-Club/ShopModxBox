Informer = function(config){    
    config = config || {};
    F.apply(this.config, config);
    Informer.superclass.constructor.call(this,this.config);
}
Informer.prototype = {
    showMsg:function(m){
        $.jGrowl(m,{
            sticky:false
        });
    }
}
