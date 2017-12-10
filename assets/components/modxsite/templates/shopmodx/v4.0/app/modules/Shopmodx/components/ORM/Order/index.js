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

import GraphQLJSON from 'graphql-type-json';

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


// import MODXResourceType from '../MODXResource';

import OrderProductType from '../OrderProduct';


export const OrderArgs = {
  id: {
    type: GraphQLInt,
    description: "ID",
  },
  ownOrder: {
    type: GraphQLBoolean,
    description: "Текущий заказ пользователя",
  },
};


export const OrdersArgs = Object.assign({
}, listArgs);


const OrderType = new GraphQLObjectType({
  name: 'OrderType',
  description: 'Заказ',
  fields: () => {

    return { 
      id: {
        type: GraphQLInt,
        description: "ID",
      },
      number_history: {
        type: GraphQLInt,
        description: "Порядковый номер истории",
      },
      status_id: {
        type: GraphQLInt,
        description: "ID статуса",
      },
      status_str: {
        type: GraphQLString,
        description: "Статус",
      },
      contractor: {
        type: GraphQLInt,
        description: "ID клиента",
      },
      createdby: {
        type: GraphQLInt,
        description: "Кем создан",
      },
      createdon: {
        type: GraphQLString,
        description: "Дата создания",
      },
      editedby: {
        type: GraphQLInt,
        description: "Кем отредактирован",
      },
      editedon: {
        type: GraphQLString,
        description: "Дата редактирования",
      },
      manager: {
        type: GraphQLInt,
        description: "ID менеджера",
      },
      address: {
        type: GraphQLString,
        description: "Адрес",
      },
      comments: {
        type: GraphQLString,
        description: "Комментарий",
      },
      discount: {
        type: GraphQLFloat,
        description: "Скидка",
      },
      positions: {
        type: GraphQLInt,
        description: "Количество позиций",
      },
      total: {
        type: GraphQLInt,
        description: "Количество товаров",
      },
      sum: {
        type: GraphQLFloat,
        description: "Сумма с учетом скидки",
      },
      original_sum: {
        type: GraphQLFloat,
        description: "Сумма без учета скидки",
      },
      pay_id: {
        type: GraphQLInt,
        description: "ID оплаты",
      },
      paysys_invoice_id: {
        type: GraphQLString,
        description: "Номер счета в платежной системе",
      },
      pay_date: {
        type: GraphQLString,
        description: "Дата платежа",
      },
      pay_sum: {
        type: GraphQLFloat,
        description: "Сумма платежа",
      },
      paysystem_name: {
        type: GraphQLString,
        description: "Название платежной системы",
      },
      Products: {
        type: new GraphQLList(OrderProductType),
        description: "Товарная позиция",
        resolve: (source, args, context, info) => {

          const {
            id,
          } = source;

          if(!id){
            return null;
          }

          const {
            rootResolver,
          } = context;

          Object.assign(args, {
            order: id,
            // _store: "remote",
          });

          return rootResolver(null, args, context, info);

        },
      },
    };

  },
});


export const OrderMutations = {

  orderAddProduct: {
    type: OrderType,
    description: "Добавление товара в корзину",
    args: {
      product_id: {
        type: new GraphQLNonNull(GraphQLInt),
        description: "ID товара",
      },
      quantity: {
        type: new GraphQLNonNull(GraphQLInt),
        description: "Количество",
      },
    },
  },

  orderUpdateProduct: {
    type: OrderType,
    description: "Обновление товара в корзине",
    args: {
      position_id: {
        type: new GraphQLNonNull(GraphQLInt),
        description: "ID товарной позиции",
      },
      quantity: {
        type: new GraphQLNonNull(GraphQLInt),
        description: "Количество",
      },
    },
  },

  orderSubmit: {
    type: OrderType,
    description: "Оформление заказа",
    args: {
      params: {
        type: GraphQLJSON,
        description: "Параметры заказа",
      }
    },
  },

};


export const getList = (source, args, context, info) => {

  const {
  } = context.state;

  const {
  } = args;




  if(ownProfile){

    const {
      remoteResolver,
    } = context;

    if(!remoteResolver){
      throw("remoteResolver undefined");
    }


    return new Promise(async (resolve, reject) => {

      try{

        const result = await remoteResolver(null, args, context, info);



        // if(result && result.success){

        //   resolve(result);

        // }
        // else{
        //   reject(result);
        // }

        resolve( result && List([result]) || null);

        // resolve(result);

      }
      catch(e){
        reject(e);
      }

    });
    
  }
 

  let state = UsersStore.getState();

  if(myOnly){

    const {
      user,
    } = context.props || {};

    const currentUser = user && user.user;

    if(!currentUser){
      return null;
    }

    state = state.filter(n => n.createdby === currentUser.id);

  }

  if(username){
    state = state.filter(n => n.username === username);
  }

  if(delegatesOnly){
    state = state.filter(n => n.delegate === true);
  }

  return state;
};


export default OrderType;