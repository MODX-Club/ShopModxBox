'use strict';


// import React    from 'react';
// import ReactDom from 'react-dom/server';
// import { match, RouterContext } from 'react-router';
// import { Provider } from "react-redux";

// const fs = require('fs');

import configureStore from 'modules/Shopmodx/config/store';
import routes from "modules/Shopmodx/config/routes";

import {
  MainApp,
} from "modules/Shopmodx/components/App/";

// var Model = require('objection').Model;
 
import Response from './components/response';

const debug = require('debug')("react-cms:slave-router");

// import md5 from 'md5';

// import fetch from 'node-fetch';

// const geoip = require('geoip-lite');

// const FormData = require('form-data');


import config, {
  db as db_config,
  host,
  site_url,
} from '../../config/config'; 


let {
  connection: {
    prefix,
  },
} = db_config;

// const knex = require('knex')(db_config);
 
// let styles = {};

 

// let apiData;
// let mapData;
// let citiesData;


/*
  OLD Router
*/

import ReactCmsRouter from 'react-cms/src/server/components/Router';

export default class Router extends ReactCmsRouter{


  constructor(options = {}){

    Object.assign(options, {
      config,
      db_config,
      site_url,
      configureStore,
      routes,
      MainApp,
      Response,
    });

    super(options);

    // this.router = this.createRouter(options);

    // console.log("this.router", this.router);
  }



  setReloadDataInterval(){

    // setInterval(this.loadData, 1000 * 300);

  }



  async loadData(){


    // debug("loadData main");

    // knex.raw("SET SESSION group_concat_max_len = 10000000;").then().catch(e => {
    //   console.error("SET SESSION Error", e);
    // });

    // await this.loadApiData();

    // // this.loadMapData();

    // await this.loadCitiesData();

    // this.loadRedirects();

    this.inited = true;

    return true;
  }
 
  getHeaderScripts(){

    return ``;

  }

}
