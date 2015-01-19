ShopMODX.widgets.Order = function(config){
    config = config || {};
    this.config = F.applyIf(config,this.config);    
    ShopMODX.widgets.Order.superclass.constructor.call(this,config);
    
    this.initialize();
};
F.extend(ShopMODX.widgets.Order,ShopMODX.widgets._Order,{

});