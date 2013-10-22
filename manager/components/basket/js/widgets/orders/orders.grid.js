/*
    Управление заказами
*/ 
Shop.grid.OrdersGrid = function(config){
    config = config || {};
    
    this._loadExpander();
    
    Ext.applyIf(config, {
        title: 'Управление заказами'
        ,header: true
        ,url: Shop.config.connector_url + 'orders.php'
        ,paging: true
        ,remoteSort: true
        ,autosave: true
        ,pageSize:  10
        ,save_callback: this.save_callback
        ,fields: [
            'id'
            ,'order_id'
            ,'menu'
            ,'order_num'
            ,'status_id'
            ,'status_str'
            ,'createdon'
            ,'editedon'
            ,'contractor_fullname'
            ,'contractor_email'
            ,'contractor_phone'
            ,'manager_fullname'
            ,'manager'
            ,'ip'
            ,'address'
            ,'comments'
        ]
        ,cls: 'orders-grid'
        ,plugins: this.expander
    });
    
    Shop.grid.OrdersGrid.superclass.constructor.call(this, config);
    
};

Ext.extend(Shop.grid.OrdersGrid, MODx.grid.Grid,{
    
    
    // Прогоняем полученные данные по записям
    
    
    save_callback: function(response){
        var object = response.object;
        var record  = this.getStore().getById(object.id);
        if(record){
            for(var i in object){
                record.set(i, object[i]);
            }
            record.commit();
        }
        return;
    } 
    
    ,save_callback_depricated: function(response){
        for(var x in response.object){
            var object = response.object[x];
            var record  = this.getStore().getById(object.id);
            if(record){
                for(var i in object){
                    record.set(i, object[i]);
                }
                record.commit();
            }
        }
        return;
    } 
    
    ,_loadExpander: function(){
        this.expander = new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                '<div id="order-grid-{id}"></div>'
            )
            ,listeners:{
                render: function(){
                    console.log(this);
                },
                expand: function(exp, record, body, rowIndex){
                    var id = record.get('id');
                    
                    new Shop.grid.OrderDataGrid({
                        renderTo: Ext.get('order-grid-' + id)
                        ,order_id: id
                    });
                    
                },
                scope: this
            }
            ,sortable:  false
            ,expandOnDblClick: false
        });
        this.expander.OrdersGrids = {};
        return;
    }
    
    ,_loadColumnModel: function(){
        this.cm = new Ext.grid.ColumnModel({
            grid: this
            ,defaults: {
                width: 120,
                sortable: true
            },
            columns: [
                this.expander
                ,{header: 'ID', dataIndex: 'id', width: 50}
                ,{header: 'Дата создания', dataIndex: 'createdon'}
                ,{header: 'Дата изменения', dataIndex: 'editedon', hidden: true}
                ,{
                    header: 'Статус'
                    ,dataIndex: 'status_id'
                    ,renderer: function(value, cell, record){
                        return record.get('status_str');
                    }
                    ,editable: true
                }
                /*,{
                    header: 'IP'
                    ,dataIndex: 'ip'
                }*/
                ,{
                    header: 'ФИО'
                    ,dataIndex: 'contractor_fullname'
                    ,renderer: this.hiderRenderer
                    ,editable: true
                }                
                ,{
                    header: 'Телефон'
                    ,dataIndex: 'contractor_phone'
                    ,renderer: this.hiderRenderer
                    ,editable: true
                }
                ,{
                    header: 'Емейл'
                    ,dataIndex: 'contractor_email'
                    ,renderer: this.hiderRenderer
                    ,editable: true
                }
                ,{
                    header: 'Адрес'
                    ,dataIndex: 'address'
                    ,renderer: this.hiderRenderer
                    ,editable: true
                }
                ,{
                    header: 'Комментарии'
                    ,dataIndex: 'comments'
                    ,renderer: this.hiderRenderer
                    ,editable: true
                }
                ,{
                    header: 'Менеджер'
                    ,dataIndex: 'manager_fullname'
                }
                /*,{
                    header: 'Внутренний номер'
                    ,dataIndex: 'order_num'
                    ,hidden: true
                }*/
            ]
            ,getCellEditor: this.getCellEditor
        });
        return;
    }
    
    ,getCellEditor: function(colIndex, rowIndex) {
        var xtype = 'textfield';
        var record = this.grid.store.getAt(rowIndex);
        var column = this.getColumnAt(colIndex);
        //console.log(column);
        if(!Shop.hasPermission('edit_all_orders') && MODx.user.id != record.get('manager')){
            return;
        }
        
        
        var fieldName = this.getDataIndex(colIndex);
        //console.log(fieldName);
        
        switch(fieldName){
            // Проверяем право редактирования данных клиента
            case 'contractor_email':
            case 'contractor_phone':
            case 'contractor_fullname':
                if(!Shop.hasPermission('edit_clients_data')){
                    return;
                }
                break;
            case 'status_id':
                // Если заявка новая, не позволяем менять статус
                if(record.get('status_id') == 1){
                    return;
                }
                xtype = 'shop-combo-orderstatus';
                break;
            case 'address':
            case 'comments':
                this.grid.showEditWindow(column, record);
                return;
        }
        
         
        var o = MODx.load({
            xtype: xtype
        });
        
        return new Ext.grid.GridEditor(o);
    }
    
    ,showEditWindow: function(column, record){
        new MODx.Window({
            title: column.header
            ,width: 'auto'
            ,url: this.url
            ,action: 'update'
            ,fields:[
                {
                    xtype: 'hidden'
                    ,name: 'order_id'
                    ,value: record.get('order_id')
                }
                ,{
                    xtype: 'htmleditor'
                    ,hiddenName: column.dataIndex
                    ,name: column.dataIndex
                    ,value: record.get(column.dataIndex)
                }
            ]
            ,scope: this
            ,success: function(){
                this.refresh();
            }
        })
            .show();
    }
    
    ,hiderRenderer: function(value, cell, record){
        if(!value){
            value = 'Не указано';
        }
        else if(!Shop.hasPermission('view_all_orders') && MODx.user.id != record.get('manager')){
            value = '********';
        }
        return value;
    }
    
    // Принимаем заказ
    ,takeOrder: function(a,b){
        var recordData = this.menu.record;
        MODx.msg.confirm({
            text: 'Принять заявку?'
            ,url: Shop.config.connector_url + 'orders.php?action=update/takeorder'
            ,params : {
                'order_id': recordData.id
            }
            ,listeners:{
                success: {'fn': this.onUpdateSuccess, scope: this}
            }
        });
    }
    
    // Меняем статус заказа
    ,updateOrderStatus: function(a,b){
        var recordData = this.menu.record;
        var win = new MODx.Window({
            url: Shop.config.connector_url + 'orders.php'
            ,action: 'update/updatestatus'
            
            ,bodyStyle: 'padding: 20px 0 0 10px;'
            ,width: 300
            ,fields:[{
                xtype: 'shop-combo-orderstatus'
                ,value: recordData.status_id
                ,fieldLabel: 'Статус'
                ,hiddenName: 'new_status'
            },{
                xtype: 'hidden',
                name: 'order_id',
                value: recordData.id
            }]
            ,listeners:{
                success: {'fn': function(response, form){
                    if(!this.onUpdateSuccess(response.a.result)){
                        return;
                    }
                    win.close();
                }, scope: this}
            }
        });
        win.show();
        return;
    }
    
    // Удаление заявок
    ,deleteOrder: function(a,b){
        var recordData = this.menu.record;
        console.log(recordData);
        MODx.msg.confirm({
            text: 'Удалить заявку?'
            ,url: Shop.config.connector_url + 'orders.php'
            ,params:{
                action: 'remove'
                ,order_id: recordData.id
            }
            ,listeners:{
                success: {
                    'fn': function(response, form){
                        console.log(this);
                        this.refresh();
                    }
                    ,scope: this
                }
            }
        });
        return;
    }
    
    ,onUpdateSuccess: function(response){
        if(!response.success){
            MODx.msg.alert(response.message || 'Ошибка выполнения запроса');
            return false;
        }
        
        this.refresh();
        return;
    }
});
Ext.reg('shop-grid-ordersgrid',Shop.grid.OrdersGrid);


/*
Shop.grid.OrderDataGrid
Данные заказа (товары)
*/

Shop.grid.OrderDataGrid = function(config){
    config = config || {}; 
    
    Ext.applyIf(config, {
        url: Shop.config.connector_url + 'orderdata.php'
        ,border: false
        ,paging: true
        ,remoteSort: false
        ,fields: [
            'object_id'     // ID документа товара
            ,'pagetitle'
            //,'color'
            //,'size'
            //,'design'
            ,'order_quantity'
            ,'order_price'
            ,'uri'
            ,'model_url'
            ,'image'
            ,'image_empty'
        ]
        ,baseParams:{ 
            action: config.action || 'getList'
            ,order_id: config.order_id || null
        }
    });
    
    Shop.grid.OrderDataGrid.superclass.constructor.call(this, config);
};

Ext.extend(Shop.grid.OrderDataGrid, MODx.grid.Grid,{
     
    
    _loadColumnModel: function(){
        this.cm = new Ext.grid.ColumnModel({
            defaults: {
                width: 120,
                sortable: false
            },
            columns: [
                {header: 'ID товара', dataIndex: 'object_id', width: 50}
                ,{header: 'Изображение', dataIndex: 'image', renderer: function(value, column, record){
                    /*console.log(column);
                    console.log(record);
                    console.log(value);*/
                    if(!value){
                        value = record.get('image_empty');
                    }
                    return '<img style="width:50px" src="' +value+'"/>';
                }}
                ,{header: 'Товар', dataIndex: 'pagetitle', renderer: function(value, column, record){
                    return '<a href="'+MODx.config.site_url+record.get('uri')+'" target="_blank">'+value+'</a>';
                    // return '<a href="'+record.get('model_url')+'" target="_blank">'+value+'</a>';
                }}
                //,{header: 'Цвет', dataIndex: 'color'}
                //,{header: 'Размер', dataIndex: 'size'}
                //,{header: 'Исполнение', dataIndex: 'design'}
                ,{header: 'Количество', dataIndex: 'order_quantity'}
                ,{header: 'Цена', dataIndex: 'order_price'}
            ],
        });
        return;
    }
});
Ext.reg('shop-grid-orderdatagrid',Shop.grid.OrderDataGrid);

