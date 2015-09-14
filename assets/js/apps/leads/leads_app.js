define(["app", "apps/leads/show/show_controller", "tpl!apps/templates/searchcontact.tpl",], function(System, showController, searchTpl){
  System.module('LeadsApp', function(LeadsApp, System, Backbone, Marionette, $, _){

    LeadsApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "leads" : "showLeads",
        "addClient" : "addClient",
        "addlead" : "addLead",
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
      showLeads: function(){
        //System.contentRegion.show();
        showController.showLeads();
        //System.execute("set:active:header", "Menu");
      },

      addClient: function(a){
        //System.contentRegion.show();
        showController.addClient(a);
        //System.execute("set:active:header", "Menu");
      },

      addLead: function(a){
        //System.contentRegion.show();
        showController.addLead(a);
        //System.execute("set:active:header", "Menu");
      },

      searchContact: function(){
        System.contentRegion.show(layout);
        showController.searchContact(layout);
        //System.execute("set:active:header", "Menu");
      }
    };

    System.on("leads:show", function(){
      System.navigate("leads");
      API.showLeads();
    });

    System.on("leads:add", function(){
      System.navigate("addlead");
      API.addLead();
    });

    System.on("leads:contactadd", function(a){
      System.navigate("addlead");
      API.addLead(a);
    });

     System.on("leads:search", function(){
      System.navigate("searchcontact");
      API.searchContact();
    });

    System.addInitializer(function(){
      new LeadsApp.Router({
        controller: API
      });
    });
  });

  return System.LeadsApp;
});

