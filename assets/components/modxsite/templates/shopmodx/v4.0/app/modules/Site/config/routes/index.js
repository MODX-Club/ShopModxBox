

import {
  AppMain,
  DefaultPage,
  MainPage,
  DbPage,
} from "modules/Site";

// console.log("CompanyCreatePage", CompanyCreatePage);

let routes = {
  path: "/",
  component: AppMain,
  indexRoute: { 
    component: MainPage
  },
  childRoutes: [
    {
      path: "/",
      component: MainPage,
      // childRoutes: [
      //   {
      //     path: "/index",
      //   },
      //   {
      //     path: "/@:lat,:lng,:zoom",
      //   },
      //   {
      //     path: "/index/@:lat,:lng,:zoom",
      //   },
      // ],
    },
    {
      path: "/db",
      component: DbPage,
    },
    {
    path: "*",
      // name: "notfound",
      component: DefaultPage,
      // redirect: '/'
    },
  ]
};

// if(typeof window !== 'undefined'){
//   routes.childRoutes.push({
//     path: "*",
//     // name: "notfound",
//     component: NotFoundPage,
//     // redirect: '/'
//   });
// }

export default routes;