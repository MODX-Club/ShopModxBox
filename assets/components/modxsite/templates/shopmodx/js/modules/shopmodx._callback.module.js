ShopMODX.modules._Callback = function(config){
    config = config || {};
    this.config = F.applyIf(config,this.config);
    ShopMODX.modules._Callback.superclass.constructor.call(this,config);
};
F.extend(ShopMODX.modules._Callback,_ShopMODX);
F.mixin(ShopMODX.modules._Callback.prototype, Object.create(_ShopMODX.prototype));
F.mixin(ShopMODX.modules._Callback.prototype, Object.create(Callback.prototype));