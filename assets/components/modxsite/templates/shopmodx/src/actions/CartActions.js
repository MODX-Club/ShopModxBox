"use_strict";

var Dispatcher = require("../core/Dispatcher");
var API = require("../utils/API");
// var ActionTypes = require("../constants/ActionTypes");

module.exports = {
  shouldReset: API.cartReset,
  shouldRefresh: API.cartRefresh
};
