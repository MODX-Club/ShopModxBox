ShopMODX.widgets._Order = function(config){
    config = config || {};
    this.config = F.applyIf(config,this.config);    
    ShopMODX.widgets._Order.superclass.constructor.call(this,config);
};
ShopMODX.widgets._Order.prototype = {
    getObjSelector: function(cls,val){
        return '[data-'+this.config.cmpFlag+'-'+cls+'="'+val+'"]';
    }
    
    ,checkProductRowsExists: function(){
        objs = $( this.getObjSelector(this.config.goodCls,this.config.goodClsVal) );
        if(typeof objs[0] !== 'undefined' ) return objs.length;
        return false;
    }
    ,removeProductRow: function(){
        this.remove();    
    }
    ,findProductRow: function(id){
        return $( this.getObjSelector(this.config.goodIDCls,id) );    
    }
    ,updateRows: function(id){
        obj = this.findProductRow(id);
        if(typeof obj[0] !== 'undefined'){            
            if( this.checkProductRowsExists() > 1 ){
                this.removeProductRow.call(obj);
            }else{
                location.reload();  
            }
        }
    }
    
    ,bindEvents:function(){
        var obj = $( this.getObjSelector(this.config.basketCls,this.config.basketClsVal) )
            ,scope = this
        ;
        // bind good del event
        obj.on('click',this.getObjSelector(this.config.behavCls,this.config.behavior.goodDelCls),function(e){
            e.stopPropagation();
            scope.onGoodDel.call(this,scope);
            return false;    
        });
        
        // bind recount order Event
        obj.on('change',this.getObjSelector(this.config.behavCls,this.config.behavior.goodNumCls),function(e){
            e.stopPropagation();
            scope.onGoodRecount.call(this,scope);
            return false;    
        });
        
    }
    ,onGoodDel: function(scope){
        obj = this;
        if(!(obj instanceof $)) obj = $(obj);
        
        good = obj.parents(scope.getObjSelector(scope.config.goodCls,scope.config.goodClsVal));        
        
        scope.config.RequestProvider.run.call(scope,{
            action: scope.config.action.goodDel || 'products/remove'
            ,data:{product_key:good.data(scope.config.cmpFlag+'-'+scope.config.goodIDCls)}
            ,callbackName:'onProductDeleteFromOrder'
        })
    }
    ,onGoodRecount: function(scope){
        obj = this;
        if(!(obj instanceof $)) obj = $(obj);
        
        data = obj.parents('form').serializeArray();   
        
        // исключаем экшн, подбираемый в форме, чтобы не было конфликтов запросов
        var nData = [];
        for(var i in data){
            if(data[i].name != 'action') nData.push(data[i]);    
        }
        
        scope.config.RequestProvider.run.call(scope,{
            action: scope.config.action.refresh || 'recalculate'
            ,data:nData
            ,callbackName:'onOrderRecount'
        })
    }
    ,initialize:function(){
        this.bindEvents();
    }
};
F.mixin(ShopMODX.widgets._Order.prototype, Object.create(_ShopMODX.prototype));