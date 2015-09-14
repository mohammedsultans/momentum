define(["app", "apps/footer/show/show_controller"], function(Ecommerce, ShowController){
  Ecommerce.module('FooterApp', function(Footer, Ecommerce, Backbone, Marionette, $, _){
    Footer.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "privacy" : "showPrivacy",
        "terms" : "showTerms"
      }
    });

    var API = {
      showFooter: function(){
        ShowController.showFooter();
      },

      showPrivacy: function(){
        Ecommerce.execute("set:active:header", "home");
        Ecommerce.navigate("privacy");
        ShowController.showPrivacy();
      },

      showTerms: function(){
        Ecommerce.execute("set:active:header", "home");
        Ecommerce.navigate("terms");
        ShowController.showTerms();
      }
    };

    Footer.on("start", function(){
      API.showFooter();
    });
  });

  return Ecommerce.FooterApp;
});