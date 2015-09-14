define(["app"], function(ECommerce){
  ECommerce.module('Entities', function(Entities, ECommerce, Backbone, Marionette, $, _){

    Backbone.emulateJSON = true;

    Entities.Model = Backbone.Model.extend({
      urlRoot: "ecomadmin/service/inventory",
    });

    Entities.Collection = Backbone.Collection.extend({
      url: "/ecomadmin/service/inventory",
      model: Entities.Model
    });

    //Entities.configureStorage(Entities.Model);

    //Entities.configureStorage(Entities.Collection);

    var initializeCollection = function(val){
      var collection = new Entities.Collection(val);
      return collection.models;
    };

    var blogs, currentBlog;

    var API = {
      createProduct: function(data){
        var product = new Entities.Model();
        var defer = $.Deferred();
        
        $.when(

          $.post("/ecomadmin/service/inventory/index.php", data, function(val){
            //var a = JSON.parse(val);
            //alert(JSON.stringify(a['features']));
            product.set(JSON.parse(val));
                
          })

        ).done(function() {
          defer.resolve(product);
        });
        return defer.promise();
      }, 

      getProduct: function(id){
        var model;
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/service/inventory/?prodid="+id, function(val){
            var model = Entities.Model(JSON.parse(val));    
          })

        ).done(function() {
          defer.resolve(model);
        });
        return defer.promise();
      },

      getProductsCatalog: function(page, maincat, qty){
        var model = new Entities.Model();
        var collection;
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/service/inventory/?stockInventory&page="+page+"&collection="+maincat+"&qty="+qty, function(val){
            collection = new Entities.Collection(JSON.parse(val));
          }),

          $.get("/ecomadmin/service/inventory/?categories", function(val){
            model.set('categories', new Entities.Collection(JSON.parse(val)));
          })

        ).done(function() {
          var data = {};
          data['page'] = page;
          data['qty'] = qty;
          data['name'] = null;
          data['sort'] = null;
          data['cats'] = [];
          data['maincat'] = maincat;
          data['max'] = 100000;
          data['min'] = 1;
          data['resultcount'] = collection.length;//this should not be equal to qty but the whole results

          model.set(data);
          model.set('products', collection);
          defer.resolve(model);
        });
        return defer.promise();
      },

      getFilteredCatalog: function(params){
        var model = new Entities.Model();
        var collection;
        var defer = $.Deferred();
        var name = params['name'];
        var page = params['page'];
        var qty = params['qty'];
        var sort = params['sort'];
        var cats = params['cats'];
        var maincat = params['maincat'];
        var max = params['max'];
        var min = params['min'];

        $.when(

          $.get("/ecomadmin/service/inventory/?filterCatalog&name="+name+"&page="+page+"&qty="+qty+"&sort="+sort+"&cats="+cats+"&collection="+maincat+"&max="+max+"&min="+min, function(val){
            collection = new Entities.Collection(JSON.parse(val));  
          }),

          $.get("/ecomadmin/service/inventory/?categories", function(val){
            model.set('categories', new Entities.Collection(JSON.parse(val)));
          })

        ).done(function() {
          params['resultcount'] = collection.length;

          model.set(params);
          model.set('products', collection);
          defer.resolve(model);
        });
        return defer.promise();
      },

      getFeaturedProducts: function(){
        var collection;
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/service/inventory/?products&featured", function(val){
            collection = new Entities.Collection(JSON.parse(val))       
          })

        ).done(function() {
          defer.resolve(collection);
        });
        return defer.promise();
      },

      getLatestProducts: function(){
        var collection;
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/service/inventory/?products&latest", function(val){
            collection = new Entities.Collection(JSON.parse(val))       
          })

        ).done(function() {
          defer.resolve(collection);
        });
        return defer.promise();
      },

      searchProducts: function(name){
        var subscribers = new Entities.Collection();
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/service/admin/?searchSubscriber&name="+name, function(val){
            var models = initializeCollection(JSON.parse(val));
            subscribers.reset(models);       
          })

        ).done(function() {
          defer.resolve(subscribers);
        });
        return defer.promise();
        
      },

      getAccountDetails: function(){
        var account;
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/service/admin/?adminDetails", function(val){
            account = new Entities.Model(JSON.parse(val));      
          })

        ).done(function() {
          defer.resolve(account);
        });
        return defer.promise();
        
      },

      getDashboardEntities: function(){
        var dash;
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/service/admin/?dashDetails", function(val){
            dash = new Entities.Model(JSON.parse(val));      
          })

        ).done(function() {
          defer.resolve(dash);
        });
        return defer.promise();
        
      }
    };

    ECommerce.reqres.setHandler("product:create", function(data){
      return API.createProduct(data);
    });

    ECommerce.reqres.setHandler("product:catalog", function(page, maincat, qty){
      return API.getProductsCatalog(page, maincat, qty);
    });

    ECommerce.reqres.setHandler("product:featured", function(){
      return API.getFeaturedProducts();
    });

    ECommerce.reqres.setHandler("product:latest", function(){
      return API.getLatestProducts();
    });

    ECommerce.reqres.setHandler("product:filter", function(params){
      return API.getFilteredCatalog(params);
    });
  });

  return ;
});