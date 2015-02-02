Cart = require("./Cart");

var ExtendedCart = function(props) {
  Cart.call(this, props || {});
};

ExtendedCart.prototype = Object.create(Cart.prototype);

module.exports = ExtendedCart;
