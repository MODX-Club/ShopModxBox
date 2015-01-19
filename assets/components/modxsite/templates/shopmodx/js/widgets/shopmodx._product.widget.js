ShopMODX.widgets._Product = function(config){
    config = config || {};
    this.config = F.applyIf(config,this.config);    
    ShopMODX.widgets._Product.superclass.constructor.call(this,config);   
};
ShopMODX.widgets._Product.prototype = {
    initialize: function(){
        this.bindEvents();  
    }
    ,bindEvents:function(){
        var scope = this; 
        
        $(document).on('click', '[data-'+this.config.cmpFlag+'-'+this.config.productCls+'="'+this.config.productClsVal+'"] ' + this.config.ruler ,function(e){
            e.stopPropagation();
            scope.addToCart.call(this,scope);
            return false;
        })
    }
    ,addToCart:function(scope){
        obj = this;
        if(! (obj instanceof $)) obj = $(obj);
        
        data = obj.parents('form').serializeArray();
        
        // исключаем экшн, подбираемый в форме, чтобы не было конфликтов запросов
        var nData = [];
        for(var i in data){
            if(data[i].name != 'action') nData.push(data[i]);    
        }
        
        scope.config.RequestProvider.run.call(scope,{
            action: scope.config.action || 'product/add'
            ,data:nData
            ,type:'post'
            ,callbackName:'onProductAddToCart'
        })
    }
};
F.mixin(ShopMODX.widgets._Product.prototype, Object.create(_ShopMODX.prototype));