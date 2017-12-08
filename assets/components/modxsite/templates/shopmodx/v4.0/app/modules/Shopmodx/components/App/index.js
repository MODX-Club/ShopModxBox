import './styles/styles.less';

import React, {Component} from 'react';

import PropTypes from 'prop-types';

import * as proxyActions from 'modules/Redux/actions/proxyActions';
import * as userActions from 'modules/Redux/actions/userActions';
import * as documentActions from 'modules/Redux/actions/documentActions';

import customPropTypes from 'material-ui/utils/customPropTypes';
import { createStyleSheet } from 'jss-theme-reactor';
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

import Informer from 'structor-templates/components/Informer';

// import MainMenu from './MainMenu';

import Auth from 'react-cms/src/app/components/Auth';

// import ORM from '../ORM';

// import CoinHive from 'react-coin-hive/src';

// import locale from 'moment/src/locale/ru';
// import 'moment';

// import RootType, {
//   Mutation,
//   rootDirectives,
// } from '../ORM';

// import Company from '../ORM/Company';
// import User from '../ORM/User';

// import { Contact } from '../ORM/Contact';
// import { Place } from '../ORM/Place';
// import { PlaceContact } from '../ORM/PlaceContact';

// import defaultQuery from 'modules/Site/components/ORM/query';

// import rootResolver from 'modules/Site/components/ORM/resolver';

// import {
//   buildSchema,
//   graphql,
//   execute,
//   parse,
//   GraphQLSchema,
//   GraphQLObjectType,
//   GraphQLInt,
//   GraphQLFloat,
//   GraphQLString,
//   GraphQLBoolean,
//   GraphQLList,
//   GraphQLNonNull,
//   GraphQLID,

//   introspectionQuery, 
//   buildClientSchema, 
//   printSchema,
// } from 'graphql';


import {
  buildExecutionContext,
  buildResolveInfo,
  getOperationRootType,
} from 'graphql/execution/execute';



// import injectTapEventPlugin from 'react-tap-event-plugin';
// injectTapEventPlugin();


export const createStores = function(){

  let stores = {
    MODXResourcesStore: new DataStore(new Dispatcher()),
    // RatingsStore: new DataStore(new Dispatcher()),
    // UsersStore: new DataStore(new Dispatcher()),
    // CommentsStore: new DataStore(new Dispatcher()),
    // ResourcesStore: new DataStore(new Dispatcher()),
    // TopicsStore: new DataStore(new Dispatcher()),
    // EditVersionsStore: new DataStore(new Dispatcher()),
    // BeersStore: new DataStore(new Dispatcher()),
    // PlacesBeersStore: new DataStore(new Dispatcher()),
    // AlbumsStore: new DataStore(new Dispatcher()),
    // EventsStore: new DataStore(new Dispatcher()),

    // // ContactsStore: new DataStore(new Dispatcher()),
    
    // PlacesStore: new DataStore(new Dispatcher()),
    // PlaceTypesStore: new DataStore(new Dispatcher()),
    // ServicesStore: new DataStore(new Dispatcher()),
    // PlaceContactsStore: new DataStore(new Dispatcher()),
    // PlacesServicesStore: new DataStore(new Dispatcher()),
    // ContactsServicesStore: new DataStore(new Dispatcher()),
    // ContactGradesStore: new DataStore(new Dispatcher()),
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

// const defaultProps = {
//   connector_url: '/assets/components/modxsite/connectors/connector.php',
// };


let classes;


var styleSheet = createStyleSheet('InteractiveLayout', (theme) => {



  var mobile = theme.breakpoints.down('md');
  var desktop = theme.breakpoints.up('md');
  var xs = theme.breakpoints.down('sm');
  var smMin = theme.breakpoints.up('sm');
  var lgMin = theme.breakpoints.up('lg');

  var css = {
  };

  return css;
});


export class MainApp extends Component{

  static childContextTypes = {
    appExports: PropTypes.object,
    // classes: PropTypes.object,
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

});

export class AppMain extends ReactCmsApp{

  static propTypes = propTypes;

  static defaultProps = defaultProps;
 

  static childContextTypes = childContextTypes;
  

  getChildContext() {

    let {
    } = this.props;

    let {
    } = this.state;

    let context = super.getChildContext() || {};

    Object.assign(context, {

    });

    return context;
  }

  constructor(props){

    super(props);

    let notifications_store = new DataStore(new Dispatcher());


    // const orm = new ORM();
    // const schema = this.getSchema();
  

    Object.assign(this.state, {
      notifications_store: notifications_store,
      inited: false,
      developMode: true,
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


class Renderer extends Component{


  static contextTypes = {
    styleManager: customPropTypes.muiRequired,
    appExports: PropTypes.object.isRequired,
  }


  constructor(props){

    super(props);

    this.state = {
    };

  }


  componentWillMount(){
  
    classes = this.context.styleManager.render(styleSheet);

    const {
      styleManager,
    } = this.context;

    let {
      appExports,
    } = this.context;

    if(appExports){

      appExports.theme = styleManager;

    }
  }


  render(){

    const {
      inited,
      children,
      authOpen,
      notifications_store,
    } = this.props;

    const {
      coinHiveInited,
    } = this.state;

    return <div
      className="MainApp"
    >
      
      {children}

      <Auth 
        open={authOpen}
      />

      <Informer
        store={notifications_store}
      />

    </div>;
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
