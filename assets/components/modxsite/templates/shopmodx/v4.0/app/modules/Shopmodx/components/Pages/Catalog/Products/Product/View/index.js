
import React, { Component } from 'react';

import PropTypes from 'prop-types';

import Typography from 'material-ui/Typography';
import Grid from 'material-ui/Grid';

import NumberFormat from 'react-number-format';

export default class ProductView extends Component{

	static propTypes = {
		modxResource: PropTypes.object.isRequired,
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
			modxResource: item,
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
				<Typography>
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

			</Grid>


			 
		</Grid>
	}
}
