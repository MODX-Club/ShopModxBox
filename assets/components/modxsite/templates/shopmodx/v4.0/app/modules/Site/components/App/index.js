
import './styles/styles.less';

import { connect } from 'react-redux';

import PropTypes from 'prop-types';

// import * as Shopmodx from 'shopmodx-react/components/App';
import * as Shopmodx from 'shopmodx-react/components/App';

import Renderer from './Renderer';

import Basket from '../Basket';

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
	isDemo: true,
});


let {
	...childContextTypes,
} = ShopModxApp.childContextTypes || {};

Object.assign(childContextTypes, {
	isDemo: PropTypes.bool,
	toggleIsDemo: PropTypes.func,
});


class AppMain extends ShopModxApp{

	static defaultProps = defaultProps;
	static childContextTypes = childContextTypes;

	constructor(props){

		super(props);

		const {
			isDemo,
		} = props;

		Object.assign(this.state, {
			developMode: false,
			isDemo,
		});

	}


  getChildContext() {

		let context = super.getChildContext();

    let {
      isDemo,
    } = this.state;

		Object.assign(context, {
			isDemo,
			toggleIsDemo: ::this.toggleIsDemo,
    });

    return context;
	}
	

	toggleIsDemo(){

		const {
			isDemo,
		} = this.state;

		this.setState({
			isDemo: !isDemo,
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
