/*
    Общая панель-заготовка для панелей с заголовками
*/
shopModxGroupEdit.panel.Panel = function(config){
    config = config || {};
    
    Ext.applyIf(config, {
        border: false
        ,baseCls: 'modx-formpanel container'
        ,items: []
    });
    
    if(config.paneltitle){
        config.items.splice(0,0,{
            html: '<h2>'+config.paneltitle+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        });
    }
    
    shopModxGroupEdit.panel.Panel.superclass.constructor.call(this, config);
};

Ext.extend(shopModxGroupEdit.panel.Panel, MODx.Panel, {});
Ext.reg('shopmodxgroupedit-panel-panel', shopModxGroupEdit.panel.Panel);


/*
    Главная панель для группового редактора
*/
shopModxGroupEdit.panel.MainPanel = function(config){
    config = config || {};
    
    Ext.applyIf(config, {
        paneltitle: 'Групповое редактирование'
        ,items:[
            {
                xtype: 'shopmodxgroupedit-tabs-groupedit'
            }
        ]
    });
    
    shopModxGroupEdit.panel.MainPanel.superclass.constructor.call(this, config);
};

Ext.extend(shopModxGroupEdit.panel.MainPanel, shopModxGroupEdit.panel.Panel, {});
Ext.reg('shopmodxgroupedit-panel-mainpanel', shopModxGroupEdit.panel.MainPanel);