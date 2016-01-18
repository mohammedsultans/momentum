define(["app", "apps/procurement/show/show_controller"], function(System, showController){
  System.module('ProcurementApp', function(ProcurementApp, System, Backbone, Marionette, $, _){

    ProcurementApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "suppliers" : "showSuppliers",
        "addSupplier" : "addSupplier",
        "receiveGoods" : "receiveGoods",
        "receiveOrder" : "receiveOrder",
        "returnGoods" : "returnGoods",
        "paySupplierGRN" : "paySupplierGRN",
        "paySupplier" : "paySupplier",
        "purchaseOrder" : "purchaseOrder",
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

      receiveGoods: function(a){
        //System.contentRegion.show();
        showController.receiveGoods(a);
        //System.execute("set:active:header", "Menu");
      },

      receiveOrder: function(){
        //System.contentRegion.show();
        showController.receiveOrder();
        //System.execute("set:active:header", "Menu");
      },

      returnGoods: function(a){
        //System.contentRegion.show();
        showController.returnGoods(a);
        //System.execute("set:active:header", "Menu");
      },

      paySupplierGRN: function(){
        //System.contentRegion.show();
        showController.paySupplierGRN();
        //System.execute("set:active:header", "Menu");
      },

      paySupplier: function(){
        //System.contentRegion.show();
        showController.paySupplier();
        //System.execute("set:active:header", "Menu");
      },

      purchaseOrder: function(){
        //System.contentRegion.show();
        showController.purchaseOrder();
        //System.execute("set:active:header", "Menu");
      },

      supplierTx: function(){
        //System.contentRegion.show();
        showController.supplierTx();
        //System.execute("set:active:header", "Menu");
      },
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

