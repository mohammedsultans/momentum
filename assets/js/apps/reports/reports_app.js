define(["app", "apps/reports/show/show_controller"], function(System, showController){
  System.module('ReportsApp', function(ReportsApp, System, Backbone, Marionette, $, _){

    ReportsApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "financialReports" : "financialReports",
        "clientReports" : "clientReports",
        "procurementReports" : "procurementReports",
        "projectReports" : "projectReports",
        "hrReports" : "hrReports",
        "systemReports" : "systemReports"
      }
    });

    var API = {
      financialReports: function(){
        //System.contentRegion.show();
        showController.financialReports();
        //System.execute("set:active:header", "Menu");
      },

      clientReports: function(a){
        //System.contentRegion.show();
        showController.clientReports(a);
        //System.execute("set:active:header", "Menu");
      },

      procurementReports: function(a){
        //System.contentRegion.show();
        showController.procurementReports(a);
        //System.execute("set:active:header", "Menu");
      },

      projectReports: function(a){
        //System.contentRegion.show();
        showController.projectReports(a);
        //System.execute("set:active:header", "Menu");
      },

      hrReports: function(a){
        //System.contentRegion.show();
        showController.hrReports(a);
        //System.execute("set:active:header", "Menu");
      },

      systemReports: function(a){
        //System.contentRegion.show();
        showController.systemReports(a);
        //System.execute("set:active:header", "Menu");
      }

    };

    System.on("employees:show", function(){
      System.navigate("employees");
      API.showemployees();
    });
    
    System.addInitializer(function(){
      new ReportsApp.Router({
        controller: API
      });
    });
  });

  return System.ReportsApp;
});

