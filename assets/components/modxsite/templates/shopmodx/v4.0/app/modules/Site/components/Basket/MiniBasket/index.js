import React, { Component } from 'react';

import PropTypes from 'prop-types';


import ShopModxMiniBasketView from 'shopmodx-react/components/Basket/View/MiniBasket';


import BasketIcon from 'material-ui-icons/ShoppingCart';

import {Link} from 'react-router';

import Decliner from 'react-decliner';

import NumberFormat from 'react-number-format';

let {
  ...contextTypes
} = ShopModxMiniBasketView.contextTypes;


Object.assign(contextTypes, {
  isDemo: PropTypes.bool.isRequired,
});


export default class MiniBasketView extends ShopModxMiniBasketView{

  static contextTypes = contextTypes;


  render(){

		const {
      order,
      isDemo,
    } = this.context;
    

    if(!isDemo){
      return super.render();
    }

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
							Товаров: {total} <Decliner 
								num={total}
								words={['товар', 'товара', 'товаров']}
							/>
						</span> : "Корзина пуста"}
					</div>

					<div>
						{positions > 0 ? <span>
							Позиций: {positions} <Decliner 
								num={positions}
								words={['позиция', 'позиции', 'позиций']}
							/>
						</span> : null}
					</div>
					
					{sum > 0 && <div>
						Сумма: <NumberFormat
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
