ShopMODX.widgets.Product = function(config){
    config = config || {};
    this.config = F.applyIf(config,this.config);
    ShopMODX.widgets.Product.superclass.constructor.call(this,config);  
    
    this.initialize();
    
};
F.mixin(ShopMODX.widgets.Product.prototype, Object.create(ShopMODX.widgets._Product.prototype));

F.extend(ShopMODX.widgets.Product,ShopMODX.widgets._Product);