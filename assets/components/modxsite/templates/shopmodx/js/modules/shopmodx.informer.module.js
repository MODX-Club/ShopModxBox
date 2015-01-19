F.mixin(Informer.prototype, Object.create(_ShopMODX.prototype));

ShopMODX.modules.Informer = function(config){
    config = config || {};
    F.apply(this.config, config);    
    ShopMODX.modules.Informer.superclass.constructor.call(this,this.config);    
}
F.extend(ShopMODX.modules.Informer,Informer);

ShopMODX.modules.Informer = new ShopMODX.modules.Informer();