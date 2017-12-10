
import React, { Component } from 'react';

import PropTypes from 'prop-types';

import BasketIcon from 'material-ui-icons/ShoppingBasket';

import {Link} from 'react-router';

import Decliner from 'react-decliner';

import NumberFormat from 'react-number-format';

export default class ShopModxMiniBasketView extends Component{

	static propTypes = {

	};
	
	static contextTypes = {
		order: PropTypes.object.isRequired,
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
			order,
		} = this.context;

		const {
			id,
			sum,
			original_sum,
			total,
			positions,
		} = order || {};

		return <Link
			to="/order/"
			href="/order/"
			rel="nofollow"
			title="Корзина"
			style={{
				display: "flex",
		    flexDirection: "row-reverse",
		    alignItems: "center",
			}}
		>

			<div
				style={{
					paddingLeft: 10,
				}}
			>
				
					<div>
						{total > 0 ? <span>
							{total} <Decliner 
								num={total}
								words={['товар', 'товара', 'товаров']}
							/>
						</span> : "Корзина пуста"}
					</div>

					<div>
						{positions > 0 ? <span>
							{positions} <Decliner 
								num={positions}
								words={['позиция', 'позиции', 'позиций']}
							/>
						</span> : null}
					</div>
					
					{sum > 0 && <div>
						<NumberFormat
							value={sum}
							thousandSeparator=" "
							displayType="text"
						/> <Decliner 
							num={sum}
							words={['рубль', 'рубля', 'рублей']}
						/>
					</div> || null }

			</div>
			
			<BasketIcon 
				color={total > 0 ? 'green' : undefined}
			/> 


		</Link>
	}
}
