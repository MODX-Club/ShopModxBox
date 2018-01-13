
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


 
  getHeaderScripts(){

    return `
      <!-- Yandex.Metrika counter -->
      <script type="text/javascript" >
          (function (d, w, c) {
              (w[c] = w[c] || []).push(function() {
                  try {
                      w.yaCounter47291208 = new Ya.Metrika({
                          id:47291208,
                          clickmap:true,
                          trackLinks:true,
                          accurateTrackBounce:true,
                          webvisor:true,
                          trackHash:true
                      });
                  } catch(e) { }
              });

              var n = d.getElementsByTagName("script")[0],
                  s = d.createElement("script"),
                  f = function () { n.parentNode.insertBefore(s, n); };
              s.type = "text/javascript";
              s.async = true;
              s.src = "https://mc.yandex.ru/metrika/watch.js";

              if (w.opera == "[object Opera]") {
                  d.addEventListener("DOMContentLoaded", f, false);
              } else { f(); }
          })(document, window, "yandex_metrika_callbacks");
      </script>
      <noscript><div><img src="https://mc.yandex.ru/watch/47291208" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
      <!-- /Yandex.Metrika counter -->
    `;

  }

}
