define(["app"], function(Ecommerce){
  Ecommerce.module('ContactApp', function(ContactApp, Ecommerce, Backbone, Marionette, $, _){

    ContactApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "contact" : "showContact"
      }
    });

    var API = {//deferred instantiation
      showContact: function(){
        require(["apps/contact/show/show_controller"], function(ShowController){
          ShowController.showContact();
          //Ecommerce.execute("set:active:header", "contact");
        });
      }
    };

    Ecommerce.on("contact:show", function(){
      Ecommerce.navigate("contact");
      API.showContact();
    });

    Ecommerce.addInitializer(function(){
      new ContactApp.Router({
        controller: API
      });
    });
  });

  return Ecommerce.ContactApp;
});
