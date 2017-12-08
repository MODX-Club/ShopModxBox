
var debug = require('debug')("react-cms:response");

import moment from 'moment';

moment.locale('ru');
  
const querystring = require('querystring');

const httpServ = require('http');

import fetch from 'node-fetch';

var FormData = require('form-data');

import {
  db as db_config,
  site_url,
} from '../../../../config/config'; 

// debug('config host', host);


import jsdom from 'jsdom';

const { JSDOM } = jsdom;


const w = (new JSDOM(`<!DOCTYPE html>`)).window;

const {
    document,
    HTMLElement,
    HTMLAnchorElement,
    HTMLImageElement,
} = w;

global.document = document;
global.HTMLElement = HTMLElement;
global.HTMLAnchorElement = HTMLAnchorElement;
global.HTMLImageElement = HTMLImageElement;

const doc = document.implementation.createHTMLDocument('div');

    
let {
  connection: {
    prefix,
  },
} = db_config;

import {
  buildSchema,
  introspectionQuery,
  graphql,
  GraphQLSchema,
  GraphQLObjectType,
  GraphQLInt,
  GraphQLFloat,
  GraphQLString,
  GraphQLBoolean,
  GraphQLList,
  GraphQLNonNull,
  GraphQLID,
  GraphQLEnumType,
} from 'graphql';


import {
  listArgs,
  ObjectsListType,
} from 'modules/Site/components/ORM/fields';



import RootType, {
  Mutation,
  rootDirectives,
} from 'modules/Site/components/ORM';


import * as ORM from 'modules/Site/components/ORM';


import {
  createStores,
  initData,
  createStoreObject,
} from 'modules/Site/components/App';

import localResolver from 'modules/Site/components/ORM/resolver';

import rootResolver from '../ORM/resolver';

// import Company from 'modules/ReactCMS/components/ORM/Company';
// import User from 'modules/ReactCMS/components/ORM/User';

// import defaultQuery from 'modules/ReactCMS/components/ORM/query';


import ReactCmsQuery from 'react-cms/src/app/components/ORM/query';

// import OrmQuery from 'modules/ReactCMS/components/ORM/query';

import OrmQuery from 'modules/Site/components/ORM/query';

const defaultQuery = `
${OrmQuery}

#ReactCmsQuery
${ReactCmsQuery}

`;




// console.log("ORM 2", ORM);
// console.log("RootType", RootType);
// console.log("Mutation", Mutation);
// console.log("rootDirectives", rootDirectives);

var knex;

// 

// var knexdb = require('knex');

let schema;

export default class Response{

  // constructor (req, res, params, knexdb, config, ws_clients, SendWebSocketMessage, SendMODXRequest) {
  constructor (scope, res, params, knexdb, config, ws_clients, SendWebSocketMessage, SendMODXRequest) {
 
    this.scope = scope;

    this.db = knex = knexdb;

    // this.req = req;
    // this.res = res;
    // this.params = params;
    
    this.config = config;
    
    // this.prepareSchema();


    schema = this.getSchema();

    // this.rootResolver = rootResolver;
 
    this.ws_clients = ws_clients;

    this.SendWebSocketMessage = SendWebSocketMessage;

    this.SendMODXRequest = SendMODXRequest;

    this.serverDOMBuilder = ::this.serverDOMBuilder;


    this.state = createStores();

    this.initData = initData.bind(this);
    this.createStoreObject = createStoreObject.bind(this);

    this.localResolver = localResolver.bind(this);
  };

  getConfig = (field) => {

    return this.config[field];

  };

  getPrefix = () => {

    // const db = require('../../../../config/config');

    // let 

    const db_config = this.getConfig("db");

    let {
      connection: {
        prefix,
      },
    } = db_config || {};

    return prefix;
  };


  setState = (state) => {

    Object.assign(this.state, state);

  }


  serverDOMBuilder(html){

    // const blocks = convertFromHTML(content, global.serverDOMBuilder);

    // state = ContentState.createFromBlockArray(blocks);

    doc.documentElement.innerHTML = html;
    
    const root = doc.getElementsByTagName('body')[0];



    return root;
  }


  // prepareSchema(){

  //   this.RatingGroupbyEnumList = {
  //     name : 'RatingGroupbyEnum2',
  //     description : 'Способ группировки рейтингов',
  //     values : {
  //       company: {
  //         value: 'company',
  //         description : 'Сгруппировать по компаниям (общий рейтинг)'
  //       },
  //       rating_type: {
  //         value: 'rating_type',
  //         description : 'Сгруппировать по типам рейтингов (по каким рейтингам сколько голосов всего и по количеству компаний)'
  //       },
  //       company_and_rating_type: {
  //         value: 'company_and_rating_type',
  //         description : 'Сгруппировать по компаниям и типам рейтингов (средний балл на каждую компанию по типу рейтинга)'
  //       },
  //       // rating_type_and_company: {
  //       //   value: 'rating_type_and_company',
  //       //   description : 'Сгруппировать по компаниям и типам рейтингов в них'
  //       // },
  //     }
  //   };


  //   return;
  // }

  // SendMODXRequest = (action, params) => {

  //   const req = this.req;

  //   const method = 'POST';

  //   let url = `/assets/components/modxsite/connectors/connector.php?pub_action=${action}`;

  //   let options = {
  //     // host: host,
  //     // port: 80,
  //     // path: url,
  //     method,
  //     headers: {
  //       // 'Content-Type': 'application/x-www-form-urlencoded',
  //       // 'Content-Length': Buffer.byteLength(postData)
  //     },
  //     // json: {
  //     //   users: users
  //     // }
  //   };

  //   let form;

  //   let {
  //     sort,
  //     ...other
  //   } = params;

  //   params = {...other};

  //   if(sort){

  //     if(Array.isArray(sort)){

  //       sort = sort[0];

  //       if(sort){

  //         params.sort = sort.by;
  //         params.dir = sort.dir || undefined;

  //       }

  //     }

  //   }

  //   if(method == 'POST' && params){
  //     // var postData = querystring.stringify(params);


  //     form = new FormData()

  //     for(var i in params){
        
  //       var value = params[i];

  //       value = (typeof value !== "undefined") && value.toString && value.toString() || undefined;

  //       if(value !== undefined){
  //         form.append(i, value);
  //       }
  //     }

  //     // form.append('limit', 3);
  //     // form.append('with_coors_only', 'true');

  //     options.body = form;

  //     // Object.assign(options.headers, form.getHeaders());

      
  //   }



  //   /*
  //   * Собираем кукисы из оригинального запроса и если передаются куки в параметрах запроса,
  //   * то объединяем их
  //   * */
  //   var cookies = [];

  //   let cookies_obj;

  //   // if(req.upgradeReq && req.upgradeReq.headers && req.upgradeReq.headers.cookie){
  //   //   var cooks = req.upgradeReq.headers.cookie.split(";");

  //   //   cooks.map(function(item){
  //   //     var match = item.match(/ *(.+?)=(.+)/);
  //   //     if(match){
  //   //       cookies_obj[match[1]] = match[2];
  //   //     }
  //   //   });
  //   // }

  //   if(req.headers && req.headers.cookie){
  //     let cooks = req.headers.cookie.split(";");

  //     cookies_obj = {};

  //     cooks.map(function(item){
  //       var match = item.match(/ *(.+?)=(.+)/);
  //       if(match){
  //         cookies_obj[match[1]] = match[2];
  //       }
  //     });
  //   }

  //   if(cookies_obj){

  //     for(var i in cookies_obj){
  //       cookies.push(i + '=' + cookies_obj[i]);
  //     }
  //   }

  //   if(cookies){
  //     options.headers.Cookie = cookies;

  //     debug("options.headers", options.headers);
  //   }

  //   debug("options.headers", options.headers);
  //   debug("options", options);


  //   return fetch(site_url + url, options)
  //     .then(function(res) {
  //       return res.json();
  //     });
  // };

  ObjectResolver = (resolver, object, args) => {
    return new Promise((resolve, reject) => {

      resolver(object, args)
        .then(result => resolve(result && result[0] || null))
        .catch(e => reject(e));

    });
  }

  ObjectsResolver = (resolver, object, args) => {
    return new Promise((resolve, reject) => {

      resolver(object, args)
        .then(result => {

          

          resolve(result.success && result.object || null);
        })
        .catch(e => reject(e));

    });
  }

  // companiesListResolver = (object, args) => {

  //   return new Promise((resolve, reject) => {
  //     // Эта функция будет вызвана автоматически

  //     // В ней можно делать любые асинхронные операции,
  //     // А когда они завершатся — нужно вызвать одно из:
  //     // resolve(результат) при успешном выполнении
  //     // reject(ошибка) при ошибке

  //     // 

  //     let {
  //       id,
  //       limit,
  //       page,
  //       offset: start,
  //       count,
  //       voted_companies,
  //       search,
  //     } = args || {};

  //     limit = limit || 0;

  //     let action = 'companies/getdata';

  //     let params = {
  //       // with_coors_only: false,       // Только с координатами
  //       company_id: id,
  //       limit,
  //       page,
  //       start,
  //       count: count === undefined ? 1 : count,
  //       companies: voted_companies,
  //       search,
  //     };

  //     let request = this.SendMODXRequest(action, params); 


  //     request
  //     .then((data) => {

  //       if(!data.success){

  //         return reject(data.message || "Ошибка выполнения запроса");
  //       }

  //       // delete(data.object);

  //       // 

  //       return resolve(data);
  //     })
  //     .catch((e) => {
  //       return reject(e);
  //     })
  //     ;
  //   });
  // }

  // companiesResolver = (object, args) => {

  //   return new Promise((resolve, reject) => {

  //     this.companiesListResolver(object, args)
  //       .then((result) => {

          

  //         if(!result.success){

  //           return reject(result.message || "Ошибка выполнения запроса");
  //         }

  //         // 

  //         return resolve(result.object && result.object || null);
  //       })
  //       .catch((e) => {
  //         return reject(e);
  //       })
  //     ;
  //   });
  // }

  // companyResolver = (object, args) => {

  //   return new Promise((resolve, reject) => {

  //     this.companiesResolver(object, args)
  //       .then((result) => {

          

  //         // 

  //         return resolve(result && result[0] || null);
  //       })
  //       .catch((e) => {
  //         return reject(e);
  //       })
  //     ;
  //   });
  // }

  commentsListResolver = (object, args) => {

    return new Promise((resolve, reject) => {
      // Эта функция будет вызвана автоматически

      // В ней можно делать любые асинхронные операции,
      // А когда они завершатся — нужно вызвать одно из:
      // resolve(результат) при успешном выполнении
      // reject(ошибка) при ошибке


      let {
        id,
        thread: thread_id,
        limit,
        start,
        count,
        sort: sortParams,
        // order: dir,
      } = args || {};

      const {
        by: sort,
        dir,
      } = sortParams && sortParams[0] || {};

      limit = limit || 0;

      let action = 'comments/getdata';

      let params = {
        id,
        thread_id,
        limit,
        start,
        count,
        // sort,
        // dir,
      };

      if(sort){
        params.sort = sort;

        if(dir){
          params.dir = dir;
        }
      }

      // 

      let request = this.SendMODXRequest(action, params); 


      request
        .then((data) => {

          if(!data.success){

            return reject(data.message || "Ошибка выполнения запроса");
          }

          // delete(data.object);

          // 
          
          // 

          // return resolve(data && data.object || []);
          return resolve(data);
        })
        .catch((e) => {
          return reject(e);
        })
      ;
    });
  }

  RatingTypesResolver = (Company, args) => {

    let {
      id,
      limit,
      start,
      sort,
    } = args || {};

    var q = knex(`${prefix}site_content as rating_types`)
      // .innerJoin(`${prefix}user_attributes as profile`, 'users.id', 'profile.internalKey')
      // .select('profile.*')
      .select('rating_types.*')
      .select('rating_types.pagetitle as name')
      // .limit('3')
      ;

      q.where({
        deleted: 0,
        published: 1,
        hidemenu: 0,
        parent: 1349,
      });

      // if(Company_id){

      //   q.innerJoin(`${prefix}modxsite_companies_places as places_companies`, 'places_companies.place_id', 'places.id');
      //   q.where('places_companies.Company_id', Company_id);

      // }

      if(id){
        q.where('id', id);
      }

      if(limit > 0){
        q.limit(limit);
      }

      // 

      q.then((result) => { 
        return result;
      });

    return q; 
  }

  citiesResolver = (parent, args) => {

    let {
      id,
      limit,
      start,
    } = args || {};

    var q = knex(`${prefix}site_content as cities`)
      // .innerJoin(`${prefix}user_attributes as profile`, 'users.id', 'profile.internalKey')
      // .select('profile.*')
      .select('cities.*') 
      .select('cities.pagetitle as name')
      .select('coords_tv.value as coords')
      // .limit('3')
      ;

      q.leftJoin(`${prefix}site_tmplvar_contentvalues as coords_tv`, function() {
        this.on('coords_tv.contentid', 'cities.id')
          .andOn('coords_tv.tmplvarid', 27)
          .andOn(knex.raw("coords_tv.value != ''"))
      });
 
      q.where({
        template: 26,
        published: 1,
        deleted: 0,
        hidemenu: 0,
      });

      if(id){
        q.where('id', id);
      }

      if(limit > 0){
        q.limit(limit);
      }

      // 

      q.orderByRaw("FIELD(cities.id, 1201, 1199, 1197) DESC");
      q.orderBy('pagetitle', 'ASC');

      q.then((result) => { 
        return result;
      });

    return q; 
  }

  placesResolver = (Company, args) => {

    let {
      id,
      limit,
      start,
      Company_id,
      withGeoOnly,
    } = args || {};

    if(Company){
      let {
        id: cont_id,
      } = Company;

      Company_id = cont_id;
    }

    var q = knex(`${prefix}gmapsdb_places as places`)
      // .innerJoin(`${prefix}user_attributes as profile`, 'users.id', 'profile.internalKey')
      // .select('profile.*')
      .select('places.*') 
      // .limit('3')
      ; 

      if(withGeoOnly){
        q.where('lat', '>', 0);
        q.where('lng', '>', 0);
      }

      if(Company_id){

        q.innerJoin(`${prefix}modxsite_companies_places as places_companies`, 'places_companies.place_id', 'places.id');
        q.where('places_companies.Company_id', Company_id);

      }

      if(id){
        q.where('id', id);
      }

      if(limit > 0){
        q.limit(limit);
      }

      // 

      q.then((result) => { 
        return result;
      });

    return q; 
  }

  servicesResolver = (object, args) => {

    let {
      id,
      place_id,
      Company_id,
      limit,
      start,
    } = args || {};

    var q = knex(`${prefix}modxsite_services as services`)
      // .innerJoin(`${prefix}user_attributes as profile`, 'users.id', 'profile.internalKey')
      // .select('profile.*')
      .select('services.*') 
      // .limit('3')
      ; 

      if(place_id || Company_id){
        
        q.innerJoin(`${prefix}modxsite_place_services as place_services`, 'place_services.service_id', 'services.id');

        if(place_id){
          q.where('place_services.place_id', place_id);
        }

        if(Company_id){
          q.where('place_services.Company_id', Company_id);
        }

      }

      if(limit > 0){
        q.limit(limit);
      }

      if(id > 0){
        q.where('id', id);
      }

      

      q.then((result) => { 
        return result;
      });

    return q; 
  }


  // usersListResolver = (project) => {

  //   var q = knex(`${prefix}users as users`)
  //     .innerJoin(`${prefix}user_attributes as profile`, 'users.id', 'profile.internalKey')
  //     .select('profile.*')
  //     .select('users.*') 
  //     .limit('3')
  //     ; 
  //     // 

  //     q.then((result) => { 
  //       return result;
  //     });

  //   return q; 
  // }


  usersListResolver = (object, args, context, info) => {

    return new Promise((resolve, reject) => {
      // Эта функция будет вызвана автоматически

      // В ней можно делать любые асинхронные операции,
      // А когда они завершатся — нужно вызвать одно из:
      // resolve(результат) при успешном выполнении
      // reject(ошибка) при ошибке

      // 

      let {
        ids,
        limit,
        page,
        offset: start,
        count,
        // voted_companies,
        search,
      } = args || {};

      limit = limit || 0;

      let action = 'users/getdata';

      let params = {
        // with_coors_only: false,       // Только с координатами
        format: "json",
        ids,
        limit,
        page,
        start,
        count: count === undefined ? 1 : count,
        // companies: voted_companies,
        search,
      };

      let request = this.SendMODXRequest(action, params); 


      request
      .then((data) => {



        if(!data.success){

          return reject(data.message || "Ошибка выполнения запроса");
        }

        return resolve(data);
      })
      .catch((e) => {
        return reject(e);
      })
      ;
    });
  }

  resourcesResolver = (author) => {  

    let {
      id: author_id,
    } = author || {};

    var q = knex(`${prefix}site_content as resources`)
      .select('resources.*')
      .limit('3')
      ; 
      
      q.where("deleted", 0);
      q.where("published", 1);
      q.where("hidemenu", 0);
      q.where("context_key", "web");

      if(author_id){
        q.where("createdby", author_id);
      }
      

      // 

      q.then((result) => { 
        return result;
      });

    return q; 
  }
 



  getSchema(){

    return new GraphQLSchema({
      query: RootType,
      mutation: Mutation,
      // directives: rootDirectives,
    });

  }

  createStoreObject = (Class, data) => {
    return new Class(data, this);
  }

  getSchema__(){

    let RatingTypesType;
    let RatingsType;
    let RatingGroupbyEnum;

    let CityType;
    let PlaceType;
    let CompanyType;
    let ServiceType;
    let CommentType;

    const {
      RatingGroupbyEnumList,
    } = this;
    
    RatingGroupbyEnum = new GraphQLEnumType(RatingGroupbyEnumList);

    const imageType = {
      type: new GraphQLObjectType({
        name: 'Images',
        fields: {
          original: {
            type: GraphQLString,
            resolve: (image) => {
              return image;
            },
          },
          thumb: {
            type: GraphQLString,
            resolve: (image) => {
              return `images/resized/thumb/${image}`;
            },
          },
          marker_thumb: {
            type: GraphQLString,
            resolve: (image) => {
              return `images/resized/marker_thumb/${image}`;
            },
          },
          small: {
            type: GraphQLString,
            resolve: (image) => {
              return `images/resized/small/${image}`;
            },
          },
          middle: {
            type: GraphQLString,
            resolve: (image) => {
              return `images/resized/middle/${image}`;
            },
          },
          big: {
            type: GraphQLString,
            resolve: (image) => {
              return `images/resized/big/${image}`;
            },
          },
        },
      }),
      resolve: (object) => {

        const {
          image,
        } = object;

        return image && image.replace(/^\//g, '') || null;

        // return image ? `uploads/${image}` : null;
      },
    };

    // const SortType = new GraphQLEnumType({
    //   name: "__SortType",
    //   description: 'Сортировка',
    //   values: {
    //     asc: {
    //       value: 'asc',
    //       description: 'В прямом порядке',
    //     },
    //     desc: {
    //       value: 'desc',
    //       description: 'В обратном порядке',
    //     },
    //   },
    // });

    RatingTypesType = new GraphQLObjectType({
      name: 'RatingTypesType',
      description: 'Тип рейтинга (Парилка, Интерьер, Кухня и т.п.)',
      fields: () => {

        return {
          id: {
            type: GraphQLInt
          },
          name: {
            type: GraphQLString
          },
          ratings: {
            type: new GraphQLList(RatingsType),
            resolve: (rating_type) => {

              const {
                id: type,
              } = rating_type;

              let args = {
                type,
                groupBy: 'rating_type',
                limit: 0,
              };

              

              return this.RatingsResolver(null, args);
            },
          },
        };
      },
    });

    CommentType = new GraphQLObjectType({
      name: 'CommentType',
      description: 'Комментарии',
      fields: () => {

        return {
          id: {
            type: GraphQLInt,
          },
          thread_id: {
            type: GraphQLString,
          },
          text: {
            type: GraphQLString,
          },
          author_username: {
            type: GraphQLString,
          },
          author_fullname: {
            type: GraphQLString,
          },
          author_avatar: {
            type: GraphQLString,
          },
          parent: {
            type: GraphQLInt,
          },
          published: {
            type: GraphQLInt,
          },
          deleted: {
            type: GraphQLInt,
          },
          createdon: {
            type: GraphQLString,
            // description: 'Время создания комментария в миллисекундах',
            resolve: (comment, args) => {
              return comment.createdon ? moment(comment.createdon).format('MMMM DD, YYYY | HH:mm:ss') : null;
            },
          },
          // ratings: {
          //   type: new GraphQLList(RatingsType),
          //   resolve: (rating_type) => {

          //     const {
          //       id: type,
          //     } = rating_type;

          //     let args = {
          //       type,
          //       groupBy: 'rating_type',
          //       limit: 0,
          //     };

          //     

          //     return this.RatingsResolver(null, args);
          //   },
          // },
        };
      },
    });


    RatingsType = new GraphQLObjectType({
      name: 'RatingsType',
      description: 'Рейтинги бань (с возможностью группировки по типам рейтингов и компаний)',
      fields: () => {

        return {
          rating: {
            type: GraphQLFloat
          },
          max_vote: {
            type: GraphQLFloat
          },
          min_vote: {
            type: GraphQLFloat
          },
          type: {
            type: GraphQLInt
          },
          company_id: {
            type: GraphQLInt
          },
          quantity: {
            type: GraphQLInt
          },
          quantity_voters: {
            type: GraphQLInt,
            description: 'Количество проголосовавши людей',
          },
          voted_companies: {
            type: GraphQLString
          },
          voter: {
            type: GraphQLInt,
            description: 'Проголосовавший пользователь',
          },
          voters: {
            type: GraphQLString,
            description: 'Проголосовавшие люди',
          },
          companies: {
            type: new GraphQLList(CompanyType),
            resolve: (rating) => {

              const {
                company_id,
                voted_companies,
              } = rating;

              if(!voted_companies && !company_id){
                return [];
              }

              let args = {};

              if(voted_companies){
                Object.assign(args, {
                  voted_companies,
                });
              }
              else{
                Object.assign(args, {
                  id: company_id,
                });
              }

              

              return this.companiesResolver(null, args);
            },
          },
          rating_type: {
            type: new GraphQLList(RatingTypesType),
            resolve: (rating) => {

              const {
                type,
              } = rating;

              let args = {
                id: type,
                limit: 0,
              };

              return this.RatingTypesResolver(null, args);
            },
          },
        };
      },
    }); 

    CityType = new GraphQLObjectType({
      name: 'CityType',
      fields: () => {

        return {
          id: {
            type: GraphQLInt
          },
          name: {
            type: GraphQLString
          },
          defaultZoom: {
            type: GraphQLInt,
            resolve: (object) => {
              let zoom;
              
              switch(object.id){

                // Москва
                case 1197:
                // Спб
                case 1201:
                  zoom = 11;
                  break;

                // Кронштадт
                case 1394:
                  zoom = 12;
                  break;


                // Москва
                case 1199:
                  zoom = 9;
                  break;

                default: zoom = 12;
              }

              return zoom;
            },
          },
          coords: {
            type: new GraphQLObjectType({
              // new GraphQLObjectType({
                name: 'cityCoordsType',
                fields: {
                  lat: {
                    type: GraphQLFloat,
                  },
                  lng: {
                    type: GraphQLFloat,
                  },
                },
              // })
            }),
            resolve: (object) => {

              let {
                coords,
              } = object;

              if(coords){
                coords = coords.split(",").map(n => parseFloat(n));
              }

              return coords && {
                lat: coords[1],
                lng: coords[0],
              } || null;

            },
          },
        };
      },
    }); 

    PlaceType = new GraphQLObjectType({
      name: 'PlaceType',
      fields: () => {

        return {
          id: {
            type: GraphQLInt
          },
          name: {
            type: GraphQLString
          },
          companies: {
            type: new GraphQLList(CompanyType),
            resolve: (place) => {

              const {
                id: place_id,
              } = place;

              return this.companiesResolver(null, {
                place_id,
              });
            },
          },
          services: {
            type: new GraphQLList(ServiceType),
            args: {
              // place_id: {
              //   type: GraphQLID
              //   // type: new GraphQLNonNull(GraphQLID)
              // },
              // limit: {
              //   type: new GraphQLNonNull(GraphQLInt),
              // },
              // Company_id: {
              //   type: GraphQLInt
              // },
              // withGeoOnly: {
              //   type: GraphQLBoolean
              // },
            },
            resolve: (place, args) => {

              const {
                id: place_id,
              } = place;

              return this.servicesResolver(null, {
                place_id,
                limit: 0,
              });
            },
          },
        };
      },
    }); 

    CompanyType = new GraphQLObjectType({
      name: 'CompanyType',
      fields: () => {

        return {
          id: {
            type: GraphQLInt
          },
          name: {
            type: GraphQLString
          },
          longtitle: {
            type: GraphQLString
          },
          description: {
            type: GraphQLString
          },
          content: {
            type: GraphQLString
          },
          alias: {
            type: GraphQLString
          },
          uri: {
            type: GraphQLString
          },
          image: imageType,
          city_id: {
            type: GraphQLInt
          },
          city: {
            type: GraphQLString
          },
          city_uri: {
            type: GraphQLString
          },
          tvs: {
            type: new GraphQLObjectType({
              name: 'TSvType',
              fields: {
                address: {
                  type: GraphQLString,
                  description: 'Адрес',
                },
                site: {
                  type: GraphQLString,
                  description: 'Веб-сайт',
                },
                facility_type: {
                  type: GraphQLString,
                  description: 'Тип заведения',
                },
                phones: {
                  type: GraphQLString,
                  description: 'Телефон',
                },
                work_time: {
                  type: GraphQLString,
                  description: 'Рабочее время',
                },
                prices: {
                  type: GraphQLString,
                  description: 'Цены',
                },
                metro: {
                  type: GraphQLString,
                  description: 'Метро',
                },
              },
            }),
            resolve: (object) => {
              let tvs = {};

              if(object.tvs){

                for(var name in object.tvs){

                  var tv = object.tvs[name];

                  if(tv){

                    var {
                      tv_id: id,
                      caption,
                      value,
                    } = tv;

                    tvs[name] = value;

                  }
                }
                
              }

              return tvs;
            },
          },
          gallery: {
            type: new GraphQLList(
              new GraphQLObjectType({
                name: 'galleryType',
                fields: {
                  image: imageType,
                },
              })
            ),
            resolve: (object) => {
              return object.gallery || [];
            },
          },
          coords: {
            type: new GraphQLObjectType({
              // new GraphQLObjectType({
                name: 'coordsType',
                fields: {
                  lat: {
                    type: GraphQLFloat,
                  },
                  lng: {
                    type: GraphQLFloat,
                  },
                },
              // })
            }),
            resolve: (object) => {

              return object.coords && {
                lat: object.coords[1],
                lng: object.coords[0],
              } || null;
            },
          },
          ratings: {
            description: 'Рейтинги компании',
            type: new GraphQLList(RatingsType),
            args: {
              type: {
                type: GraphQLID
                // type: new GraphQLNonNull(GraphQLID)
              },
              limit: {
                type : GraphQLInt,
              },
              groupBy: {
                type : RatingGroupbyEnum,
              },
            },
            resolve: (company, args) => {

              // 

              const {
                id: company_id,
              } = company;

              Object.assign(args, {
                company: company_id,
              });

              return this.RatingsResolver(company, args);
            },
          },
          ratingAvg: {
            description: 'Суммарный рейтинг',
            type: RatingsType,
            args: {
              groupBy: {
                type : RatingGroupbyEnum,
              },
            },
            resolve: (company, args) => {

              // 

              const {
                id: company_id,
              } = company;

              Object.assign(args, {
                company: company_id,
                groupBy: 'company',
                limit: 1,
              });

              return this.ObjectResolver(this.RatingsResolver, company, args);
            },
          },
          ratingsByType: {
            description: 'Рейтинг по типам',
            type: new GraphQLList(RatingsType),
            args: {
              groupBy: {
                type : RatingGroupbyEnum,
              },
            },
            resolve: (company, args) => {

              // 

              const {
                id: company_id,
              } = company;

              Object.assign(args, {
                company: company_id,
                groupBy: 'rating_type',
              });

              return this.RatingsResolver(company, args);
            },
          },
          votes: {
            description: 'Все голоса за компанию',
            type: new GraphQLList(RatingsType),
            args: {
              groupBy: {
                type : RatingGroupbyEnum,
              },
            },
            resolve: (company, args) => {

              // 

              const {
                id: company_id,
              } = company;

              Object.assign(args, {
                company: company_id,
              });

              return this.RatingsResolver(company, args);
            },
          },
          comments: {
            type: new GraphQLList(CommentType),
            description: CommentType.description,
            // args: {
            //   order: {
            //     type: SortType,
            //     description: SortType.description,
            //   },
            // },
            args: listArgs,
            resolve: (company, args) => {


              const {
                id: company_id,
              } = company;

              args = Object.assign({
                order: 'asc',
              }, args, {
                thread: company_id,
                // thread: parseInt(company_id),
              });

              // 

              // return this.ObjectsResolver(this.commentsListResolver, company, args);

              return new Promise((resolve, reject) => {

                this.commentsListResolver(company, args)
                  .then(result => {
                    // resolve(result && result);
                    if(result.success){
                      return resolve(result.object || []);
                    }
                    else{
                      return reject(result.message || 'Ошибка выполнения запроса');
                    }
                  })
                  .catch(e => reject(e));
              });

              // return this.commentsListResolver(company, args);
            },
          },
        }
      }
    });

    ServiceType = new GraphQLObjectType({
      name: 'ServiceType',
      fields: () => {
        return {
          id: {
            type: GraphQLInt,
          },
          name: {
            type: GraphQLString
          },
          parent: {
            type: GraphQLInt
          },
          places: {
            type: new GraphQLList(PlaceType),
            resolve: (service, args) => {

              // 

              const {
                id: service_id,
              } = service;

              Object.assign(args, {
                service_id,
              });

              // 

              return this.placesResolver(service, args);
            },
          },
          companies: {
            type: new GraphQLList(CompanyType),
            // args: {
            //   id: {
            //     type: GraphQLID
            //     // type: new GraphQLNonNull(GraphQLID)
            //   },
            //   limit: {
            //     type: GraphQLInt
            //   },
            // },
            resolve: (service, args) => {

              // 

              const {
                id: service_id,
              } = service;

              Object.assign(args, {
                service_id,
              });

              // 


              return this.companiesResolver({}, args);
            },
          },
        }
      }
    });

    const DocumentType = new GraphQLObjectType({
      name: 'DocumentType',
      fields: {
        id: {
          type: GraphQLInt
        },
        pagetitle: {
          type: GraphQLString
        },
        longtitle: {
          type: GraphQLString
        },
        uri: {
          type: GraphQLString
        },
      }
    });

    const UserType = new GraphQLObjectType({
      name: 'UserType',
      description: 'Пользователь',
      fields: {
        id: {
          type: GraphQLInt
        },
        username: {
          type: GraphQLString
        },
        fullname: {
          type: GraphQLString
        },
        email: {
          type: GraphQLString
        },
        // photo: {
        //   type: GraphQLString,
        //   resolve: user => {
        //     return user.photo && user.photo.replace(/^\//g,'') || null;
        //   },
        // },
        image: imageType,
        active: {
          type: GraphQLBoolean,
          resolve: user => {
            return user.active === null ? null : parseInt(user.active);
          },
        },
        sudo: {
          type: GraphQLBoolean,
          resolve: user => {
            return user.sudo === null ? null : parseInt(user.sudo);
          },
        },
        blocked: {
          type: GraphQLBoolean,
          resolve: user => {
            return user.blocked === null ? null : parseInt(user.blocked);
          },
        },
      }
    });

    const RootType___ = new GraphQLObjectType({
      name: 'RootType',
      fields: {
        // comments: {
        //   type: new GraphQLList(CommentType),
        //   description: CommentType.description,
        //   args: {
        //   },
        //   resolve: (object, args) => {

        //     // 

        //     return this.commentsListResolver(object, args);
        //   },
        // },

        comments: {
          type: new ObjectsListType({
            name: "CommentsList",
            type: CommentType,
            description: 'Список комментариев',
          }),
          args: Object.assign({
            thread: {
              type: GraphQLID,
              description: 'ID диалоговой ветки',
            },
          }, listArgs),
          resolve: (object, args, context) => {
            // 

            return this.commentsListResolver(object, args, context);
          },
        },


        rating_types: {
          type: new GraphQLList(RatingTypesType),
          description: RatingTypesType.description,
          args: {
            id: {
              type: GraphQLID
              // type: new GraphQLNonNull(GraphQLID)
            },
            limit: {
              type: new GraphQLNonNull(GraphQLInt)
              // type: GraphQLInt
            },
          },
          resolve: (object, args) => {

            // 

            return this.RatingTypesResolver(object, args);
          },
        },
        ratings: {
          type: new GraphQLList(RatingsType),
          description: RatingTypesType.description,
          args: {
            type: {
              type: GraphQLID
              // type: new GraphQLNonNull(GraphQLID)
            },
            limit: {
              type: new GraphQLNonNull(GraphQLInt)
              // type: GraphQLInt
            },
            company: {
              type: GraphQLInt,
              description: 'ID компании, по которой надо получить рейтинги',
              // type: GraphQLInt
            },
            groupBy: {
              type : RatingGroupbyEnum,
            },
          },
          resolve: (object, args) => {

            // 

            return this.RatingsResolver(object, args);
          },
        },
        companies: {
          type: new ObjectsListType({
            name: "CompaniesList",
            type: CompanyType,
            description: 'Список компаний',
          }),
          args: listArgs,
          resolve: (object, args) => {
            // 

            return this.companiesListResolver(object, args);
          },
        },
        company: {
          type: CompanyType,
          name: "CompaniesList",
          description: CompanyType.description,
          args: {
            id: {
              type: new GraphQLNonNull(GraphQLInt),
            },
          },
          resolve: (object, args) => {
            // 

            return this.companyResolver(object, args);
          },
        },
        cities: {
          type: new GraphQLList(CityType),
          args: {
            id: {
              type: GraphQLID
              // type: new GraphQLNonNull(GraphQLID)
            },
            limit: {
              // type: GraphQLInt
              type: new GraphQLNonNull(GraphQLInt)
            },
            // Company_id: {
            //   type: GraphQLInt
            // },
            // service_id: {
            //   type: GraphQLInt
            // },
            // withGeoOnly: {
            //   type: GraphQLBoolean
            // },
          },
          resolve: (object, args) => {

            // 

            return this.citiesResolver(object, args);
          },
        },
        // places: {
        //   type: new GraphQLList(PlaceType),
        //   args: {
        //     id: {
        //       type: GraphQLID
        //       // type: new GraphQLNonNull(GraphQLID)
        //     },
        //     limit: {
        //       // type: GraphQLInt
        //       type: new GraphQLNonNull(GraphQLInt)
        //     },
        //     Company_id: {
        //       type: GraphQLInt
        //     },
        //     service_id: {
        //       type: GraphQLInt
        //     },
        //     withGeoOnly: {
        //       type: GraphQLBoolean
        //     },
        //   },
        //   resolve: (object, args) => {

        //     // 

        //     return this.placesResolver(object, args);
        //   },
        // },
        // services: {
        //   type: new GraphQLList(ServiceType),
        //   args: {
        //     id: {
        //       type: GraphQLID
        //       // type: new GraphQLNonNull(GraphQLID)
        //     },
        //     limit: {
        //       type: new GraphQLNonNull(GraphQLInt),
        //     },
        //     Company_id: {
        //       type: GraphQLInt
        //     },
        //     place_id: {
        //       type: GraphQLInt
        //     },
        //     // withGeoOnly: {
        //     //   type: GraphQLBoolean
        //     // },
        //   },
        //   resolve: (object, args) => {
        //     return this.servicesResolver(object, args);
        //   },
        // },
        users: {
          type: new ObjectsListType({
            name: "UsersList",
            type: UserType,
            description: 'Список пользователей',
          }),
          args: listArgs,
          resolve: (object, args, context) => {
            // 

            return this.usersListResolver(object, args, context);
          },
        },
        resources: {
          type: new GraphQLList(DocumentType),
          resolve: () => {
            return this.resourcesResolver();
          },
        },
      }
    });



    var schema = new GraphQLSchema({
      query: RootType
    });

    return schema;
  }

  process = async (req, res, params) => {

    params = Object.assign({}, req.query || {}, params || {});

    let {
      pub_action,
      query,
      variables,
      operationName,
    } = params;

    let result;

    // await this.localQuery({
    await this.remoteQuery({
      query,
      operationName,
      variables,
      req,
    })
    .then((response) => {

      let {
        errors,
      } = response;

      if(errors && errors.length){
        let {
          message,
          ...other
        } = errors[0];

        result = this.failure(message, {...other}, res);
      }
      // else
      result = this.success("", response && response.data || null, res);
    })
    .catch(e => {


      // console.error("Response process e", e);

      result = this.failure(e, null, res);
    });

    return result;
  }

  // process = async (req, res, params) => {  

  //   // let {
  //   //   pub_action,
  //   //   ...params 
  //   // } = this.getRequestParams();

  //   // let params = this.params || {};
  //   // let query = req.query || {};

  //   params = Object.assign({}, req.query || {}, params || {});

  //   // let {
  //   //   ...params 
  //   // } = this.getRequestParams();

  //   let {
  //     pub_action,
  //     query,
  //     variables,
  //     operationName,
  //   } = params;

  //   let result;

  //   // variables = Object.assign(variables || {
  //   //   req,
  //   // });

  //   await this.remoteQuery({
  //     query,
  //     operationName,
  //     variables,
  //     req,
  //   })
  //   .then((response) => {

  //     let {
  //       errors,
  //     } = response;

  //     if(errors && errors.length){
  //       let {
  //         message,
  //         ...other
  //       } = errors[0];

  //       result = this.failure(message, {...other}, res);
  //     }
  //     // else
  //     result = this.success("", response && response.data || null, res);
  //   })
  //   .catch(e => {


  //     console.error("Response process e", e);

  //     result = this.failure(e, null, res);
  //   });

  //   return result;
  // }


  localQuery = (graphQLParams) => {

    const {
      query,
      operationName,
      variables,
      req,
    } = graphQLParams;

    return new Promise((resolve, reject) => {

      const {
        request,
      } = variables || {};

      const {
        location,
        params,
      } = request || {};

      // const {
      //   req,
      //   ...debugParams
      // } = graphQLParams;

      // console.log("response localQuery", "resolver");
      // debug("localQuery", debugParams);
      // debug("localQuery location", location, params);

      graphql({
        schema,
        operationName,
        source: query || defaultQuery,
        // rootValue: undefined,
        variableValues: variables || undefined,
        // contextValue: this.getChildContext(),
        contextValue: Object.assign({}, this, {
          SendMODXRequest: async (action, params) => {



            return this.SendMODXRequest(action, params, req);

          },
          rootResolver: this.localResolver,
          remoteResolver: rootResolver,
          req,
        }),
        fieldResolver: localResolver,
        // directives: rootDirectives,
      })
        .then((result) => {

          let {
            errors,
          } = result;

          if(errors && errors.length){
            let {
              message,
              ...other
            } = errors[0];

            let responseMessage = message;

            let responseObject = other;

            if(message && typeof message === "string"){

              try{

                let response = JSON.parse(message);

                reject(response);

              }
              catch(e){

              }

            }

            return reject(responseMessage, {...responseObject});

            // return reject({
            //   success: false,
            //   message: "SDfdsf",
            //   object: {
            //     dsfdsf: "SDfdsf",
            //   },
            // });
          }

          // console.error("remoteQuery result error", result);

          // console.error("remoteQuery result JSON error", JSON.stringify(result));

          // debug("localQuery result", debugParams);
          // debug("localQuery location result", location, params);

          return resolve(result);
        })
        .catch(e => {
          console.error("remoteQuery error", e);
          console.error("remoteQuery JSON error", JSON.stringify(e));
          reject(e);
        });
    });

  }

  remoteQuery = (graphQLParams) => {

    const {
      query,
      operationName,
      variables,
      req,
    } = graphQLParams;


    // const {
    //   req: nullReq,
    //   ...debugParams
    // } = graphQLParams;

    // debug("localQuery", debugParams);

    return new Promise((resolve, reject) => {



      graphql({
        schema,
        operationName,
        source: query || defaultQuery,
        // rootValue: undefined,
        variableValues: variables || undefined,
        // contextValue: this.getChildContext(),
        contextValue: Object.assign({}, this, {
          SendMODXRequest: async (action, params) => {



            return this.SendMODXRequest(action, params, req);

          },
        }),
        fieldResolver: rootResolver,
        // directives: rootDirectives,
      })
        .then((result) => {

          let {
            errors,
          } = result;

          if(errors && errors.length){
            let {
              message,
              ...other
            } = errors[0];

            let responseMessage = message;

            let responseObject = other;

            if(message && typeof message === "string"){

              try{

                let response = JSON.parse(message);

                reject(response);

              }
              catch(e){

              }

            }

            return reject(responseMessage, {...responseObject});

            // return reject({
            //   success: false,
            //   message: "SDfdsf",
            //   object: {
            //     dsfdsf: "SDfdsf",
            //   },
            // });
          }

          // console.error("remoteQuery result error", result);

          // console.error("remoteQuery result JSON error", JSON.stringify(result));

          // debug("remoteQuery result", debugParams);

          return resolve(result);
        })
        .catch(e => {
          console.error("remoteQuery error", e);
          console.error("remoteQuery JSON error", JSON.stringify(e));
          reject(e);
        });
    });

  }


  success(message, object, res){

    return this.outputResponse(res, true, message, object)
  }

  failure(message, object, res){
          
    console.error("Response failure", message, object);
    console.error("Response failure JSON", JSON.stringify(message));

    return this.outputResponse(res, false, message, object)
  }

  outputResponse(res, success, message, object){

    // let output = object || {};

    let output = {
      success,
      message,
      // object,
      data: object,
    };

    if(typeof message === "object"){
      output = message;
    }

    // this.res.writeHead(200, {'Content-Type': 'application/json'});
    // this.res.end(JSON.stringify(output));

    res.writeHead(200, {'Content-Type': 'application/json'});
    res.end(JSON.stringify(output));
  } 

}