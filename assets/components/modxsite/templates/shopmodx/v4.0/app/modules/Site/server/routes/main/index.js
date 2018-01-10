
import ShopmodxRouter from 'shopmodx-react/server/routes/main';

import {
  MainApp,
} from "modules/Site/components/App/";


import configureStore from 'modules/Site/config/store';
import routes from "modules/Site/config/routes";
import defaultQuery from 'modules/Site/components/ORM/query';
import rootResolver from 'modules/Site/components/ORM/resolver';
 
import Response from './components/response';

import RootType, {
  Mutation,
  rootDirectives,
} from 'modules/Site/components/ORM';


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

    options = Object.assign({
      config,
      db_config,
      site_url,
      configureStore,
      routes,
      MainApp,
      defaultQuery,
      rootResolver,
      RootType,
      Mutation,
      rootDirectives,
      Response,
    }, options);

    super(options);

  }

}
