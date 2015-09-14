define(["app_admin"], function(TheMarket){
  TheMarket.module('Entities', function(Entities, TheMarket, Backbone, Marionette, $, _){

    Backbone.emulateJSON = true;

    Entities.Model = Backbone.Model.extend({
      urlRoot: "ecomadmin/presentation/inventory",
    });

    Entities.Collection = Backbone.Collection.extend({
      url: "/ecomadmin/presentation/inventory",
      model: Entities.Model
    });

    //Entities.configureStorage(Entities.Journal);

    //Entities.configureStorage(Entities.JournalCollection);

    var initializeCollection = function(val){
      var collection = new Entities.Collection(val);
      return collection.models;
    };

    var blogs, currentBlog;

    var API = {
      createProduct: function(data){
        var subscribers = new Entities.Collection();
        var defer = $.Deferred();

        $.when(

          $.post("/ecomadmin/presentation/inventory", data, function(val){
            var models = initializeModels(JSON.parse(val));
            subscribers.reset(models);       
          })

        ).done(function() {
          defer.resolve(subscribers);
        });
        return defer.promise();
        
      },

      getProduct: function(id){
        var admins = new Entities.Collection();
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/presentation/admin/?admins", function(val){
            var models = initializeModels(JSON.parse(val));
            admins.reset(models);       
          })

        ).done(function() {
          defer.resolve(admins);
        });
        return defer.promise();
        
      },

      getProducts: function(page){
        var collection;
        var defer = $.Deferred();

        $.when(

          $.get("/ecomadmin/presentation/inventory/?products&page="+page, function(val){
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

          $.get("/ecomadmin/presentation/admin/?searchSubscriber&name="+name, function(val){
            var models = initializeModels(JSON.parse(val));
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

          $.get("/ecomadmin/presentation/admin/?adminDetails", function(val){
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

          $.get("/ecomadmin/presentation/admin/?dashDetails", function(val){
            dash = new Entities.Model(JSON.parse(val));      
          })

        ).done(function() {
          defer.resolve(dash);
        });
        return defer.promise();
        
      }
    };

    TheMarket.reqres.setHandler("product:create", function(){
      return API.createProduct();
    });
  });

  return ;
});