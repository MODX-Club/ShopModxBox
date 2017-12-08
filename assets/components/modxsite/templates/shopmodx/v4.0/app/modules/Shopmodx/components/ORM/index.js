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
  CommentEditorStateType,
} from './EditorState';

import PaymentMethodType from 'react-cms/src/app/components/ORM/PaymentMethod';


import SiteContentType, {
  SiteContentArgs,
} from 'react-cms/src/app/components/ORM/SiteContent';


import MODXResourceType from './MODXResource';



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
      args: Object.assign({}, listArgs),
    }),
    modxResources: {
      type: new GraphQLList(MODXResourceType),
      description: "Список MODX-документов",
      args: Object.assign({}, listArgs),
    },
    modxResource: {
      type: MODXResourceType,
      description: MODXResourceType.description,
      args: listArgs,
    },

  }),
});



// const mutationFields = Object.assign({
//   }
// );

// export const Mutation = new GraphQLObjectType({ //⚠️ NOT mutiation
//   name: 'Mutation',
//   fields: () => (mutationFields)
// });

export const Mutation = undefined;