'use strict';

 
import Response from './components/response';

const debug = require('debug')("react-cms:slave-router");



/*
  OLD Router
*/

import ReactCmsRouter from 'react-cms/src/server/components/Router';

export default class Router extends ReactCmsRouter{


  constructor(options = {}){

    Object.assign(options, {
      Response,
    });

    super(options);

  }



  setReloadDataInterval(){

    // setInterval(this.loadData, 1000 * 300);

  }



  // async loadData(){


  //   // debug("loadData main");

  //   // knex.raw("SET SESSION group_concat_max_len = 10000000;").then().catch(e => {
  //   //   console.error("SET SESSION Error", e);
  //   // });

  //   // await this.loadApiData();

  //   // // this.loadMapData();

  //   // await this.loadCitiesData();

  //   // this.loadRedirects();

  //   this.inited = true;

  //   return true;
  // }
 
  getHeaderScripts(){

    return ``;

  }

}
