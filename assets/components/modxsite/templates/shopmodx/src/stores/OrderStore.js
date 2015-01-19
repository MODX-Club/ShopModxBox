"use_strict";
var ActionTypes, Dispatcher, OrderStore, Store, _store;

Store = require("../core/Store");
Dispatcher = require("../core/Dispatcher");
ActionTypes = require("../constants/ActionTypes");

_store = {};

OrderStore = new Store();

OrderStore.dispatchToken = Dispatcher.register(function(payload) {
  var action = payload.action;

  switch (action.actionType) {
    case ActionTypes.ORDER_REFRESH:
      OrderStore.emitChange();
      break;
  }

  return true;
});

module.exports = OrderStore;
