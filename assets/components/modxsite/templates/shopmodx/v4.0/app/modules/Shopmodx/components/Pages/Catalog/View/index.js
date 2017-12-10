
import React, { Component } from 'react';

import PropTypes from 'prop-types';

import Grid from 'material-ui/Grid';
import Typography from 'material-ui/Typography';
import Paper from 'material-ui/Paper';

import {Link} from 'react-router';

import ProductCart from 'modules/Shopmodx/components/Pages/Catalog/View/List/Product';
import CategoryCart from 'modules/Shopmodx/components/Pages/Catalog/View/List/Category';

export default class CatalogView extends Component{

	static propTypes = {
		ProductCart: PropTypes.func.isRequired,
		CategoryCart: PropTypes.func.isRequired,
	};

	static defaultProps = {
		ProductCart,
		CategoryCart,
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
			productsList,
			categories,
			ProductCart,
			CategoryCart,
		} = this.props;


		if(!modxResource){
			return null;
		}

		const {
			id,
			pagetitle,
			uri,
		} = modxResource;

		let content = [];


		if(productsList){

			const {
				limit,
				total,
				page,
				object,
			} = productsList;

			let productsContent = object && object.map(n => {

				const {
					id,
				} = n;

				return <ProductCart 
					key={id}
					item={n}
				/>

			});

			if(productsContent && productsContent.length){

				content.push(<div
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


		if(categories && categories.length){

			content.push(<Grid
				key="categories"
				container
				gutter={0}
				style={{
					marginTop: 20,
					marginBottom: 20,
				}}
			>
				
				{categories.map(n => {

					const {
						id,
					} = n;

					return <CategoryCart 
						key={id}
						item={n}
					/>

				})}

			</Grid>);

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

			{content}

		</div>
	}
}
