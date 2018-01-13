

import {
  AppMain,
  MainPage,
  DbPage,
} from "modules/Site";

import DefaultPage from '../../components/Pages/DefaultPage';

// console.log("CompanyCreatePage", CompanyCreatePage);
import UserPage from 'shopmodx-react/components/Pages/Users/User';
import OfficeOrdersPage from '../../components/Pages/Office/Orders';
import OfficeOrderPage from 'shopmodx-react/components/Pages/Office/Orders/Order';

let routes = [{
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
      path: "/office/orders",
      component: OfficeOrdersPage,
      childRoutes: [
      ],
    },
    {
      path: "/office/orders/:orderId",
      component: OfficeOrderPage,
    },
    {
      path: "/profile/:username",
      component: UserPage,
      childRoutes: [{
        path: "/profile/:username/:action",
      }],
    },
    {
    path: "*",
      // name: "notfound",
      component: DefaultPage,
      // redirect: '/'
    },
  ]
}];

// if(typeof window !== 'undefined'){
//   routes.childRoutes.push({
//     path: "*",
//     // name: "notfound",
//     component: NotFoundPage,
//     // redirect: '/'
//   });
// }

export default routes;