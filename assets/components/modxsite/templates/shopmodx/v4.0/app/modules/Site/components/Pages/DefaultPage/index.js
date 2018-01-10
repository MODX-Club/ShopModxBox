
import PropTypes from 'prop-types';

import ShopModxDefaultPage from 'shopmodx-react/components/Pages/DefaultPage';

import ProductView from '../Catalog/Products/Product/View';

let {
  ...defaultProps
} = ShopModxDefaultPage.defaultProps;


Object.assign(defaultProps, {
  ProductView,
});


export default class DefaultPage extends ShopModxDefaultPage{

  static defaultProps = defaultProps;

}