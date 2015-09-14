define(["app"], function(Ecommerce){
  Ecommerce.module('CheckOutApp', function(CheckOutApp, Ecommerce, Backbone, Marionette, $, _){

    CheckOutApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "checkout" : "showCheckout"
      }
    });

    var API = {//deferred instantiation
      showCheckout: function(){
        require(["apps/check_out/show/show_controller"], function(ShowController){
          ShowController.showCheckOut();
          //Ecommerce.execute("set:active:header", "contact");
        });
      }
    };

    Ecommerce.on("checkout:show", function(){
      Ecommerce.navigate("checkout");
      API.showCheckout();
    });

    Ecommerce.addInitializer(function(){
      new CheckOutApp.Router({
        controller: API
      });
    });
  });

  return Ecommerce.CheckOutApp;
});
