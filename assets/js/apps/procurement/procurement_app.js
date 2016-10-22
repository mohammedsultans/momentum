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
        "supplierTx" : "supplierTx",
        "viewItems" : "viewItems",
        "createStock" : "createStock",
        "createService" : "createService",
        "createAsset" : "createAsset",
        "itemCategories" : "itemCategories",
      }
    });

    System.on("suppliers:show", function(){
      System.navigate("suppliers");
      showController.showClients();
    });
    
    System.addInitializer(function(){
      new ProcurementApp.Router({
        controller: showController
      });
    });
  });

  return System.ProcurementApp;
});

