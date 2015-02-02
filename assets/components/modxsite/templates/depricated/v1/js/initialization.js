// default providers setup
_ShopMODX.prototype.config.InformerProvider = ShopMODX.modules.Informer;
_ShopMODX.prototype.config.CallbackProvider = ShopMODX.modules.Callback;

ShopMODX.modules.Request = new ShopMODX.modules.Request();
_ShopMODX.prototype.config.RequestProvider = ShopMODX.modules.Request;

$(function(){
    // from events.js
    hideInactiveControls();
    // eof events.js 
    
    // Mini-Basket Widget initialization    
    ShopMODX.widgets.Basket = new ShopMODX.widgets.Basket({
        basketCls:'basket'
        ,basketClsVal:'minibasket'
        ,dataCls:'data'
        ,dataClsVal:'cost'
        ,elements:{
            sumCls:'cost'
            ,numCls:'num'
            ,textCls:'text'
            ,curCls:'currency'
            ,curVal:'руб.'    
        }
        ,declensions:{
            one: 'товар'
            ,two: 'товарa'
            ,many: 'товаров'
        }
        ,connectorsUrl: 'assets/components/basket/connectors/'
        ,action:{
            refresh:'getdata'
            ,clear:'empty'
        }
        //,requestProvider: new Request()   // here you can redefine base Request module and Informer & Callback too
        //,callbackFlag: 'stopOnFalse'
        ,events:{
            'onMiniBasketRefresh':{
                onSuccess:function(resp){
                    if(resp.success){
                        ShopMODX.widgets.Basket.resetBasket(resp.object.sum,resp.object.total);    
                    }
                    return true;
                }
                //,onBefore:function(resp){console.log(resp)}
                //,onFailure:function(conn,err){console.log(resp)}
            },
            'onMiniBasketClear':{
                onSuccess:function(resp){
                    if(resp.success){
                        ShopMODX.widgets.Basket.resetBasket(resp.object.sum,resp.object.total);    
                    }
                    return true;
                }
            }
        }
    })
    
    // Order Widget initialization
    ShopMODX.widgets.Order = new ShopMODX.widgets.Order({
        basketCls:'basket'
        ,basketClsVal:'order'
        ,goodCls: 'item'
        ,goodClsVal: 'good'
        ,goodIDCls: 'item-id'
        ,behavCls:'behav'
        ,behavior:{
            goodDelCls:'goodDel'
            ,goodNumCls:'goodNum'
        }        
        ,action:{
            goodDel:'products/remove'
            ,refresh:'recalculate'
        }
        ,events:{
            'onOrderRecount':{
                onSuccess:function(resp){
                    if(resp.success){
                        ShopMODX.widgets.Basket.refresh();   
                    }
                    return true;
                }
            }
            ,'onProductDeleteFromOrder':{
                onSuccess:function(resp){
                    if(resp.success){
                        ShopMODX.widgets.Order.updateRows(resp.object.key);
                        ShopMODX.widgets.Basket.refresh();
                    }
                    return true;
                }
            }
        }
    });
    
    // Product Widget initialization
    ShopMODX.widgets.Product = new ShopMODX.widgets.Product({
        productCls: 'productcls'
        ,productClsVal: 'listproduct'
        ,ruler: 'button[type="submit"]'
        ,action: 'products/add'
        ,callbackFlag: 'stopOnFalse'
        ,events:{
            'onProductAddToCart':{
                onSuccess:function(resp){
                    if(resp.success){
                        ShopMODX.widgets.Basket.refresh();
                    }
                    return true;
                }    
            }    
        }
    });
    
    
    $("#loginLoginForm [type=submit]").on('click', function(){
        var form = $(this).parents('form:first');
        var data = form.serialize();
        
        new Request({
            callbackName: 'login'
            ,noSuccessMsg: true
        })
        .run({
            "url": "assets/components/modxsite/connectors/connector.php"
            ,data: data
            ,events: {
                "login":{ 
                    onSuccess: function(resp){
                        if(resp.success){
                            var url = window.location.href.replace(/\?.*/, '');
                            window.location.replace(url);
                        }
                    }
                }
            }
        });
    
        return false;
    });
    
    

})