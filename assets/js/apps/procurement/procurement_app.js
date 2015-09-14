define(["app", "apps/procurement/show/show_controller"], function(System, showController){
  System.module('ProcurementApp', function(ProcurementApp, System, Backbone, Marionette, $, _){

    ProcurementApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "suppliers" : "showSuppliers",
        "addSupplier" : "addSupplier",
        "receiveGoods" : "enquiries",
        "paySupplier" : "pendingQueries",
        "supplierTx" : "supplierTx"
      }
    });

    var API = {
      showSuppliers: function(){
        //System.contentRegion.show();
        showController.showSuppliers();
        //System.execute("set:active:header", "Menu");
      },

      addSupplier: function(a){
        //System.contentRegion.show();
        showController.addSupplier(a);
        //System.execute("set:active:header", "Menu");
      },

      supplierTx: function(){
        //System.contentRegion.show();
        showController.showSuppliers();
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

    System.on("suppliers:show", function(){
      System.navigate("suppliers");
      API.showClients();
    });
    
    System.addInitializer(function(){
      new ProcurementApp.Router({
        controller: API
      });
    });
  });

  return System.ProcurementApp;
});

