// PlaceServiceType 

import {
  buildSchema,
  introspectionQuery,
  graphql,
  GraphQLSchema,
  GraphQLObjectType,
  GraphQLInputObjectType,
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
  GraphQLIncludeDirective, 
  GraphQLSkipDirective ,
} from 'graphql/type/directives';

import {
  CommentEditorStateType,
} from './EditorState';


import SiteContentType, {
  SiteContentArgs,
} from 'react-cms/src/app/components/ORM/SiteContent';


import {
  storageDirective,
  ReactCmsStorageStoreType,
} from 'react-cms/src/app/components/ORM/directives';


import MODXResourceType, {
  MODXResourceArgs,
} from './MODXResource';



export const rootDirectives = [
  storageDirective,
  GraphQLIncludeDirective,
  GraphQLSkipDirective,
];


import {
  listField,
  listArgs,
  // ObjectsListType,
} from './fields';


const ObjectArgs = {
  id: {
    type: GraphQLInt,
    description: "Идентификатор",
  },
};
 


export default new GraphQLObjectType({
  name: 'RootType',
  fields: () => ({
    
    storages: {
      type: ReactCmsStorageStoreType,
      description: ReactCmsStorageStoreType.description,
    },

    siteContent: {
      type: SiteContentType,
      description: SiteContentType.description,
      args: SiteContentArgs,
    },

    editorState: {
      type: CommentEditorStateType,
      description: "Стейт редактора",
      resolve: source => (null),
    },


    modxResourcesList: new listField({
      type: MODXResourceType,
      name: "modxResourcesList",
      description: "Список MODX-документов с постраничностью",
      args: Object.assign({}, listArgs, MODXResourceArgs),
    }),
    modxResources: {
      type: new GraphQLList(MODXResourceType),
      description: "Список MODX-документов",
      args: Object.assign({}, listArgs, MODXResourceArgs),
    },
    modxResource: {
      type: MODXResourceType,
      description: MODXResourceType.description,
      args: listArgs,
    },

  }),
});



const mutationFields = Object.assign({
    clearCache: {
      type: GraphQLBoolean,
      description: "Сброс кеша",
    },
  }, {

  }
);

export const Mutation = new GraphQLObjectType({ //⚠️ NOT mutiation
  name: 'Mutation',
  fields: () => (mutationFields)
});