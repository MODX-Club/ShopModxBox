
import ShopmodxRouter from 'modules/Shopmodx/server/routes/main';

import {
  MainApp,
} from "modules/Shopmodx/components/App/";


import configureStore from 'modules/Site/config/store';
import routes from "modules/Site/config/routes";


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



export default class SiteRouter extends ShopmodxRouter{


  constructor(options = {}){

    Object.assign(options, {
      config,
      db_config,
      site_url,
      configureStore,
      routes,
      MainApp,
    });

    super(options);

  }

}
