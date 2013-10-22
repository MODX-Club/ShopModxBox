var Shop = function(config){
    config = config || {};
    Shop.superclass.constructor.call(this,config);
};
Ext.extend(Shop,Ext.Component,{
    config: {}
    ,tree: {}
    ,combo: {}
    ,tabs: {}
    ,panel: {}
    ,grid: {}
    ,policies:{}
    
    ,hasPermission: function(police){
        return this.policies[police] || false;
    }
});
Ext.reg('shop',Shop);

var Shop = new Shop();