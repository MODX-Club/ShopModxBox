

// Needed for redux-saga es6 generator support
import "babel-polyfill";

import locale from 'moment/src/locale/ru';

import React from "react";
import ReactDOM from "react-dom";
import { Provider } from "react-redux";
import { applyRouterMiddleware, Router, browserHistory } from "react-router";
import { useScroll } from "react-router-scroll";
// Import routes
import rootRoute from "modules/Shopmodx/config/routes";

import configureStore from "modules/Shopmodx/config/store";
// Create redux store with history
// this uses the singleton browserHistory provided by react-router
// Optionally, this could be changed to leverage a created history
// e.g. `const browserHistory = useRouterHistory(createBrowserHistory)();`
let initialState = {};

import {MainApp} from 'modules/Shopmodx/components/App';

if(
  typeof window !== 'undefined'
  && window.REDUX_INITIAL_STATE
){
  initialState = window.REDUX_INITIAL_STATE;
}


const store = configureStore(initialState);

// alert("sdfsdf");

// import jQuery from 'jquery';

// // global.jQuery = jQuery;
// // global.$ = jQuery;

// // console.log('jQuery', jQuery);


// if(typeof window !== "undefined"){
//   window.jQuery = jQuery;
// }


// const bootstrap = require('bootstrap')(jQuery);
// import 'bootstrap/dist/js/bootstrap';

if(typeof window !== "undefined"){

  browserHistory.listen(function (location) {
    window.ga && window.ga('send', 'pageview', location.pathname);
  });

  const render = () => {
    ReactDOM.render(
      <MainApp
        appExports={{}}
      >
        <Provider store={store}>
          <Router
            history={browserHistory}
            routes={rootRoute}
            render={applyRouterMiddleware(useScroll())}
          />
        </Provider>
      </MainApp>,
      document.getElementById("root")
    );
  };

  render();

  if (module.hot) {
    // modules.hot.accept does not accept dynamic dependencies,
    // have to be constants at compile-time
    module.hot.accept(["modules/Shopmodx/config/routes"], () => {
      render();
    });
  }
  
}



