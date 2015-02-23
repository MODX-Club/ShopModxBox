var OrderActions = require('../actions/OrderActions');
var OrderStore = require('../stores/OrderStore');
var _ = require('lodash-node');
var serialize = require('form-serialize');

function _getParent(e, nodeName) {
  // var parent = e.target;
  // do parent = parent.parentNode;
  // while (parent.nodeName !== nodeName.toUpperCase() || parent === null);
  // 
  // console.log('sdfsdfsdf');
  var parent = $(e.target).parents(nodeName + ':first'); 
  
  // console.log('sdfsdfsdf');
  // console.log(parent);
  return parent[0] || null;
}

var Order = function(props) {
  this.props = {};
  this.initialize(props || {});
};

Order.prototype = {
  initialize: function(props) {
    OrderStore.addChangeListener(this.onChange.bind(this));

    _.merge(this.props, props);
    this.bindEvents();
  },
  bindEvents: function() {
      // console.log(this.props.wrapper);
    var node = $(this.props.wrapper);
    if (!node.length) return;


    $('body').on('click', this.props.wrapper + ' [data-smodx-behav]', this, function(e) { 
        
        var _this = e.data; 
        var el = $(this); 
        
        switch(el.attr('data-smodx-behav')){
            
            case _this.props.behaviour.delete:
                _this.shouldRemoveGoodFromOrder(e);
                break; 
            
            default: return true;
        }
        
        return false;
    });  


    $('body').on('change', this.props.wrapper + ' [data-smodx-behav]', this, function(e) {
         
        var _this = e.data;
         
        var el = $(this); 
        
        switch(el.attr('data-smodx-behav')){ 
            
            case _this.props.behaviour.change:
                _this.shouldChangeGoodQuantityInOrder(e);
                break;
            
            default: return true;
        }
        
        return false;
    });  
  },
  
//   bindEvents: function() {
//     var node = document.querySelector(this.props.wrapper);
//     if (!node) return;
// 
//     node.addEventListener('click', (function(_this) {
//       return function(e) {
//           // console.log(e);
//           // console.log(this);
//         if (e.target.getAttribute('data-smodx-behav') !== _this.props.behaviour.delete) return;
//           // console.log(e);
//           // console.log(this);
//         e.preventDefault();
// 
//         // delete good
//         _this.shouldRemoveGoodFromOrder(e);
// 
//       };
//     })(this), true);
// 
//     node.addEventListener('change', (function(_this) {
//       return function(e) {
//         if (e.target.getAttribute('data-smodx-behav') !== _this.props.behaviour.change) return;
//         e.preventDefault();
// 
//         // delete good
//         _this.shouldChangeGoodQuantityInOrder(e);
// 
//       };
//     })(this), true);
// 
// 
//   },
  
  
  shouldRemoveGoodFromOrder: function(event) {
    // console.log('shouldRemoveGoodFromOrder');
    
    
    // Получаем элемент, по которому кликнули.
    var current = $(event.target);
    
    // Получаем id товара
    var parent = current.parents('[data-smodx-item="good"]:first');
    
    var id = parent.attr('data-smodx-item-id');
    
    $('[data-smodx-item-id="'+id+'"]').remove();
    
    var data = {
      product_key: id
    };
    
    this.handleRemoveGoodFromOrderAction(data, event);
    return; 
  },
  shouldChangeGoodQuantityInOrder: function(event) {
    var node = _getParent(event, 'form');

    /*data = serialize(node, {
      serializer: function(curry, name, value) {
        if (name !== 'basket_action')
          curry[name] = value;
        return curry;
      }
    });
    
    console.log(data);*/
    
    data = $(node).serialize();
    
    // console.log(data);

    this.handleChangeGoodQuantityInOrderAction ((data), event);

  },
  handleRemoveGoodFromOrderAction: function(data, event) {
    OrderActions.shouldRemoveGood({
      data: data
    });
  },
  handleChangeGoodQuantityInOrderAction: function(data, event) {
    OrderActions.shouldRecountOrder({
      data: data
    });
  },
  onChange: function() {
    // if empty cart
    // console.log('sdfsdfs');
    
    if (!document.querySelectorAll(this.props.item).length) location.reload();

  }
};

module.exports = Order;
