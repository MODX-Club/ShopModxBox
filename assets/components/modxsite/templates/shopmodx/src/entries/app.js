require('../common.js');
var Env = require('../constants/Env');
var _Good = require('../components/Good');
var _Cart = require('../components/ExtendedCart');
var _Order = require('../components/Order');

document.addEventListener("DOMContentLoaded", function() {

  new _Good({
    wrapper: '[data-smodx-productcls="listproduct"]',
    ruler: '[type="submit"]'
  });

  new _Cart({
    wrapper: '[data-smodx-data="cost"]',
    declensions: {
      one: 'товар',
      two: 'товарa',
      many: 'товаров'
    }
  });

  new _Order({
    wrapper: '[data-smodx-basket="order"]',
    item: '[data-smodx-item="good"]',
    idAttr: 'data-smodx-item-id',
    behaviour: {
      delete: 'goodDel',
      change: 'goodNum',
    }
  });

});
