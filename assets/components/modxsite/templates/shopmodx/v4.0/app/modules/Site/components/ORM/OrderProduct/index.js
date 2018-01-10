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

import * as OrderProduct from 'shopmodx-react/components/ORM/OrderProduct';

let {
	OrderProductFields,
} = OrderProduct;


Object.assign(OrderProductFields, {
	// size: {
	// 	type: GraphQLString,
	// 	description: "Размер (модификация)",
	// },
});

const OrderProductType = new GraphQLObjectType({
  name: 'OrderProductType',
  description: 'Позиция заказа',
  fields: () => (OrderProductFields),
});


Object.assign(OrderProduct, {
	default: OrderProductType,
});


module.exports = OrderProduct;