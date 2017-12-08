

import {
  MainApp,
  MainPage,
  DbPage,
} from "modules/Shopmodx";

// console.log("CompanyCreatePage", CompanyCreatePage);

let routes = {
  path: "/",
  component: MainApp,
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
    // {
    // path: "*",
    //   // name: "notfound",
    //   component: NotFoundPage,
    //   // redirect: '/'
    // },
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