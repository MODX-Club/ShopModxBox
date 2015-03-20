require('es5-shim');
var $ = require('jQuery');
var Request = require("./utils/request");
var Env = require('./constants/Env');

Request = Object.create(Request).init({
  connectorsUrl: Env.siteConnectorsUrl,
  connector: Env.connector
});

window.ShopMODX = {
    Request: Request,
    Env: Env
};

$(function() {

  $('[data-smodx-behav="recount"]').remove();

  $("#loginLoginForm [type=submit]").on('click', function() {
    var form = $(this).parents('form:first');
    var data = [];

    form.serialize().split('&').map(function(node) {
      if (node.match('action')) return;
      this.push(node);
    }, data);

    Request.run('login', data.join('&'))
      .then(function(resp) {
        if (resp.success) {
          var url = window.location.href.replace(/\?.*/, '');
          url = url.replace(/#.*/, '');
          window.location.replace(url);
        }
      });

    return false;
  });


});

