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

export {
  sortBy,
};


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
    returnType,
    operation,
  } = info;

  // Если это не корневой вызов, сбрасываем операцию, чтобы сквозной вызов не выполнялся
  if(source){
    operation = undefined;
  }


  // if(operation && operation.name){

  //   switch(operation.name.value){

  //     case "addCompany":

  //       if(returnType === CompanyType){

  //         return addCompany(source, args, context, info); 
          
  //       }

  //       break;

  //     case "addCompanyGalleryImage":

  //       if(returnType === CompanyType){

  //         return companyAddGalleryImage(source, args, context, info); 
          
  //       }

  //       break;

  //     case "addTopic":

  //       if(returnType === ResourceType){

  //         return addTopic(source, args, context, info); 
          
  //       }

  //       break;

  //     case "addComment":

  //       if(returnType === CommentType){

  //         return addComment(source, args, context, info); 
          
  //       }

  //       break;

  //     case "clearCache":



  //       if(typeof window !== "undefined"){
  //         throw("Операция не разрешена в окне");
  //       }

  //       const {
  //         scope,
  //       } = context;

  //       return new Promise(async (resolve, reject) => {

  //         try{

  //           result = await scope.clearCache();



  //           resolve(result);

  //         }
  //         catch(e){
  //           reject(e);
  //         }

  //       });


  //       break;

  //     // Сохранение поискового запроса
  //     case "updateCompany":

  //       const {
  //         remoteResolver,
  //       } = context;

  //       if(!remoteResolver){
  //         throw("remoteResolver undefined");
  //       }


  //       return new Promise(async (resolve, reject) => {

  //         try{

  //           const result = await remoteResolver(null, args, context, info);



  //           // if(result && result.success){

  //           //   resolve(result);

  //           // }
  //           // else{
  //           //   reject(result);
  //           // }

  //           resolve(result);

  //         }
  //         catch(e){
  //           reject(e);
  //         }

  //       });

  //       break;

  //   }
  // }


  if(source){

    result = source[fieldName];
  }

  if(result === undefined){

    // Резолвим по типу объекта

    // const {
    //   name: returnTypeName,
    // } = returnType;


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