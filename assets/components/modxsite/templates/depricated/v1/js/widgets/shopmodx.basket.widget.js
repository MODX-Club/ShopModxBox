ShopMODX.widgets.Basket = function(config){
    config = config || {};
    this.config = F.applyIf(config,this.config);    
    ShopMODX.widgets.Basket.superclass.constructor.call(this,config);
}
F.extend(ShopMODX.widgets.Basket,ShopMODX.widgets._Basket);
