
import React, { Component } from 'react';

import PropTypes from 'prop-types';

import MiniBasket from './View/MiniBasket';

export default class Basket extends Component{

	static propTypes = {
		MiniBasketView: PropTypes.func.isRequired,
	};


	static defaultProps = {
		MiniBasketView: MiniBasket,
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
			MiniBasketView,
		} = this.props;

		return <MiniBasketView 

		/>
	}
}
