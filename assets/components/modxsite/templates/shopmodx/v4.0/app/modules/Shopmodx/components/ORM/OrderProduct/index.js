import {
  GraphQLID,
  GraphQLInt,
  GraphQLString,
  GraphQLList,
  GraphQLObjectType,
  GraphQLInputObjectType,
  GraphQLEnumType,
  GraphQLFloat,
  GraphQLBoolean,
} from 'graphql';

import { List } from 'immutable';

// import {
//   CommentType,
// } from '../Comment';

// import UserNoticeType from '../UserNotice';

import {
  listField,
  listArgs,
  imageType,
  // ObjectsListType,
} from '../fields';


import MODXResourceType from '../MODXResource';


export const OrderProductArgs = {
  id: {
    type: GraphQLInt,
    description: "ID",
  },
};


export const OrdersProductsArgs = Object.assign({
  order: {
    type: GraphQLInt,
    description: "ID заказа",
  },
}, listArgs);


const OrderProductType = new GraphQLObjectType({
  name: 'OrderProductType',
  description: 'Позиция заказа',
  fields: () => {

    return { 
      id: {
        type: GraphQLInt,
        description: "ID",
      },
      order_id: {
        type: GraphQLInt,
        description: "ID заказа",
      },
      product_id: {
        type: GraphQLInt,
        description: "ID товара",
      },
      quantity: {
        type: GraphQLInt,
        description: "Количество",
      },
      price: {
        type: GraphQLFloat,
        description: "Стоимость",
      },
      Product: {
        type: MODXResourceType,
        description: "Товар",
        resolve: (source, args, context, info) => {

          const {
            product_id,
          } = source;

          // console.log("product_id", product_id);

          if(!product_id){
            return null;
          }

          const {
            rootResolver,
          } = context;

          Object.assign(args, {
            id: product_id,
            // _store: "remote",
          });

          return rootResolver(null, args, context, info);

        },
      },
    };
  },
});


export default OrderProductType;