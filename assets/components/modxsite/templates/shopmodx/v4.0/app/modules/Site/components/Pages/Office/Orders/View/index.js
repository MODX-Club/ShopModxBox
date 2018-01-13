
import React, {Component} from 'react';

import PropTypes from 'prop-types';

import ShopmodxOrdersView from 'shopmodx-react/components/Pages/Office/Orders/View';


let {
  ...contextTypes
} = ShopmodxOrdersView.contextTypes;


Object.assign(contextTypes, {
  isDemo: PropTypes.bool.isRequired,
});


export default class OrdersView extends ShopmodxOrdersView{


  static contextTypes = contextTypes;


  renderStatuses(){

    const statuses = super.renderStatuses();
    
    const {
      isDemo,
    } = this.context;

    if(!isDemo){
      return statuses;
    }

    return <div
      style={{
        border: "2px dotted #ddd",
        padding: 5,
        margin: "10px 0",
      }}
    >
      
      {statuses}

      <p>
        <b>Полезно: </b> код этого компонента можно посмотреть и потестировать <a 
          href="https://stackblitz.com/edit/shopmodx-orders-statuses"
          target="_blank"
        >здесь</a>.
      </p>

    </div>

  }

}