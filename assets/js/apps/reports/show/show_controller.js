define(["app", "apps/reports/show/show_view"], function(System, View){
  System.module('ReportsApp.Show', function(Show, System, Backbone, Marionette, $, _){
    Show.Controller = {

      financialReports: function(a){ 
        var view = new View.FinancialReports();        
        System.contentRegion.show(view);
      },

      clientReports: function(a){ 
        var view = new View.ClientReports();        
        System.contentRegion.show(view);
      },

      procurementReports: function(a){ 
        var view = new View.ProcurementReports();        
        System.contentRegion.show(view);
      },

      projectReports: function(a){ 
        var view = new View.ProjectReports();        
        System.contentRegion.show(view);
      },

      hrReports: function(a){ 
        var view = new View.HRReports();        
        System.contentRegion.show(view);
      },

      systemReports: function(a){ 
        var view = new View.SystemReports();        
        System.contentRegion.show(view);
      }     
      
    };
  });

  return System.ReportsApp.Show.Controller;
});
