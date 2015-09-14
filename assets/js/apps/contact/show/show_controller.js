define(["app", "apps/contact/show/show_view"], function(Ecommerce, View){
  Ecommerce.module('ContactApp.Show', function(Show, Ecommerce, Backbone, Marionette, $, _){
    Show.Controller = {
      showContact: function(){
        var view = new View.Contact();
        Ecommerce.contentRegion.show(view);
        view.on('csubmit', function(data) {
            $.post('/ecomadmin/service/contact/index.php', data, function(userData) {
                if (userData == 1) {
                	view.triggerMethod("form:clear");
                };
            });
        })
      }
    };
  });

  return Ecommerce.ContactApp.Show.Controller;
});
