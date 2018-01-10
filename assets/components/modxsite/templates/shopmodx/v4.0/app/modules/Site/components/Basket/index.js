

import React, { Component } from 'react';

import PropTypes from 'prop-types';

import ShopModxBasket from 'shopmodx-react/components/Basket';

import MiniBasket from './MiniBasket';


let {
	...contextTypes
} = ShopModxBasket.contextTypes || {};


Object.assign(contextTypes, {
});


let {
	...defaultPropsBasket
} = ShopModxBasket.defaultProps || {};


Object.assign(defaultPropsBasket, {
	MiniBasketView: MiniBasket,
});


export default class Basket extends ShopModxBasket{

	static contextTypes = contextTypes;

	static defaultProps = defaultPropsBasket;

}

