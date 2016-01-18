define(["app", "apps/hr/show/show_controller"], function(System, showController){
  System.module('HRApp', function(HRApp, System, Backbone, Marionette, $, _){

    HRApp.Router = Marionette.AppRouter.extend({

      //controller: showController,

      appRoutes: {
        "employees" : "showEmployees",
        "addEmployee" : "addEmployee",
        "postAllowance" : "postAllowance",
        "postOvertime" : "postOvertime",
        "salaryAdvance" : "salaryAdvance",
        "payroll" : "payroll",
        "paySalaries" : "paySalaries",
        "exEmployees" : "exEmployees",
        "employeeTx" : "employeeTx"
      }
    });

    System.addInitializer(function(){
      new HRApp.Router({
        controller: showController
      });
    });

    System.on("employees:show", function(){
      System.navigate("employees");
      showController.showemployees();
    });
    
    
  });

  return System.HRApp;
});

