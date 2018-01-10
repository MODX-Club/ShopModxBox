
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
  GraphQLNonNull,
} from 'graphql';

import * as Order from 'shopmodx-react/components/ORM/Order';

let {
	OrderMutations,
	default: OrderType,
} = Order;

let {
	orderAddProduct,
	orderUpdateProduct,
} = OrderMutations;


Object.assign(orderAddProduct.args, {
  // size: {
  //   type: GraphQLString,
  //   description: "Размер (модификация)",
  // },
});


Object.assign(orderUpdateProduct.args, {
  // size: {
  //   type: GraphQLString,
  //   description: "Размер (модификация)",
  // },
});


Object.assign(Order, {
	OrderMutations,
});


module.exports = Order;
