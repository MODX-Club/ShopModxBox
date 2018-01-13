import React, {Component} from 'react';

import PropTypes from 'prop-types';

import ShopModxHeader from 'shopmodx-react/components/App/Renderer/Header';
import MainMenu from './MainMenu';

import Grid from 'material-ui/Grid';
import {Link} from 'react-router';

import Switch from 'material-ui/Switch';


let {
  ...contextTypes
} = ShopModxHeader.contextTypes;


Object.assign(contextTypes, {
  isDemo: PropTypes.bool.isRequired,
  toggleIsDemo: PropTypes.func.isRequired,
});


export default class Header extends ShopModxHeader{

  static contextTypes = contextTypes;
  
  
  toggleIsDemo(){

    const {
      toggleIsDemo,
    } = this.context;

    return toggleIsDemo();

  }


	render(){
    
    const {
      isDemo,
    } = this.context;

    const {
      Basket,
    } = this.context;

    const demoSwitcher = <div
      style={{
        textAlign: "center",
        margin: !isDemo ? "0 -15px" : undefined,
        background: !isDemo ? "rgba(0,0,0,0.1)" : undefined,
      }}
    >
      <h2
        style={{
          color: "#333",
          display: "inline-flex",
          flexDirection: "row",
          alignItems: "center",
          margin: 0,
        }}
      >
        <Switch 
          checked={isDemo}
          onChange={::this.toggleIsDemo}
        />
        Тестовый магазин
      </h2>
    </div>

    let header;

    if(isDemo){

      header = <Grid
        container
        gutter={0}
        className="MainApp--header"
        style={{
          marginBottom: 20,
        }}
      >


        <Grid
          item
        >
      
          <Link
            to="/"
            href="/"
            title="shopModx eCommerce engine"
          >

            <div 
              className="MainApp--header-logo"
            >


            </div>
            
          </Link>
        
        </Grid>

        <Grid
          item
        >

          {demoSwitcher}

        </Grid>
  
    

        <Grid
          item
          xs
        >
      
          <MainMenu 

          />

          <div
            style={{
              display: "flex",
              justifyContent: "flex-end",
            }}
          >
            <Basket />
          </div>

        </Grid>


        
      </Grid>

    }
    else{

      header = <div>
         
        {demoSwitcher}

        {super.render()}

      </div>

    }


		return header;
	}

}

