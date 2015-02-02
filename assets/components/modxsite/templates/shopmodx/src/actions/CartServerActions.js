"use_strict";
var ActionTypes, Dispatcher;

Dispatcher = require("../core/Dispatcher");
ActionTypes = require("../constants/ActionTypes");

module.exports = {
  didCartRefresh: function(data) {
    Dispatcher.handleServerAction({
      data: data,
      actionType: ActionTypes.CART_REFRESH
    });
  },
  didCartReset: function(data) {
    Dispatcher.handleServerAction({
      data: data,
      actionType: ActionTypes.CART_RESET
    });
  }
};
