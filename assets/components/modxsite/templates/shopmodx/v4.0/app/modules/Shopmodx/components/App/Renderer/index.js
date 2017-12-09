import './styles/styles.less';

import React, {Component} from 'react';

import PropTypes from 'prop-types';

import customPropTypes from 'material-ui/utils/customPropTypes';
import { createStyleSheet } from 'jss-theme-reactor';

import Informer from 'structor-templates/components/Informer';

import Auth from 'react-cms/src/app/components/Auth';

import Grid from 'material-ui/Grid';

import {Link} from 'react-router';

import Header from './Header';

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

export default class Renderer extends Component{


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
    } = this.state;

    return <div
      className="MainApp"
    >

      <Header />
      
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
