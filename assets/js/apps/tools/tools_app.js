define(["app", "apps/tools/show/show_controller"], function(System, showController){
  System.module('ToolsApp', function(ToolsApp, System, Backbone, Marionette, $, _){

    ToolsApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "users" : "userManager",
        "roles" : "userRoles",
        "password" : "changePassword",
        "licencing" : "userManager",
        "sysConfig" : "userRoles",
        "sysMonitor" : "changePassword",
        "sysBackup" : "changePassword"
      }
    });

    var API = {
      userManager: function(){
        //System.contentRegion.show();
        showController.userManager();
        //System.execute("set:active:header", "Menu");
      },

      userRoles: function(){
        //System.contentRegion.show();
        showController.userRoles();
        //System.execute("set:active:header", "Menu");
      },

      changePassword: function(){
        //System.contentRegion.show();
        showController.changePassword();
        //System.execute("set:active:header", "Menu");
      }
    };

    System.addInitializer(function(){
      new ToolsApp.Router({
        controller: API
      });
    });
  });

  return System.ToolsApp;
});

