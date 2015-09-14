define(["app", "apps/dash/show/show_controller"], function(System, showController){
  System.module('DashApp', function(DashApp, System, Backbone, Marionette, $, _){

    DashApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "dash" : "showDash"
      }
    });

    /*var MenuLayout = Backbone.Marionette.Layout.extend({
      template: MenuTpl,

      regions: {
        topRegion: ".slider",
        latestRegion: ".carosel"
      }

    });

    var layout = new MenuLayout();*/

    var API = {
      showDash: function(){
        //System.contentRegion.show();
        showController.showDash();
        //System.execute("set:active:header", "Menu");
      }
    };

    System.on("dash:show", function(){
      System.navigate("dash");
      API.showDash();
    });

    System.addInitializer(function(){
      new DashApp.Router({
        controller: API
      });
    });
  });

  return System.DashApp;
});

