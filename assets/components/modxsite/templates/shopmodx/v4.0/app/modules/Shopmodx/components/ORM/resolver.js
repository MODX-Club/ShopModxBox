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


import {List} from 'immutable';

import {
	listField,
	ObjectsListType,
} from './fields';


import SiteContentType, {
  getList as getSiteContentTypeList,
} from 'react-cms/src/app/components/ORM/SiteContent';


import {
  getObjectsList as ReactCmsGetObjectsList,
  getObjects as ReactCmsGetObjects,
  getObject as ReactCmsGetObject,
  objectResolver as ReactCmsObjectResolver,
  sortBy,
} from 'react-cms/src/app/components/ORM/resolver';


import {
  storageDirective,
} from 'react-cms/src/app/components/ORM/directives';

export {
  sortBy,
};


import {
  getDirectiveValue,
} from 'react-cms/src/app/components/ORM/helpers.js';


import MODXResourceType, {
  getList as getMODXResourcesList,
} from './MODXResource';


const getObjectsList = function(ofType, source, args, context, info){
  return ReactCmsGetObjectsList(ofType, source, args, context, info, getResolverByType);
}

const getObjects = function(ofType, source, args, context, info){
  return ReactCmsGetObjects(ofType, source, args, context, info, getResolverByType);
}

const getObject = function(ofType, source, args, context, info){
  return ReactCmsGetObject(ofType, source, args, context, info, getResolverByType);
}


const objectResolver = function(ofType, source, args, context, info){
  return ReactCmsObjectResolver(ofType, source, args, context, info, getResolverByType);
}


const rootResolver = function(source, args, context, info){

  let result;


  let {
    fieldName,
    fieldNodes,
    returnType,
    operation,
  } = info;

  // Если это не корневой вызов, сбрасываем операцию, чтобы сквозной вызов не выполнялся
  if(source){
    operation = undefined;
  }


  if(source){

    result = source[fieldName];
  }

  if(result === undefined){

    // Резолвим по типу объекта

    // const {
    //   name: returnTypeName,
    // } = returnType;

    const {
      _store: argsStore,
    } = args;

    const {
      remoteResolver,
    } = context;


    const directives = fieldNodes[0].directives;

    const storage = directives.filter(d => d.name.value === storageDirective.name)[0];


    let store = getDirectiveValue(source, args, context, info, storage, "store");

    if(store === "remote" || argsStore === "remote"){

      // console.log("Front ORM remote", info);

      return remoteResolver(source, args, context, info);

    }



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

        if(getResolverByType(ofType)){

          return getObjectsList(ofType, source, args, context, info);

        }

      }

    }

    else if(returnType instanceof GraphQLList){

      const {
        ofType,
      } = returnType;

      if(getResolverByType(ofType)){

        return getObjects(ofType, source, args, context, info);

      }

    }

    else if(returnType instanceof GraphQLObjectType){

      if(getResolverByType(returnType)){

        return objectResolver(returnType, source, args, context, info);

      }        

    }

  }


  return result;

}






const getResolverByType = function(ofType){

  // console.log("getResolverByType", ofType);

  let resolver;


  if(ofType === SiteContentType){

    resolver = getSiteContentTypeList;
      
  }

  if(ofType === MODXResourceType){

    resolver = getMODXResourcesList;
      
  }

  return resolver;

}



export default rootResolver;