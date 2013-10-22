ShopMODX.modules.Callback = function(config){
    config = config || {};
    this.config = F.applyIf(config,this.config);    
    ShopMODX.modules.Callback.superclass.constructor.call(this,config);
};
F.extend(ShopMODX.modules.Callback,ShopMODX.modules._Callback);

ShopMODX.modules.Callback = new ShopMODX.modules.Callback();