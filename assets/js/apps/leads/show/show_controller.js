define(["app", "apps/leads/show/show_view"], function(System, View){
  System.module('LeadsApp.Show', function(Show, System, Backbone, Marionette, $, _){
    Show.Controller = {
      showLeads: function(){ 
        var view = new View.Leads();
        System.contentRegion.show(view);
        /*require(["apps/entities/inventory"], function(){
          $.when(System.request("product:featured")).done(function(response){
            //alert(JSON.stringify(response.length));
            var view = new View.Slides({ collection: response });
            layout.slidesRegion.show(view); 
          });

          $.when(System.request("product:latest")).done(function(response){
            //alert(JSON.stringify(response.length));
            var view = new View.Latest({ collection: response });
            layout.latestRegion.show(view); 
          });
        }); */
	    },

      addLead: function(a){ 
        var view = new View.Customer();;
        

        /*if (a) {
          var x = Backbone.Model.extend({
            urlRoot: "presentation/blog",
          });
          var model = new x;
          model.set('name', a.name);
          model.set('phone', a.phone);
          view = new View.AddContactLead({model: model});
        }else{
          view = new View.AddLead();
        }*/
        
        System.contentRegion.show(view);

        view.on('create', function(data) {
          data['operation'] = 'create';
            $.post('http://192.168.43.29/chases/leads.php', data, function(result) {
              if (result == 1) {
                alert('Success: Lead created');
                //admin.triggerMethod("form:done");
              };
            });
        });
        /*require(["apps/entities/inventory"], function(){
          $.when(System.request("product:featured")).done(function(response){
            //alert(JSON.stringify(response.length));
            var view = new View.Slides({ collection: response });
            layout.slidesRegion.show(view); 
          });

          $.when(System.request("product:latest")).done(function(response){
            //alert(JSON.stringify(response.length));
            var view = new View.Latest({ collection: response });
            layout.latestRegion.show(view); 
          });
        }); */
      },

      addClient: function(a){ 
        var view = new View.Client();;
        

        /*if (a) {
          var x = Backbone.Model.extend({
            urlRoot: "presentation/blog",
          });
          var model = new x;
          model.set('name', a.name);
          model.set('phone', a.phone);
          view = new View.AddContactLead({model: model});
        }else{
          view = new View.AddLead();
        }*/
        
        System.contentRegion.show(view);

        view.on('create', function(data) {
          data['operation'] = 'create';
            $.post('http://192.168.43.29/chases/leads.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("success");
              };
            });
        });
        /*require(["apps/entities/inventory"], function(){
          $.when(System.request("product:featured")).done(function(response){
            //alert(JSON.stringify(response.length));
            var view = new View.Slides({ collection: response });
            layout.slidesRegion.show(view); 
          });

          $.when(System.request("product:latest")).done(function(response){
            //alert(JSON.stringify(response.length));
            var view = new View.Latest({ collection: response });
            layout.latestRegion.show(view); 
          });
        }); */
      },

      searchContact: function(layout){ 
        var views = new View.SearchHeader();
        layout.topRegion.show(views);

        views.on('results', function(data) {
          alert(JSON.stringify(data));
          var mod = Backbone.Model.extend({
            urlRoot: "presentation/blog",
          });

          var col = Backbone.Collection.extend({
            url: "presentation/blog",
            model: mod
          });

          var collection = new col(data);

          alert(collection.length + ' contacts found!');
          var result = new View.Contacts({ collection: collection});
          layout.resultRegion.show(result);
        });
        
        /*require(["apps/entities/inventory"], function(){
          $.when(System.request("product:featured")).done(function(response){
            //alert(JSON.stringify(response.length));
            var view = new View.Slides({ collection: response });
            layout.slidesRegion.show(view); 
          });

          $.when(System.request("product:latest")).done(function(response){
            //alert(JSON.stringify(response.length));
            var view = new View.Latest({ collection: response });
            layout.latestRegion.show(view); 
          });
        }); */
      }
    };
  });

  return System.LeadsApp.Show.Controller;
});
