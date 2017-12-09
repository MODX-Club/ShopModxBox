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



export const UsersArgs = Object.assign({
  delegatesOnly: {
    type: GraphQLBoolean,
    description: "Только представители",
  },
  myOnly: {
    type: GraphQLBoolean,
    description: "Только мои",
  },
}, listArgs);


export const UserType = new GraphQLObjectType({
  name: 'UserType',
  description: 'Пользователь',
  fields: () => {

    return { 
      id: {
        type: GraphQLInt
      },
      username: {
        type: GraphQLString
      },
      fullname: {
        type: GraphQLString
      },
      email: {
        type: GraphQLString,
      },
      image: {
        type: GraphQLString,
      },
      imageFormats: imageType,
      // photo: {
      //   type: new GraphQLObjectType({
      //     name: "SDfsdf",
      //     fields: {
      //       image: imageType,
      //     },
      //   }),
      //       resolve: (data) => {

      //         return "DSfds";
      //       },
      // },
      active: {
        type: GraphQLBoolean,
      },
      blocked: {
        type: GraphQLBoolean,
      },
      sudo: {
        type: GraphQLBoolean,
      },
      delegate: {
        type: GraphQLBoolean,
        description: "Флаг того, что пользователь - представитель компании.",
      },
      createdon: {
        type: GraphQLInt,
        description: "Дата регистрации пользователя",
      },
      offer: {
        type: GraphQLString,
        description: "Коммерческое предложение",
      },
      offer_date: {
        type: GraphQLInt,
        description: "Дата отправки коммерческого предложения",
      },
      contract_date: {
        type: GraphQLInt,
        description: "Дата заключения сделки",
      },
      createdby: {
        type: GraphQLInt,
        description: "Кем создана учетка пользователя",
      },
      // comments: {
      //   type: new GraphQLList(CommentType),
      //   description: CommentType.description,
      //   resolve: async (source, args, context, info) => {

      //     const {
      //       id,
      //     } = source;

      //     if(!id){
      //       return null;
      //     }

      //     Object.assign(args, {
      //       createdby: id,
      //     });


      //     const {
      //       rootResolver,
      //     } = context;

      //     return rootResolver(null, args, context, info);
      //   },
      // },
      // notices: {
      //   type: new GraphQLList(UserNoticeType),
      //   description: "Список настроек уведомлений",
      // },
      // _Dirty: {
      //   type: GraphQLBoolean,
      //   description: "Флаг того, что объект изменен",
      //   resolve: source => {

      //     return source && source._isDirty ? true : false;

      //   },
      // },
    };
  },
});


export const getList = (source, args, context, info) => {

  const {
    UsersStore,
  } = context.state;

  const {
    username,
    delegatesOnly,
    myOnly,
    ownProfile,
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


export default UserType;