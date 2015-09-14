define(["app", "apps/catalog/show/show_view"], function(ECommerce, View){
  ECommerce.module('CatalogApp.Show', function(Show, ECommerce, Backbone, Marionette, $, _){
    Show.Controller = {
      showCatalog: function(maincat, title){
        require(["apps/entities/inventory"], function(){
          $.when(ECommerce.request("product:catalog", 1, maincat, 15)).done(function(response){
            //alert(JSON.stringify(response.length));
            //total number of pages and search parameters are delivered by model which includes the collection
            response.set('title', title);
            //alert(response.get('maincat'));
            var view = new View.Catalog({ model: response });
            ECommerce.contentRegion.show(view);

            view.on('page', function(page, params) {
              //params === filter parameters
              $.when(ECommerce.request("product:catalog", page, params)).done(function(response){
                response.set('title', title);
                view.model = response;
                view.render();
                view.triggerMethod("form:reload");
              });
            });

            view.on('filter', function(params) {
              //params['operation'] = 'filter';
              $.when(ECommerce.request("product:filter", params)).done(function(response){
                response.set('title', title);
                view.model = response;
                view.render();
                view.triggerMethod("form:reload");
              });
            });

            view.on('itemview:addtocart', function(arg, model) {
              alert(JSON.stringify(model));
              //Ecommerce.trigger("cart:add", model);
            });

            view.on('itemview:viewproduct', function(arg, model) {
              ECommerce.trigger("product:view", model);
            });
          });
        });
        //var view = new View.Catalog();
        //ECommerce.contentRegion.show(view);
      },

      showProduct: function(model){
        //alert(JSON.stringify(model));

        //Fetch related products i.e around 6 - 15 products that are usually bought together or in the same categories
        //bought together is computed at order stage
        //model should contain collection of 5 or less latest reviews
        //
        var view = new View.Product({model: model});
        ECommerce.contentRegion.show(view);
        
        view.on('addtocart', function(product) {
          $.when(ECommerce.request("cart:add", product)).done(function(response){
            //view.model = response;
            //view.render();
          });
        });
      }
    };
  });

  return ECommerce.CatalogApp.Show.Controller;
});
