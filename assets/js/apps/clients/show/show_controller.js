define(["app", "apps/clients/show/show_view"], function(System, View){
  System.module('ClientsApp.Show', function(Show, System, Backbone, Marionette, $, _){
    Show.Controller = {
      showClients: function(){ 
        var view = new View.Clients();
        System.contentRegion.show(view);

        view.on('deleter', function(id) {
          var data = {};
          data['operation'] = 'deleteClient';
          data['id'] = id;
          $.post(System.coreRoot + '/service/crm/index.php', data, function(result) {
            if (result == 1) {
              view.triggerMethod("delete");
            }else{
              view.triggerMethod("error");
            }
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

      enquiries: function(a){ 
        var view = new View.Enquiry();
        

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
          data['operation'] = 'enquiry';
            $.post(System.coreRoot + '/service/crm/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("success");
              }else{
                view.triggerMethod("error");
              }
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

      pendingQueries: function(a){ 
        var view = new View.PendingQueries();

        System.contentRegion.show(view);

        view.on('check', function(stamp) {
          var data = {};
          data['operation'] = 'checkenquiry';
          data['stamp'] = stamp;
          $.post(System.coreRoot + '/service/crm/index.php', data, function(result) {
            if (result == 1) {
              view.triggerMethod("success");
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      addClient: function(a){ 
        var view = new View.Client();
        

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
          data['operation'] = 'addClient';
            $.post(System.coreRoot + '/service/crm/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("success");
              }else{
                view.triggerMethod("error");
              }
            });
        });

        view.on('edit', function(data) {
          data['operation'] = 'editClient';
            $.post(System.coreRoot + '/service/crm/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("success");
              }else{
                view.triggerMethod("error");
              }
            });
        });

        view.on('delete', function(data) {
          data['operation'] = 'deleteClient';
            $.post(System.coreRoot + '/service/crm/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("delete");
              }else{
                view.triggerMethod("error");
              }
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

  return System.ClientsApp.Show.Controller;
});
