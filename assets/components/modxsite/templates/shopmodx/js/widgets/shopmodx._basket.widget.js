ShopMODX.widgets._Basket = function(config){
    config = config || {};
    this.config = F.applyIf(config,this.config);    
    ShopMODX.widgets._Basket.superclass.constructor.call(this,config);
};
ShopMODX.widgets._Basket.prototype = {
    refresh:function(){
        var scope = this;
        
        scope.config.RequestProvider.run.call(this,{
            action: scope.config.action.refresh || 'getdata'
            ,callbackName: 'onMiniBasketRefresh'
            ,noMsg:true
        });    
    }
    ,clear:function(){
        var scope = this;
        
        scope.config.RequestProvider.run.call(this,{
            action: scope.config.action.clear || 'empty'
            ,callbackName: 'onMiniBasketClear'
        });    
    }
    ,resetBasket: function(sum,num,text){
        var basket = $('[data-'+this.config.cmpFlag+'-'+this.config.dataCls+'="'+this.config.dataClsVal+'"]');

        if(typeof basket[0] !== 'undefined'){
            basket.find('.'+this.config.elements.sumCls).text(sum);
            basket.find('.'+this.config.elements.numCls).text(num);
            basket.find('.'+this.config.elements.textCls).text(this.processText(num,this.config.declensions));
            basket.find('.'+this.config.elements.curCls).text(this.config.elements.curVal);   
        }
    }
    ,processText: function(val,obj){
        if (val%10==1 && val%100!=11){
            return obj.one;
        }else if(val%10>=2 && val%10<=4 && (val%100<10 || val%100>=20)){
            return obj.two;
        }else{
            return obj.many;
        }
    }
};
F.mixin(ShopMODX.widgets._Basket.prototype, Object.create(_ShopMODX.prototype));