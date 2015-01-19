var GoodsServerActions = require('../actions/GoodsServerActions');
var CartServerActions = require('../actions/CartServerActions');
var OrderServerActions = require('../actions/OrderServerActions');
var ActionTypes = require("../constants/ActionTypes");

var Request = require('./request');
var Env = require('../constants/Env');
var Informer = require('./informer');

Informer = new Informer();

Request = Request.init({
  connectorsUrl: Env.connectorsUrl,
  connector: Env.connector,
  actionKey: 'action'
});

Request.onSuccess = Informer.success;
Request.onFailure = Informer.failure;

function _debug(callback) {
  if (location.hash.match('!dev') !== null) {
    callback();
  }
}

function _catchError(error) {
  // throw error
  _debug(function() {
    console.log(error);
  });
}

var API = {
  addGoodToCart: function(params) {
    return Request
      .run(ActionTypes.GOOD_ADD, params.data)
      .then(function(args) {

        // good add stuff
        if (args.success) {
          GoodsServerActions.didAddToCart(args.response.object);
          return API.cartRefresh();
        }

      })
      .catch(_catchError)
      .then(function() {})
    ;
  },
  cartReset: function(params) {
    return Request
      .run(ActionTypes.CART_RESET)
      .then(function() {
        if (args.success) CartServerActions.didCartReset(args.response.object);
      })
      .catch(_catchError)
    ;
  },
  cartRefresh: function(params) {
    return Request
      .run(ActionTypes.CART_REFRESH)
      .then(function(args) {
        // cart refresh stuff
        if (args.success) CartServerActions.didCartRefresh(args.response.object);
      })
      .catch(_catchError)
    ;
  },
  removeGood: function(params) {
    return Request
      .run(ActionTypes.GOOD_REMOVE, params.data)
      .then(function(args) {
        // cart refresh stuff
        if (args.success) {
          // removeRow
          OrderServerActions.didRemoveGood(args.response.object);
          return API.cartRefresh();
        }
      })
      .catch(_catchError)
    ;
  },
  recountOrder: function(params) {
    return Request
      .run(ActionTypes.ORDER_RECOUNT, params.data)
      .then(function(args) {
        // cart refresh stuff
        if (args.success) {
          return API.cartRefresh();
        }
      })
      .catch(_catchError)
    ;
  },
};

module.exports = API;
