import {
  GraphQLInt,
  GraphQLString,
  GraphQLList,
  GraphQLObjectType,
  GraphQLInputObjectType,
  GraphQLEnumType,
} from 'graphql';



export const getList = (object, args, context, info) => {

  const {
    SendMODXRequest,
  } = context;

  return new Promise((resolve, reject) => {
    let {
      id,
      limit,
      page,
      offset: start,
      count,
      search,
      ownProfile,
      delegatesOnly,
      ownOrder,
    } = args || {};

    limit = limit || 0;

    let action = 'orders/getdata';

    if(ownOrder){
      
      action = 'order/own/getdata';

    }

    const url = `/assets/components/shopmodx/connectors/connector.php?pub_action=${action}`;

    let params = Object.assign({...args}, {
      id,
      limit,
      page,
      start,
      count: count === undefined ? 1 : count,
      search,
      url,
    });

    let request = SendMODXRequest(action, params);

    request
    .then((data) => {

      // console.log("Orders result", data);

      if(!data.success){

        // Если запрашивали личный объект заказа пользователя, то отсутствие объекта пока не рассчитывается как ошибка


        return !ownOrder ? reject(data.message || "Ошибка выполнения запроса") : resolve(null);

      }

      if(data.object){

        if(!Array.isArray(data.object)){
          data.object = [data.object];
        }

        data.object.map(n => {

          return n;

        });

      }

      return resolve(data);
    })
    .catch((e) => {
      return reject(e);
    })
    ;
  });
}


export const orderAddProduct = (object, args, context, info) => {

  const {
    SendMODXRequest,
  } = context;

  return new Promise((resolve, reject) => {
    let {
      id,
    } = args || {};

    let action = 'orders/add_product';

    const url = `/assets/components/shopmodx/connectors/connector.php?pub_action=${action}`;

    let params = Object.assign({...args}, {
      url,
    });

    let request = SendMODXRequest(action, params);

    request
    .then((data) => {

      if(!data.success){

        return reject(data.message || "Ошибка выполнения запроса");
      }

      return resolve(data.object);
    })
    .catch((e) => {
      return reject(e);
    })
    ;
  });
}

export const orderRecalculate = (object, args, context, info) => {

  const {
    SendMODXRequest,
  } = context;

  return new Promise((resolve, reject) => {
    let {
      id,
    } = args || {};

    let action = 'recalculate';

    const url = `/assets/components/shopmodx/connectors/connector.php?pub_action=${action}`;

    let params = Object.assign({...args}, {
      url,
    });

    let request = SendMODXRequest(action, params);

    request
    .then((data) => {

      if(!data.success){

        return reject(data.message || "Ошибка выполнения запроса");
      }

      return resolve(data.object);
    })
    .catch((e) => {
      return reject(e);
    })
    ;
  });
}


export const orderSubmit = (object, args, context, info) => {

  const {
    SendMODXRequest,
  } = context;

  return new Promise((resolve, reject) => {
    let {
      id,
    } = args || {};

    let action = 'order/submit';

    const url = `/assets/components/shopmodx/connectors/connector.php?pub_action=${action}`;

    let params = Object.assign({...args}, {
      url,
    });

    let request = SendMODXRequest(action, params);

    request
    .then((data) => {

      // console.log("orderSubmit result", data);

      if(!data.success){

        // return reject(data.message || "Ошибка выполнения запроса");
        return reject({
          message: data.message || "Ошибка выполнения запроса",
          // locations: data.data,
          errors: data.data,
          path: "OrderSubmit",
        });
      }

      return resolve(data.object);
    })
    .catch((e) => {
      return reject(e);
    })
    ;
  });
}

