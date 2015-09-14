define(["app", "apps/clients/show/show_controller", "tpl!apps/templates/searchcontact.tpl",], function(System, showController, searchTpl){
  System.module('ClientsApp', function(ClientsApp, System, Backbone, Marionette, $, _){

    ClientsApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "clients" : "showClients",
        "addClient" : "addClient",
        "enquiries" : "enquiries",
        "pending" : "pendingQueries",
        "searchcontact" : "searchContact"
      }
    });

    var SearchLayout = Backbone.Marionette.Layout.extend({
      template: searchTpl,

      regions: {
        topRegion: ".page-header",
        resultRegion: ".container-widget"
      }

    });

    var layout = new SearchLayout();

    var API = {
      showClients: function(){
        //System.contentRegion.show();
        showController.showClients();
        //System.execute("set:active:header", "Menu");
      },

      addClient: function(a){
        //System.contentRegion.show();
        showController.addClient(a);
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
      },

      searchContact: function(){
        System.contentRegion.show(layout);
        showController.searchContact(layout);
        //System.execute("set:active:header", "Menu");
      }
    };

    System.on("clients:show", function(){
      System.navigate("clients");
      API.showClients();
    });
    
    System.addInitializer(function(){
      new ClientsApp.Router({
        controller: API
      });
    });
  });

  return System.ClientsApp;
});

