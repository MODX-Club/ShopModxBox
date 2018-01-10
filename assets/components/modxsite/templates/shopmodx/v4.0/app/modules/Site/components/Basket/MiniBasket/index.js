import React, { Component } from 'react';

import PropTypes from 'prop-types';


import ShopModxMiniBasketView from 'shopmodx-react/components/Basket/View/MiniBasket';


let {
  ...contextTypes
} = ShopModxMiniBasketView.contextTypes;


Object.assign(contextTypes, {
  isDemo: PropTypes.bool.isRequired,
});


export default class MiniBasketView extends ShopModxMiniBasketView{

  static contextTypes = contextTypes;

}
