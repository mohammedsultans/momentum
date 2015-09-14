define(["app", "apps/journals/show/show_view"], function(Ecommerce, View){
  Ecommerce.module('JournalsApp.Show', function(Show, Ecommerce, Backbone, Marionette, $, _){
    Show.Controller = {
      showJournals: function(){
      	/*var m = Ecommerce.request("journal:entities");
      	console.log(m);
      	var journalcollection = new View.Journals();
        Ecommerce.mainRegion.show(journalcollection);*/
      	require(["entities/lightning"], function(){
          $.when(Ecommerce.request("journal:entities")).done(function(content){
            if(content !== undefined){
              var journalcollection = new View.Journals({ collection: content });
              Ecommerce.mainRegion.show(journalcollection);

               journalcollection.on('itemview:read', function(arg, model) {
                  $.when(Ecommerce.request("issue:articles", model)).done(function(contents){
                    Ecommerce.navigate("journal/latest/"+model.get('journal_id'));
                    var issueView = new View.Issue({ model: contents });
                    Ecommerce.mainRegion.show(issueView);

                    issueView.on('navjournals', function() {
                      Ecommerce.mainRegion.show(journalcollection);
                    });
                  });
              });
            }
          });
      	});
      },

      showJournal: function(id){
        /*var m = Ecommerce.request("journal:entities");
        console.log(m);
        var journalcollection = new View.Journals();
        Ecommerce.mainRegion.show(journalcollection);*/
        require(["entities/lightning"], function(){
          $.when(Ecommerce.request("latest:issue", id)).done(function(contents){
            var issueView = new View.Issue({ model: contents });
            Ecommerce.mainRegion.show(issueView);

            issueView.on('navjournals', function() {
              Ecommerce.trigger("journals:show");
            });
          });
        });
      }
    };
  });

  return Ecommerce.JournalsApp.Show.Controller;
}); 
