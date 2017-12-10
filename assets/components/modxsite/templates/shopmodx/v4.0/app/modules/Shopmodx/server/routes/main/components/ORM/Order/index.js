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
    } = args || {};

    limit = limit || 0;

    let action = 'orders/getdata';

    const url = `/assets/components/shopmodx/connectors/connector.php?pub_action=${action}`;

    let params = {
      id,
      limit,
      page,
      start,
      count: count === undefined ? 1 : count,
      search,
      url,
    };

    let request = SendMODXRequest(action, params);

    request
    .then((data) => {

      // console.log("Orders result", data);

      if(!data.success){

        return reject(data.message || "Ошибка выполнения запроса");
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

