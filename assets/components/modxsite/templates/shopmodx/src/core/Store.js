var EventEmitter = require('events').EventEmitter;
var _ = require('lodash-node');
var CHANGE_EVENT = 'change';

var Store = function(params) {
  _.merge(this, EventEmitter);
  _.merge(this, params);
  this.dispatchToken = null;
};

Store.prototype = Object.create(EventEmitter.prototype);

Store.prototype.emitChange = function() {
  return this.emit(CHANGE_EVENT);
};

Store.prototype.addChangeListener = function(callback) {
  return this.on(CHANGE_EVENT, callback);
};

Store.prototype.removeChangeListener = function(callback) {
  return this.removeListener(CHANGE_EVENT, callback);
};

module.exports = Store;
