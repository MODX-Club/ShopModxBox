var CartActions = require('../actions/CartActions');
var CartStore = require('../stores/CartStore');
var _ = require('lodash-node');
var numeral = require('../utils/numberFormat');


function getCartData() {
  return CartStore.getCartData();
}

var Cart = function(props) {
  this.props = {};
  this.initialize(props || {});
};

Cart.prototype = {
  initialize: function(props) {

    CartStore.addChangeListener(this.onChange.bind(this));

    _.merge(this.props, props);
  },
  processText: function(val) {
    if (val % 10 == 1 && val % 100 != 11) {
      return this.props.declensions.one;
    } else if (val % 10 >= 2 && val % 10 <= 4 && (val % 100 < 10 || val % 100 >= 20)) {
      return this.props.declensions.two;
    } else {
      return this.props.declensions.many;
    }
  },
  onChange: function() {
    var data = getCartData();

    var nodes = document.querySelectorAll([this.props.wrapper, ".cost"].join(' '));
    [].slice.call(nodes).map(function(el) {
      el.innerText = numeral(data.sum).format('0,0');
    }, this);

    nodes = document.querySelectorAll([this.props.wrapper, ".num"].join(' '));
    [].slice.call(nodes).map(function(el) {
      el.innerText = data.total;
    }, this);

    nodes = document.querySelectorAll([this.props.wrapper, ".text"].join(' '));
    [].slice.call(nodes).map(function(el) {
      el.innerText = this.processText(data.total);
    }, this);

  }
};

module.exports = Cart;
