
import React, {Component} from 'react';

import PropTypes from 'prop-types';

import * as proxyActions from 'modules/Redux/actions/proxyActions';
import * as userActions from 'modules/Redux/actions/userActions';
import * as documentActions from 'modules/Redux/actions/documentActions';

import customPropTypes from 'material-ui/utils/customPropTypes';
import { lightBlue } from 'material-ui/styles/colors';

import { connect } from 'react-redux';
import { browserHistory, Router } from 'react-router';
import { bindActionCreators } from 'redux';
import { Provider } from "react-redux";

import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import createMuiTheme from 'material-ui/styles/theme';
import createPalette from 'material-ui/styles/palette';

import {DataStore, Dispatcher} from 'react-cms-data-view';

// import {request} from 'react-cms-data-view/src/Utils';

import ReactCmsApp from 'react-cms/src/app/components/App';

import Renderer from 'modules/Site/components/App/Renderer';
 
import {
  buildExecutionContext,
  buildResolveInfo,
  getOperationRootType,
} from 'graphql/execution/execute';

 
export const createStores = function(){

  let stores = {
    MODXResourcesStore: new DataStore(new Dispatcher()),
  };

  stores.ContactsStore = stores.CompaniesStore;

  return stores;

}

export const initData = function(apiData){
  let {
    MODXResourcesStore,
  } = this.state;

  if(apiData){

    const {
      modxResources,
    } = apiData;

    modxResources !== undefined && MODXResourcesStore.getDispatcher().dispatch(MODXResourcesStore.actions['SET_DATA'], modxResources || []);

  }

  // this.forceUpdate();

  this.setState({
    inited: true,
  });

}

export const createStoreObject = function(Class, data){
  return new Class(data, this);
}

const customStyles = createMuiTheme({
  palette: createPalette({
    // primary: lightBlue,
  }),
});




export class MainApp extends Component{

  static childContextTypes = {
    appExports: PropTypes.object,
  };

  getChildContext() {

    let {
      appExports,
    } = this.props;

    let context = {
      appExports,
    };

    return context;
  }

  render(){

    return this.props.children;

  }

}

/*
  Инициируется один раз
*/

let {
  ...defaultProps
} = ReactCmsApp.defaultProps || {};

Object.assign(defaultProps, {
  connector_url: '/assets/components/modxsite/connectors/connector.php',
});


let {
  ...propTypes
} = ReactCmsApp.propTypes || {};

Object.assign(propTypes, {
  document: PropTypes.object.isRequired,
  user: PropTypes.object.isRequired,
});


let {
  ...childContextTypes
} = ReactCmsApp.childContextTypes || {};

Object.assign(childContextTypes, {
  order: PropTypes.object,              // Текущий объект заказа пользователя
  menuItems: PropTypes.array,  // Пункты меню
  addToBasket: PropTypes.func,
});

export class AppMain extends ReactCmsApp{

  static propTypes = propTypes;

  static defaultProps = defaultProps;
 

  static childContextTypes = childContextTypes;
  

  getChildContext() {

    let {
    } = this.props;

    let {
      order,
      menuItems,
    } = this.state;

    let context = super.getChildContext() || {};

    Object.assign(context, {
      order,
      menuItems,
      addToBasket: ::this.addToBasket,
    });

    return context;
  }

  constructor(props){

    super(props);

    let notifications_store = new DataStore(new Dispatcher());
  

    Object.assign(this.state, {
      notifications_store: notifications_store,
      inited: false,
      developMode: true,
      order: {},
      menuItems: [],
    }, createStores());

    let {
      user,
      document: {
        stores,
      },
    } = props;

    user.hasPermission = this.hasPermission;

    stores.notifications_store = notifications_store;

    this.initData = initData.bind(this);
    this.createStoreObject = createStoreObject.bind(this);

  }

  getCounters(){

    return null;

    const metrika = ` 
    `;

    return <Grid
      container
      gutter={0}
      align="center"
      style={{
        // paddingLeft: 15,
      }}
    >

      <Grid
        item
      >

        {typeof window !== "undefined" && <div dangerouslySetInnerHTML={{__html: metrika}}></div>}  
        
      </Grid>
      
    </Grid>;
  }


  // Достижение цели
  triggerGoal(goal){

  }
  

  componentWillMount(){


    const {
      document,
    } = this.props;

    let {
      // apiData,
      resourceState,
    } = document;

    if(resourceState && resourceState){

      const {
        state,
      } = resourceState;

      const {
        menuItems,
      } = state || {};

      menuItems && Object.assign(this.state, {
        menuItems,
      });

    }

    return;

  }

  componentDidMount(){
    
    this.loadApiData();

  }


  componentDidUpdate(prevProps, prevState){

  }


  initUser(user){

  }

  // Перезагружаем API-данные со сбросом кеша
  reloadApiData = async () => {

    await this.remoteQuery({
      operationName: "clearCache",
    });

    return this.loadApiData();
  }


  hasPermission = (perm) => {
    let{
      user,
    } = this.props.user;

    if(!user || user.id == 0){
      return false;
    }

    return user.sudo === true || (user.policies && user.policies[perm]) || false;
  }


  async addToBasket(product){

    console.log("addToBasket product", product);

    const {
      documentActions,
    } = this.props;

    if(!product){
      return documentActions.addInformerMessage("Не был получен товар");
    }

    const {
      id,
    } = product;

    let result = await this.remoteQuery({
      operationName: "addToBasket",
      variables: {
        productId: id,
      },
    })
    .then(r => r)
    .catch(e => {
      throw(e)
    });

    return result;

  }


  render() {

    let {
      children, 
      user,
      ...other
    } = this.props;

    let {
      notifications_store,
    } = this.state;
 
    const inited = true;

    let authOpen = user && user.loginModalOpened || false;

    return <MuiThemeProvider theme={customStyles}>
      <Renderer 
        inited={inited}
        children={children}
        authOpen={authOpen}
        notifications_store={notifications_store}
      />
    </MuiThemeProvider>

    ;
  }
}


function mapDispatchToProps(dispatch) {

  let props = {
    proxyActions: bindActionCreators(proxyActions, dispatch),
    userActions: bindActionCreators(userActions, dispatch),
    documentActions: bindActionCreators(documentActions, dispatch),
  }

  props.addInformerMessage = props.documentActions.addInformerMessage;

  return props;
}

function mapStateToProps(state) {

  var currentState = {};

  // Object.assign(currentState, state.document);

  currentState.user = state.user;
  currentState.document = state.document;

  return currentState;
}

export default connect(mapStateToProps, mapDispatchToProps)(AppMain);
