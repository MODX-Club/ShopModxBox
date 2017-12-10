import {
  GraphQLInputObjectType,
  GraphQLObjectType,
  GraphQLList,
  GraphQLNonNull,
  GraphQLEnumType,
  GraphQLInt,
  GraphQLString,
  GraphQLBoolean,
  GraphQLFloat,
} from 'graphql';



import {
  ObjectsListType,
  imageType,
  order,
  SortBy,
  SortField,
  listArgs,
} from 'react-cms/src/app/components/ORM/fields';


// import {
//   CommentEditorStateType,
// } from 'react-cms/src/app/components/ORM/Comment';


import {
  CommentEditorStateType,
} from './EditorState';

export {
  ObjectsListType,
  imageType,
};


export {order}
// export const order = {
//   type: new GraphQLEnumType({
//     name: "SortType",
//     values: {
//       asc: {
//         value: 'asc',
//         description: 'В прямом порядке',
//       },
//       desc: {
//         value: 'desc',
//         description: 'В обратном порядке',
//       },
//     },
//   }),
//   description: 'Порядок сортировки',
// };

export {SortBy}
// export const SortBy = new GraphQLInputObjectType({
//   name: "SortBy",
//   fields: {
//     by: {
//       type: new GraphQLEnumType({
//         name: 'SortByValues',
//         values: {
//           id: {
//             value: 'id',
//             description: 'По ID',
//           },
//           rand: {
//             value: 'rand()',
//             description: 'В случайном порядке',
//           },
//         },
//       }),
//       description: 'Способ сортировки',
//     },
//     dir: order,
//   },
// });

export {SortField}
// export const SortField = {
//   type: new GraphQLList(SortBy),
// };

export {listArgs}
// export const listArgs = {
//   ids: {
//     type: new GraphQLList(GraphQLInt),
//     description: 'Список ID',
//   },
//   search: {
//     type: GraphQLString,
//     description: 'Поисковый запрос',
//   },
//   sort: SortField,
//   limit: {
//     type: new GraphQLNonNull(GraphQLInt),
//     description: 'Лимит записей',
//   },
//   page: {
//     type: GraphQLInt,
//     description: 'Страница',
//   },
//   offset: {
//     type: GraphQLInt,
//     description: 'Сколько записей пропустить',
//   },
// };


// export class ObjectsListType extends GraphQLObjectType{

//   constructor(props){

//     props = props || {};

//     let {
//       type,
//       args,
//       fields,
//       ...other
//     } = props;

//     fields = Object.assign(fields || {}, {
//       success: {
//         type: GraphQLBoolean,
//       },
//       message: {
//         type: GraphQLString,
//       },
//       count: {
//         type: GraphQLInt,
//       },
//       total: {
//         type: GraphQLInt,
//       },
//       limit: {
//         type: GraphQLInt,
//       },
//       page: {
//         type: GraphQLInt,
//       },
//       object: {
//         type: new GraphQLList(type),
//       },
//     });

//     Object.assign(props, {
//       fields,
//     });

//     super(props);

//   }
// }


export class listField {

  constructor(props){

    // Object.assign(this, props);


    let {
      description,
      args,
      resolve,
      ...other
    } = props;

    args = Object.assign({...listArgs}, args || {});

    Object.assign(this, {
      description,
      args,
    });

    // var a = {
    //   // type: new ObjectsListType({
    //   //   ...other,
    //   // }),
    //   // description,
    //   // args,
      
    // }

    this.type = new ObjectsListType({
      ...other,
    });

    // return this;

    // this.resolve = resolve || ::this.resolve;
  }

  beforeCount(source, args, context, info){

    let {
      ids,
    } = args;

    if(ids && ids.length){
      source = source.filter(n => ids.indexOf(n.id) !== -1);
    }

    return source;
  }

  // resolve(source, args, context, info){
    
    

  //   const {
  //     fieldName,
  //   } = info;
          
  //   let result = source && source[fieldName] || undefined;

  //   if(result){
      

  //     let {
  //       ids,
  //       offset,
  //       limit,
  //       page,
  //     } = args;

  //     page = page || 1;


  //     result = this.beforeCount(result, args, context, info);

  //     const total = result.size;
      
  //     if(offset){
  //       result = result.skip(offset);
  //     }

  //     if(limit){

  //       if(page > 1){
  //         result = result.skip(limit * (page - 1));
  //       }

  //       result = result.take(limit);
  //     }

  //     result = {
  //       success: true,
  //       message: '',
  //       count: result.size,
  //       total,
  //       limit,
  //       page,
  //       object: result,
  //     };
  //   }

  //   return result;
  // }
}


const imageFields = {
  original: {
    type: GraphQLString,
    resolve: (image) => {

    	// 
      return image && image.original || image || null;
    },
  },
  thumb: {
    type: GraphQLString,
    resolve: (image) => {
      return `images/resized/thumb/${image.original}`;
    },
  },
  marker_thumb: {
    type: GraphQLString,
    resolve: (image) => {
      return `images/resized/marker_thumb/${image.original}`;
    },
  },
  slider_thumb: {
    type: GraphQLString,
    resolve: (image) => {
      return `images/resized/slider_thumb/${image.original}`;
    },
  },
  small: {
    type: GraphQLString,
    resolve: (image) => {
      return `images/resized/small/${image.original}`;
    },
  },
  middle: {
    type: GraphQLString,
    resolve: (image) => {
      return `images/resized/middle/${image.original}`;
    },
  },
  big: {
    type: GraphQLString,
    resolve: (image) => {
      return `images/resized/big/${image.original}`;
    },
  },
};

// export const imageType = {
//   type: new GraphQLObjectType({
//     name: 'Images',
//     // fields: imageFields,
//     fields: {
//       original: {
//         type: GraphQLString,
//         resolve: (image) => {
//           return image;
//         },
//       },
//       thumb: {
//         type: GraphQLString,
//         resolve: (image) => {
//           return `images/resized/thumb/${image}`;
//         },
//       },
//       marker_thumb: {
//         type: GraphQLString,
//         resolve: (image) => {
//           return `images/resized/marker_thumb/${image}`;
//         },
//       },
//       slider_thumb: {
//         type: GraphQLString,
//         resolve: (image) => {
//           return `images/resized/slider_thumb/${image}`;
//         },
//       },
//       small: {
//         type: GraphQLString,
//         resolve: (image) => {
//           return `images/resized/small/${image}`;
//         },
//       },
//       middle: {
//         type: GraphQLString,
//         resolve: (image) => {
//           return `images/resized/middle/${image}`;
//         },
//       },
//       big: {
//         type: GraphQLString,
//         resolve: (image) => {
//           return `images/resized/big/${image}`;
//         },
//       },
//     },
//   }),
//   resolve: (object) => {

//     const {
//       image,
//     } = object;

//     return image && image.replace(/^\//g, '') || null;
//   },
// };

export const coordsType = new GraphQLObjectType({
  name: 'coordsType',
  description: "Координаты",
  fields: {
    lat: {
      type: GraphQLFloat,
    },
    lng: {
      type: GraphQLFloat,
    },
  },
})


export const TVsField = {
  type: new GraphQLObjectType({
    name: 'ResourceTSvType',
    fields: {
      address: {
        type: GraphQLString,
        description: 'Адрес',
      },
      site: {
        type: GraphQLString,
        description: 'Веб-сайт',
      },
      facility_type: {
        type: GraphQLString,
        description: 'Тип заведения',
      },
      phones: {
        type: GraphQLString,
        description: 'Телефон',
      },
      work_time: {
        type: GraphQLString,
        description: 'Рабочее время',
      },
      prices: {
        type: GraphQLString,
        description: 'Цены',
      },
      metro: {
        type: GraphQLString,
        description: 'Метро',
      },
    },
  }),
  resolve: (object) => {
    let tvs = {};

    if(object.tvs){


        for(var name in object.tvs){

          var tv = object.tvs[name];

          if(tv){

            let v;

            if(tv.tv_id === undefined){
              tvs[name] = tv;
            }
            else{

              let {
                tv_id: id,
                caption,
                value,
              } = tv;
              
              tvs[name] = value;

            }

          }
        }

    }

    return tvs;
  },
};

export const GalleryField = {
  type: new GraphQLList(
    new GraphQLObjectType({
      name: 'galleryType',
      fields: {
        image: {
          type: GraphQLString,
        },
        imageFormats: imageType,
        // image: imageType,
      },
    })
  ),
  resolve: (object) => {
    return object.gallery || [];
  },
};


export const AdvButtonType = new GraphQLObjectType({
  name: "AdvButtonType",
  description: "Рекламная кнопка",
  fields: {
    title: {
      type: GraphQLString,
      description: "Надпись кнопки",
    },
    link: {
      type: GraphQLString,
      description: "Ссылка",
    },
  },
});


export const Extends = new GraphQLObjectType({
  name: 'ExtendsType',
  fields: {
    gallery: {
      type: new GraphQLList(GraphQLString),
      description: "Галерея",
      resolve: (source) => {
        return source && source.gallery || null;
      },
    },
    worktime: {
      type: GraphQLString,
      description: "Рабочее время",
    },
    teaser: {
      type: GraphQLString,
      description: "Тизер или миссия",
    },
    advantages: {
      type: CommentEditorStateType,
      description: "Преимущества",
    },
    advButtons: {
      type: new GraphQLList(AdvButtonType),
      description: "Рекламные кнопки",
    },
  }
});


export const LogoType = new GraphQLObjectType({
  name: "LogoType",
  fields: {
    image: {
      type: GraphQLString,
    },
    imageFormats: imageType,
  },
});


const ScheduleDayRangeType = new GraphQLObjectType({
  name: "ScheduleDayRangeType",
  description: "Диапазон С и По",
  fields: {
    year: {
      type: new GraphQLNonNull(GraphQLInt),
      description: "Год",
    },
    month: {
      type: new GraphQLNonNull(GraphQLInt),
      description: "Месяц",
    },
    day: {
      type: new GraphQLNonNull(GraphQLInt),
      description: "День",
    },
    hour: {
      type: new GraphQLNonNull(GraphQLInt),
      description: "Час",
    },
    minute: {
      type: new GraphQLNonNull(GraphQLInt),
      description: "Минута",
    },
    second: {
      type: new GraphQLNonNull(GraphQLInt),
      description: "Секунда",
    },
    weekDay: {
      type: new GraphQLNonNull(GraphQLInt),
      description: "Порядковый день недели",
    },
  },
});

export const ScheduleDayType = new GraphQLObjectType({
  name: "ScheduleDayType",
  fields: {
    start: {
      type: ScheduleDayRangeType,
      description: ScheduleDayRangeType.description,
    },
    end: {
      type: ScheduleDayRangeType,
      description: ScheduleDayRangeType.description,
    },
  },
});


