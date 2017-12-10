
import React, { Component } from 'react';

import PropTypes from 'prop-types';

import Typography from 'material-ui/Typography';
import Grid from 'material-ui/Grid';
import Button from 'material-ui/Button';

import NumberFormat from 'react-number-format';

export default class ProductView extends Component{

	static propTypes = {
		modxResource: PropTypes.object.isRequired,
	};
	
	static contextTypes = {
		addToBasket: PropTypes.func.isRequired,
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


	// Добавление товара в корзину
	async addToBasket(event){

		const {
			addToBasket,
		} = this.context;

		const {
			modxResource: item
		} = this.props;

		await addToBasket(item);

		this.forceUpdate();

	}

	
	render(){

		const {
			modxResource: item,
			...other
		} = this.props;

		if(!item){
			return null;
		}

		const {
			id,
			pagetitle,
			longtitle,
			image,
			imageFormats,
			price,
			price_old,
			content,
		} = item;

		const {
			thumb,
		} = imageFormats || {};

		return <Grid
			container
		>
			
			{thumb && <Grid
				item
				xs={12}
				sm={4}
				md={3}
			>
				
				<img 
					src={thumb}
					style={{
						width: "100%",
					}}
				/>

			</Grid> || null}
			
			<Grid
				item
				xs
			>
				<Typography
					type="title"
				>
					{pagetitle}
				</Typography>
				
				<table>
					<tbody>

						{price ?
							<tr>
								<th>
									Цена
								</th>
								<td>
									{price_old && <small>
										<s>
											<NumberFormat
												value={price_old}
												thousandSeparator=" "
												displayType="text"
											/>
										</s>
									</small> || null} <NumberFormat
										value={price}
										thousandSeparator=" "
										displayType="text"
									/> руб.
								</td>
							</tr>
							:
							null
						}

					</tbody>
				</table>

				<Button
					raised
					onClick={::this.addToBasket}
				>
					Добавить в корзину
				</Button>

				{content && <div dangerouslySetInnerHTML={{__html: content}} /> || null}

			</Grid>


			 
		</Grid>
	}
}
