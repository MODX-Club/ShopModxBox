"use_strict";
var ActionTypes, Dispatcher;

Dispatcher = require("../core/Dispatcher");
ActionTypes = require("../constants/ActionTypes");

module.exports = {
  didRemoveGood: function(data) {
    Dispatcher.handleServerAction({
      data: data,
      actionType: ActionTypes.ORDER_REFRESH
    });
  }
};
