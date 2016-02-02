define(["app", "apps/operations/show/show_controller"], function(System, showController){
  System.module('OperationsApp', function(OperationsApp, System, Backbone, Marionette, $, _){

    OperationsApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "newproject" : "addProject",
        "viewproject" : "viewProject",
        "quotation" : "createQuote",
        "filereport" : "fileReport",
        "billables" : "setBillables",
        "docOrigination" : "docOrigination",
        "docRegistry" : "docRegistry",
        "docArchive" : "docArchive",        
        "docTypes" : "setDocTypes"      
      }
    });

    var API = {
      addProject: function(){
        //System.contentRegion.show();
        showController.addProject();
        //System.execute("set:active:header", "Menu");
      },

      viewProject: function(){
        //System.contentRegion.show();
        showController.viewProject();
        //System.execute("set:active:header", "Menu");
      },

      createQuote: function(a){
        //System.contentRegion.show();
        showController.createQuote(a);
        //System.execute("set:active:header", "Menu");
      },

      fileReport: function(){
        //System.contentRegion.show();
        showController.fileReport();
        //System.execute("set:active:header", "Menu");
      },

      setBillables: function(){
        //System.contentRegion.show();
        showController.setBillables();
        //System.execute("set:active:header", "Menu");
      },

      docOrigination: function(){
        //System.contentRegion.show();
        showController.docOrigination();
        //System.execute("set:active:header", "Menu");
      },

      docRegistry: function(){
        //System.contentRegion.show();
        showController.docRegistry();
        //System.execute("set:active:header", "Menu");
      },

      docArchive: function(){
        //System.contentRegion.show();
        showController.docArchive();
        //System.execute("set:active:header", "Menu");
      },

      setDocTypes: function(){
        //System.contentRegion.show();
        showController.setDocTypes();
        //System.execute("set:active:header", "Menu");
      }

    };

    System.on("add:project", function(){
      System.navigate("newproject");
      API.addProject();
    });

    System.addInitializer(function(){
      new OperationsApp.Router({
        controller: API
      });
    });
  });

  return System.OperationsApp;
});

