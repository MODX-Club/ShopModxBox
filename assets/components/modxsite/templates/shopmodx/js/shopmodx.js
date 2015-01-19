_ShopMODX = function(config){
    config = config || {};
}
_ShopMODX.prototype = {
    // default config
    config:{
        cmpFlag:'smodx'
        ,connectorsUrl:'assets/components/basket/connectors/'
        ,connector: 'connector.php'
    }
    ,getConfig:function(){
        return this.config;
    }
}

ShopMODX = function(config){    
    config = config || {};
    this.config = F.applyIf(config,this.config);  
    ShopMODX.superclass.constructor.call(this,config);
}
F.extend(ShopMODX,_ShopMODX,{
    // here you can redefine prototype methods
});

ShopMODX = new ShopMODX();

// init modules, etc.
F.applyIf(ShopMODX,{
    modules:{}
    ,widgets:{}
})

/* Further inheritance example */
/*
ShopMODX.prototype = Object.create(_ShopMODX.prototype);
N = function(config){
    config = config || {};
    this.config = F.applyIf(config,this.config);    // redefine base object settings
    N.superclass.constructor.call(this,config);    
}
F.extend(N,ShopMODX);
N = new N();
*/



