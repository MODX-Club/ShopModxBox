// for multiple pages
require('events').EventEmitter.prototype._maxListeners = 100;

require('coffee-script/register');
var requireDir = require('require-dir');

// Require all tasks in gulp/tasks, including subfolders
a = requireDir('./gulp/tasks', {
  recurse: true
});
