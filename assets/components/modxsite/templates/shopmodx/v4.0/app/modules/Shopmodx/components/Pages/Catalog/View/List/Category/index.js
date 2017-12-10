
/*
	Отдельная карточка категории для списка категорий
*/

import React, { Component } from 'react';

import PropTypes from 'prop-types';

import Grid from 'material-ui/Grid';
import Typography from 'material-ui/Typography';
import Paper from 'material-ui/Paper';

import {Link} from 'react-router';

export default class MyComponent extends Component{

	static propTypes = {
		item: PropTypes.object.isRequired,
	};

	
	static contextTypes = {

	};


	constructor(props){

		super(props);

		this.state = {

		};
	}


	componentWillMount(){
	
	}


	componentDidMount(){

	}

	
	render(){

		const {
			item,
			...other
		} = this.props;

		if(!item){
			return null;
		}

		const {
			id,
			pagetitle,
			uri,
		} = item;

		const link = `/${uri}`;

		return <Grid
			item
			xs={12}
			md={4}
			lg={3}
			{...other}
		>
			
			<Link
				to={link}
				href={link}
				title={pagetitle}
			>
				{pagetitle}
			</Link>

		</Grid>
	}
}
