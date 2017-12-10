
import React, { Component } from 'react';

import PropTypes from 'prop-types';

import Grid from 'material-ui/Grid';
import Typography from 'material-ui/Typography';
import Paper from 'material-ui/Paper';

import {Link} from 'react-router';

import ProductCart from 'modules/Shopmodx/components/Pages/Catalog/View/List/Product';

export default class MainPageView extends Component{

	static propTypes = {
		ProductCart: PropTypes.func.isRequired,
	};

	static defaultProps = {
		ProductCart,
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
			modxResource,
			products,
			categories,
			ProductCart,
		} = this.props;


		if(!modxResource){
			return null;
		}

		const {
			id,
			pagetitle,
			uri,
			content,
		} = modxResource;

		let output = [];


		if(products && products.length){

			let productsContent = products.map(n => {

				const {
					id,
				} = n;

				return <ProductCart 
					key={id}
					item={n}
				/>

			});

			if(productsContent && productsContent.length){

				output.push(<div
					key="products"
				>
						
					<Grid
						container
					>
						
						{productsContent}

					</Grid>
					 

				</div>);

			}
		}


		return <div>
			
			<Typography
				type="title"
				style={{
					marginBottom: 30,
				}}
			>
				{pagetitle}
			</Typography>
				 
			{content && <div dangerouslySetInnerHTML={{__html: content}} /> || null}

			{output}

		</div>
	}
}
