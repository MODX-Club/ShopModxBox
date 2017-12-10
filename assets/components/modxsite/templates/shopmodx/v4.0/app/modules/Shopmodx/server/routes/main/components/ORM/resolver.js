import {
  GraphQLInputObjectType,
  GraphQLObjectType,
  GraphQLList,
  GraphQLNonNull,
  GraphQLEnumType,
  GraphQLInt,
  GraphQLString,
  GraphQLBoolean,
} from 'graphql';

import {
  listField,
  ObjectsListType,
} from 'modules/Shopmodx/components/ORM/fields';


import SiteContentType from 'react-cms/src/app/components/ORM/SiteContent';

import {
  getList as getSiteContentList,
} from 'react-cms/src/server/components/ORM/SiteContent';


import MODXResourceType from 'modules/Shopmodx/components/ORM/MODXResource';

import {
  getList as getMODXResourcesList,
} from './MODXResource';


import UserType from 'modules/Site/components/ORM/User';

import {
  getList as getUsersList,
} from './User';



import OrderType from 'modules/Site/components/ORM/Order';

import {
  getList as getOrdersList,
} from './Order';



import OrderProductType from 'modules/Site/components/ORM/OrderProduct';

import {
  getList as getOrdersProductsList,
} from './OrderProduct';



const rootResolver = (source, args, context, info) => {



  let result;


  let {
    fieldName,
    operation,
    returnType,
  } = info;

  // const {
  //   ofType,
  // } = returnType;


  // console.log("operation", operation);
  // console.log("source", source);

  // Если это не корневой вызов, сбрасываем операцию, чтобы сквозной вызов не выполнялся
  if(source){
    operation = undefined;
  }


  if(operation && operation.name){

    switch(operation.name.value){


      // Сброс и обновление локального кеша приложения
      case "clearCache":

        if(typeof window !== "undefined"){
          throw("Операция не разрешена в окне");
        }

        const {
          scope,
        } = context;

        return new Promise(async (resolve, reject) => {

          try{

            result = await scope.clearCache();

            resolve(result);

          }
          catch(e){
            reject(e);
          }

        });


        break;

    }
  }


  if(source){

    if(typeof source.fieldResolver === 'function'){
      

      
      result = source.fieldResolver(source, args, context, info);
    }

    else result = source[fieldName];

  }

  if(result === undefined){

    // Резолвим по типу объекта

    const {
      returnType,
    } = info;

    const {
      name: returnTypeName,
    } = returnType;



    if(returnType instanceof ObjectsListType){
      
      const {
        _fields: {
          object: objectField,
        },
      } = returnType;

      if(objectField && objectField.type){

        const {
          type: objectType,
        } = objectField;

        const {
          ofType,
        } = objectType || {};

        return getObjectsList(ofType, source, args, context, info);
      }


    }

    else if(returnType instanceof GraphQLList){

      const {
        ofType,
      } = returnType;

      return getObjects(ofType, source, args, context, info);

    }

    else if(returnType instanceof GraphQLObjectType){


      return getObject(returnType, source, args, context, info);
    }


  }

  return result;

}


const getObjectsList = (ofType, source, args, context, info) => {

  return new Promise( async (resolve, reject) => {

    let result;

    let resolver;

    if(ofType === SiteContentType){

      resolver = getSiteContentList;
        
    }

    else if(ofType === MODXResourceType){

      resolver = getMODXResourcesList;
        
    }

    else if(ofType === UserType){

      resolver = getUsersList;
        
    }

    else if(ofType === OrderType){

      resolver = getOrdersList;
        
    }

    else if(ofType === OrderProductType){

      resolver = getOrdersProductsList;
        
    }

    if(resolver){
      result = await resolver(source, args, context, info)
      .then(r => r)
      .catch(e => {
        reject(e);
      });


      if(result){

        const {
          fieldNodes: {
            0: {
              selectionSet,
            }
          },
        } = info;

        const totalSelection = selectionSet && selectionSet.selections && selectionSet.selections.find(n => n && n.name && n.name.value === "total");


        /*
          Если запрошен список с постраничностью, то добавляем информацию о количестве и странице
        */
        if(totalSelection){

          // Object.assign(result);
          // console.log("result", result);

          const {
            page,
            limit,
          } = args;

          Object.assign(result, {
            page,
            limit,
            // count: result.object && result.object.length,
          });

        }

      }

    }


    return resolve(result);

  });

}


const getObjects = (ofType, source, args, context, info) => {

  

  let result = getObjectsList(ofType, source, args, context, info);

  if(result && result instanceof Promise){

    return new Promise((resolve, reject) => {

      result
      .then(r => resolve(r && r.object || null))
      .catch(r => reject(r))

    });

  }

    
  result = result && result.object;

  return result;

}

const getObject = (ofType, source, args, context, info) => {

  // let state;

  // const {
  //   id,
  //   parent,
  // } = args;



  const state = getObjects(ofType, source, args, context, info);


  if(state && state instanceof Promise){

    return new Promise((resolve, reject) => {

      state
      .then(r => resolve(r && r[0] || null))
      .catch(r => reject(r))

    });

  }

  return state && state[0];
}
 

export default rootResolver;


