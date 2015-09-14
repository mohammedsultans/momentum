define(["app", "apps/check_out/show/show_view"], function(Ecommerce, View){
  Ecommerce.module('CheckOutApp.Show', function(Show, Ecommerce, Backbone, Marionette, $, _){
    Show.Controller = {
      showCheckOut: function(){
        var view = new View.CheckOut();
        Ecommerce.contentRegion.show(view);
        view.on('csubmit', function(data) {
            $.post('/ecomadmin/service/shop/index.php', data, function(userData) {
                if (userData == 1) {
                	view.triggerMethod("form:clear");
                };
            });
        })
      }
    };
  });

  return Ecommerce.CheckOutApp.Show.Controller;
});
