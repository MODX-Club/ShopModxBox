
import ShopModxProductView from 'shopmodx-react/components/Pages/Catalog/Products/Product/View';

import React, { Component } from 'react';

import PropTypes from 'prop-types';

import Typography from 'material-ui/Typography';
import Grid from 'material-ui/Grid';
import Button from 'material-ui/Button';

import NumberFormat from 'react-number-format';

let {
  ...contextTypes
} = ShopModxProductView.contextTypes;


Object.assign(contextTypes, {
  isDemo: PropTypes.bool.isRequired,
});


export default class ProductView extends ShopModxProductView{

  static contextTypes = contextTypes;


  render(){

    const {
      isDemo,
    } = this.context;


    if(!isDemo){
      return super.render();
    }


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
      style={{
        flexDirection: "row-reverse",
      }}
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
