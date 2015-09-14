define(["app", "apps/header/list/list_controller"], function(Ecommerce, ListController){
  Ecommerce.module('HeaderApp', function(Header, Ecommerce, Backbone, Marionette, $, _){
    var API = {
      listHeader: function(){
        ListController.showHeader();
      }
    };

    Ecommerce.commands.setHandler("set:active:header", function(name){
      ListController.setActiveHeader(name);
    });

    Header.on("start", function(){
      API.listHeader();
    });
  });

  return Ecommerce.HeaderApp;
});