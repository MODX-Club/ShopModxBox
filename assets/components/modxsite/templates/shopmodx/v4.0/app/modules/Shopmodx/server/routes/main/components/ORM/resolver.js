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


const rootResolver = (source, args, context, info) => {



    let result;


    const {
      fieldName,
      operation,
      returnType,
    } = info;

    // const {
    //   ofType,
    // } = returnType;

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



const getObjectsList = (ofType, source, args, context, info) => {

  return new Promise( async (resolve, reject) => {

    let object;

    let resolver;

    if(ofType === SiteContentType){

      resolver = getSiteContentList;
        
    }

    else if(ofType === MODXResourceType){

      resolver = getMODXResourcesList;
        
    }

    if(resolver){
      object = await resolver(source, args, context, info)
      .then(r => r)
      .catch(e => {
        reject(e);
      });
    }


    return resolve(object);

  });

}

