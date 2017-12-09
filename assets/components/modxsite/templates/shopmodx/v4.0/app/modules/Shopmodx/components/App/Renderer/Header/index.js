
import React, { Component } from 'react';

import PropTypes from 'prop-types';

import Grid from 'material-ui/Grid';

import {Link} from 'react-router';

import MainMenu from '../MainMenu';

import Basket from 'modules/Site/components/Basket';

export default class Header extends Component{

	static propTypes = {

	};
 
	
	static contextTypes = {

	};


	constructor(props){

		super(props);

		this.state = {

		};
	} 
	
	render(){

		return <Grid
      container
      gutter={0}
      className="MainApp--header"
    >

      <Grid
        item
      >
    
        <div 
          className="MainApp--header-logo"
        >

          <Link
            to="/"
            href="/"
            title="shopModx eCommerce engine">
          </Link>

        </div>
      
      </Grid>

      <Grid
        item
        xs={12}
        md
      >

      </Grid>

      <Grid
        item
      >
    
        <MainMenu 

        />

        <Basket />

      </Grid>


      
    </Grid>
	}
}
