F.mixin(Request.prototype, Object.create(_ShopMODX.prototype));

// default Request object
ShopMODX.modules.Request = function(config){
    config = config || {};
    this.config = F.applyIf(config, this.config);    
    ShopMODX.modules.Request.superclass.constructor.call(this,config);    
}
F.extend(ShopMODX.modules.Request,Request,{
    test: function(){ console.log('ShopMODX.Request') }
});
// eof Request