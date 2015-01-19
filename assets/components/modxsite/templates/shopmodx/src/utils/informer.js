var Alertify = require('Alertify');

var Informer = function(props) {
  this.props = {};
  this.initialize(props || {});
};

Informer.prototype = {
  initialize: function(props) {},
  success: function(msg) {
    if (msg)
      Alertify.success(msg);
  },
  failure: function(msg) {
    if (msg)
      Alertify.error(msg);
  }
};


module.exports = Informer;
