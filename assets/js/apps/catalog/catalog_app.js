define(["app"], function(Ecommerce){
  Ecommerce.module('CatalogApp', function(CatalogApp, Ecommerce, Backbone, Marionette, $, _){

    CatalogApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "product" : "showProduct",
        "catalog" : "showCatalog",
        "catalog/:collection" : "showCatalog"
      }
    });

    /*var catalogLayout = Backbone.Marionette.Layout.extend({
      template: catalogTpl,

      regions: {
        sidebarRegion: "#catsidebar",
        itemsRegion: "#catitems"
      }

    });

    var layout = new catalogLayout();*/

    var API = {//deferred instantiation
      showCatalog: function(collection){
        require(["apps/catalog/show/show_controller"], function(ShowController){
          //ShowController.showCatalog();
          if (collection == 'gents') {
            ShowController.showCatalog(1, 'Gentlemens');
          }else if(collection == 'ladies'){
            ShowController.showCatalog(2, 'Ladies');
          }else if(collection == 'kids'){
            ShowController.showCatalog(3, 'Kids');
          }else{
            ShowController.showCatalog();
          }
          //Ecommerce.execute("set:active:header", "Catalog");
        });
      },

      showProduct: function(model){
        require(["apps/catalog/show/show_controller"], function(ShowController){
          ShowController.showProduct(model);
          //Ecommerce.execute("set:active:header", "Catalog");
        });
      }
    };

    Ecommerce.on("catalog:show", function(){
      Ecommerce.navigate("catalog");
      API.showCatalog();
    });

    Ecommerce.on("product:show", function(){
      Ecommerce.navigate("product");
      API.showCatalog();
    });

    Ecommerce.on("product:view", function(model){
      Ecommerce.navigate("product/"+model.get('account_no'));
      API.showProduct(model);
    });

    Ecommerce.addInitializer(function(){
      new CatalogApp.Router({
        controller: API
      });
    });
  });

  return Ecommerce.CatalogApp;
});
