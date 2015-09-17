define(["app", "apps/menu/show/show_controller", "tpl!apps/templates/menu.tpl"], function(System, showController, menuTpl){
  System.module('MenuApp', function(MenuApp, System, Backbone, Marionette, $, _){

    MenuApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "menu" : "showMenu",
        "start" : "showStart"
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
      showMenu: function(data){
        //System.contentRegion.show();
        //System.menuRegion.close();
        showController.showMenu(data);
      },

      showStart: function(){
        $('ul#presentation > li:first-child > ul > li > a').first().get(0).click();
      }
    };

    System.on("menu:show", function(data){
      API.showMenu(data);
    });

    System.addInitializer(function(){
      new MenuApp.Router({
        controller: API
      });
    });
  });

  return System.MenuApp;
});

