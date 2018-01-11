
import './styles/styles.less';

import { connect } from 'react-redux';

import PropTypes from 'prop-types';

// import * as Shopmodx from 'shopmodx-react/components/App';
import * as Shopmodx from 'shopmodx-react/components/App';

import Renderer from './Renderer';

import Basket from '../Basket';


import defaultQuery from '../ORM/query';
import rootResolver from '../ORM/resolver';

const {
	mapDispatchToProps,
	mapStateToProps,
	AppMain: ShopModxApp,
	...other
} = Shopmodx;



let {
	...defaultProps,
} = ShopModxApp.defaultProps || {};

Object.assign(defaultProps, {
	Renderer,
	Basket,
	defaultQuery,
	rootResolver,
});


class AppMain extends ShopModxApp{

	static defaultProps = defaultProps;

	constructor(props){

		super(props);

		Object.assign(this.state, {
      developMode: false,
		});

	}
  

  componentWillMount(){

    super.componentWillMount && super.componentWillMount();

    return;
  }


  async componentDidMount(){

  	super.componentDidMount && super.componentDidMount();

  }


}

module.exports = Object.assign(Shopmodx, {
	AppMain,
	default: connect(mapStateToProps, mapDispatchToProps)(AppMain),
});
