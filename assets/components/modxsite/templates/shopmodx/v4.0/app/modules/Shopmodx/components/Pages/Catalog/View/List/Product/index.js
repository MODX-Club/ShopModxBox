
/*
	Отдельная карточка товара для списка товаров
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
			imageFormats,
			uri,
		} = item;


		const {
			small: thumb,
		} = imageFormats || {};

		const link = `/${uri}`;

		return <Grid
			item
			xs={12}
			md={4}
			lg={3}
			{...other}
		>
			<Paper
				style={{
					padding: 10,
					height: "100%",
				}}
			>
			
				<Link
					to={link}
					href={link}
					title={pagetitle}
				>
					
					<Typography
						style={{
							marginBottom: 20,
						}}
					>
						
						{pagetitle}

					</Typography>

					<img 
						src={thumb}
						style={{
							width: "100%",
						}}
					/>

				</Link>
				
			</Paper>
		</Grid>
	}
}
