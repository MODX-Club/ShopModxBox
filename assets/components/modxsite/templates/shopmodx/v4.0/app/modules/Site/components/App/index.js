
import './styles/styles.less';

import { connect } from 'react-redux';

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
