define(["app", "apps/operations/show/show_controller"], function(System, showController){
  System.module('OperationsApp', function(OperationsApp, System, Backbone, Marionette, $, _){

    OperationsApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "newproject" : "addProject",
        "viewproject" : "viewProject",
        "quotation" : "createQuote",
        "filereport" : "fileReport",
        "billables" : "setBillables",
        "documentTypes" : "setDocumentTypes",
        "documentRegistry" : "documentRegistry",
        "findDocuments" : "findDocuments"
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

      setDocumentTypes: function(){
        //System.contentRegion.show();
        showController.setDocumentTypes();
        //System.execute("set:active:header", "Menu");
      },

      documentRegistry: function(){
        //System.contentRegion.show();
        showController.documentRegistry();
        //System.execute("set:active:header", "Menu");
      },

      findDocuments: function(){
        //System.contentRegion.show();
        showController.findDocuments();
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

