"use_strict";
var ActionTypes, Dispatcher;

Dispatcher = require("../core/Dispatcher");
ActionTypes = require("../constants/ActionTypes");

module.exports = {
  didAddToCart: function(data) {
    Dispatcher.handleServerAction({
      data: data,
      actionType: ActionTypes.GOOD_ADD
    });
  }
};
