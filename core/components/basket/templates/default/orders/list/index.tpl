<div id="orders-list-block"></div>
<script type="text/javascript">
    Ext.onReady(function(){
        var ordersgrid = new Shop.grid.OrdersGrid({
            renderTo: Ext.get('orders-list-block')
        });
    });
</script>