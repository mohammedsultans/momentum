define(["app", "apps/hr/show/show_controller"], function(System, showController){
  System.module('HRApp', function(HRApp, System, Backbone, Marionette, $, _){

    HRApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "employees" : "showEmployees",
        "addEmployee" : "addEmployee",
        "enquiries" : "enquiries",
        "pending" : "pendingQueries"
      }
    });

    var API = {
      showEmployees: function(){
        //System.contentRegion.show();
        showController.showEmployees();
        //System.execute("set:active:header", "Menu");
      },

      addEmployee: function(a){
        //System.contentRegion.show();
        showController.addEmployee(a);
        //System.execute("set:active:header", "Menu");
      },

      enquiries: function(a){
        //System.contentRegion.show();
        showController.enquiries(a);
        //System.execute("set:active:header", "Menu");
      },

      pendingQueries: function(a){
        //System.contentRegion.show();
        showController.pendingQueries(a);
        //System.execute("set:active:header", "Menu");
      }
    };

    System.on("employees:show", function(){
      System.navigate("employees");
      API.showemployees();
    });
    
    System.addInitializer(function(){
      new HRApp.Router({
        controller: API
      });
    });
  });

  return System.HRApp;
});

