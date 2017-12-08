"use_strict";
var Dispatcher, Flux, PayloadSources, _;

_ = require('lodash-node');
Flux = require('flux');
PayloadSources = require('../constants/PayloadSources');

Dispatcher = _.merge(new Flux.Dispatcher(), {
  _handleAction: function(action, source) {
    var payload;
    payload = {
      source: PayloadSources[source],
      action: action
    };
    return this.dispatch(payload);
  },
  handleServerAction: function(action) {
    return this._handleAction(action, 'SERVER_ACTION');
  },
  handleViewAction: function(action) {
    return this._handleAction(action, 'VIEW_ACTION');
  }
});

module.exports = Dispatcher;
