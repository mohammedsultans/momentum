define(["app"], function(Ecommerce){
  Ecommerce.module('JournalsApp', function(JournalsApp, Ecommerce, Backbone, Marionette, $, _){

    JournalsApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "journals" : "showJournals",
        "journal/:id" : "showJournal",
        "journal/latest/:id" : "showJournal"
      }
    });

    var API = {
      showJournals: function(){
        require(["apps/journals/show/show_controller"], function(ShowController){
          ShowController.showJournals();
          Ecommerce.execute("set:active:header", "journals");
        });
      },

      showJournal: function(id){
        require(["apps/journals/show/show_controller"], function(ShowController){
          ShowController.showJournal(id);
          Ecommerce.execute("set:active:header", "journals");
        });
      }
    };

    Ecommerce.on("journals:show", function(){
      Ecommerce.navigate("journals");
      API.showJournals();
    });

    Ecommerce.on("journal:show", function(id){
      Ecommerce.navigate("journal/latest/"+id);
      API.showJournal(id);
    });

    Ecommerce.addInitializer(function(){
      new JournalsApp.Router({
        controller: API
      });
    });
  });

  return Ecommerce.JournalsApp;
});
