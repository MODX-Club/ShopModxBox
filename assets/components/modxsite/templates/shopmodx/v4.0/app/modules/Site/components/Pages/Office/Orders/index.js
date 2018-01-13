import React, {Component} from 'react';

import PropTypes from 'prop-types';

import Grid from 'material-ui/Grid';
import { browserHistory } from 'react-router/lib';
import Typography from 'material-ui/Typography/Typography';

import ShopmodxOfficeOrdersPage from 'shopmodx-react/components/Pages/Office/Orders';


import Page from '../../';


let {
  ...contextTypes
} = ShopmodxOfficeOrdersPage.contextTypes;


Object.assign(contextTypes, {
  isDemo: PropTypes.bool.isRequired,
  toggleIsDemo: PropTypes.func.isRequired,
});


export default class OfficeOrdersPage extends ShopmodxOfficeOrdersPage{


  static contextTypes = contextTypes;


  async loadServerData(provider, options = {}){

    let result = await super.loadServerData(provider, options);
    
    const {
      req,	
      limit = defaultProps.limit,
      ordersSort,
      ordersStatuses,
      params,
      location,
    } = options;


		const {
			pathname,
			query,
		} = location || {};

		const {
			page,
		} = query || {};

    
    const {
      user,
    } = result && result.data || {};
    
    // console.log("options", options);
    // console.log("OfficeOrdersPage result", result);
 
    // Получаем список заказов
    await provider({
      operationName: "Orders",
      variables: {
        withPagination: true,
        orderGetProducts: true,
        orderProductGetProduct: true,
        ordersLimit: limit,
        orderGetContractor: true,
        ordersPage: page,
        ordersSort,
        ordersStatuses,
      },
      req,
    })
    .then(r => {
      // console.log("Orders result", r);

      Object.assign(result.data, r.data);

    })
    .catch(e => {
      console.error(e);
    }); 



    return result;

  }


  render(){

    const {
      ordersList,
      orderBy,
      orderDir,
      statuses,
    } = this.state;

    const {
      View,
    } = this.props;

    const {
      user: {
        user: currentUser,
      },
      isDemo,
    } = this.context;

    
    let output;


    if(!isDemo){
      return super.render();
    }
    else{
      output = <Grid
      item
      xs={12}
    >

      {currentUser 
        ? 
          null
        :
        <Typography
        type="subheading"
        style={{
          color: "red",
        }}
        >
          
          Это демо-версия магазина, поэтому вам видны все заказы. <br />
          В штатном режиме непревилегированным пользователям видны только их заказы.<br />
          Вы можете <a
            href="javascript:;"
            onClick={e => {
              e.preventDefault();
              
              const {
                toggleIsDemo,
              } = this.context;

              toggleIsDemo();
              
            }}
          >
          отключить тестовый режим
          </a>, чтобы увидеть это.

          {/* Для просмотра  ewfewfwe few fewfeewfзаказов необходимо <a
            href="javascript:;"
            onClick={e => {
              e.preventDefault();
              
              const {
                userActions,
              } = this.context;
              
              userActions.loginClicked();
              
            }}
          >
          авторизоваться
          </a> */}
        </Typography>
      }

      <View
        ordersList={ordersList}
        orderBy={orderBy}
        orderDir={orderDir}
        setOrder={::this.setOrder}
        statuses={statuses}
        onOrderStatusCheck={::this.onOrderStatusCheck}
      />

    </Grid>
    }


    return Page.prototype.render.call(this, output);

  }


}