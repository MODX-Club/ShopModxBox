"use_strict";
var ActionTypes, Dispatcher, CartStore, Store, _store;

Store = require("../core/Store");
Dispatcher = require("../core/Dispatcher");
ActionTypes = require("../constants/ActionTypes");

_store = {
  total: 0,
  sum: 0
};

CartStore = new Store({
  getCartData: function() {
    return _store;
  }
});

CartStore.dispatchToken = Dispatcher.register(function(payload) {
  var action = payload.action;

  switch (action.actionType) {
    case ActionTypes.GOOD_ADD:
      break;

    case ActionTypes.CART_REFRESH:
      _store.total = action.data.total;
      _store.sum = action.data.sum;

      CartStore.emitChange();
      break;

    case ActionTypes.CART_RESET:
      _store.total = 0;
      _store.sum = 0;

      CartStore.emitChange();
      break;
  }

  return true;
});

module.exports = CartStore;
