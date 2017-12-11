/* eslint consistent-return:0 */

const path = require('path');

require('babel-core/register')({

  // sourceRoot: '/old_disk/home/fi1osof/git/',
  // sourceRoot: '/',
  ignore: function(file){
    if(/\/node_modules\//.test(file)){
      if(
        /\/material-ui-components\//.test(file)
        || /\/material-ui\//.test(file)
        || /\/react-cms-data-view\//.test(file)
        || /\/structor-templates\//.test(file)
        || /\/react-decliner\//.test(file)
        || /\/google-map-react\//.test(file)
        || /\/react-progress-button\//.test(file)
        || /\/react-cms-draft-wysiwyg\//.test(file)
        || /\/react-schedule\//.test(file)
        || /\/react-cms\//.test(file)
        || /\/moment\//.test(file)
        || /\/react-draft-wysiwyg\//.test(file)
        || /\/shopmodx-react\//.test(file)
      ){
        return;
      }

      return true;
    }
  },
	
});

['.css', '.less', '.sass', '.ttf', '.woff', '.woff2', '.svg'].forEach((ext) => require.extensions[ext] = () => {});
// ['.css', '.less', '.sass', '.ttf', '.woff', '.woff2'].forEach((ext) => require.extensions[ext] = () => {});
require('babel-polyfill');

const express = require('express');
const logger = require('./logger');

const argv = require('minimist')(process.argv.slice(2));
const setup = require('./middlewares/frontendMiddleware');
const isDev = process.env.NODE_ENV !== 'production';
const resolve = require('path').resolve;
const app = express();

var bodyParser = require('body-parser');

app.use(bodyParser.urlencoded({     // to support URL-encoded bodies
  extended: true
})); 
app.use(bodyParser.json());       // to support JSON-encoded bodies

// In production we need to pass these values in instead of relying on webpack
// var s = 
setup(app, {
	outputPath: resolve(process.cwd(), 'build'),
	publicPath: '/build/',
});


// var router = require('./routes/main')({
//   app: app,
//   // host: config.host,
//   // raw_host_port: config.raw_host_port,
//   // path: config.path,
//   // hot_reload_debug: config.hot_reload_debug,
//   // hot_reload_port: config.hot_reload_port,
// });

// // // If you need a backend, e.g. an API, add your custom backend-specific middleware here
// // // app.use('/api', myApi);

// app.use(router);

// console.log('s result', s);


// get the intended port number, use port 3000 if not provided
const port = argv.port || process.env.PORT || 3000;

// Start your app.
app.listen(port, (err) => {
	if (err) {
		return logger.error(err.message);
	}
	logger.appStarted(port);
});
