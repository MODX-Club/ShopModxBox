var OrderActions = require('../actions/OrderActions');
var OrderStore = require('../stores/OrderStore');
var _ = require('lodash-node');
var serialize = require('form-serialize');

function _getParent(e, nodeName) {
  var parent = e.target;
  do parent = parent.parentNode;
  while (parent.nodeName !== nodeName.toUpperCase() || parent === null);
  return parent;
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
    var node = document.querySelector(this.props.wrapper);
    if (!node) return;

    node.addEventListener('click', (function(_this) {
      return function(e) {
        if (e.target.getAttribute('data-smodx-behav') !== _this.props.behaviour.delete) return;
        e.preventDefault();

        // delete good
        _this.shouldRemoveGoodFromOrder(e);

      };
    })(this), true);

    node.addEventListener('change', (function(_this) {
      return function(e) {
        if (e.target.getAttribute('data-smodx-behav') !== _this.props.behaviour.change) return;
        e.preventDefault();

        // delete good
        _this.shouldChangeGoodQuantityInOrder(e);

      };
    })(this), true);


  },
  shouldRemoveGoodFromOrder: function(event) {
    var node = _getParent(event, 'tr');

    var data = {
      product_key: node.getAttribute(this.props.idAttr)
    };

    node.remove();

    this.handleRemoveGoodFromOrderAction(data, event);
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
    if (!document.querySelectorAll(this.props.item).length) location.reload();

  }
};

module.exports = Order;
