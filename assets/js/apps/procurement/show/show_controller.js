define(["app", "apps/procurement/show/show_view"], function(System, View){
  System.module('ProcurementApp.Show', function(Show, System, Backbone, Marionette, $, _){
    Show.Controller = {
      showSuppliers: function(){ 
        var view = new View.Suppliers();
        System.contentRegion.show(view);

        view.on('del', function(id) {
          var data = {};
          data['operation'] = 'deleteSupplier';
          data['id'] = id;
          $.post(System.coreRoot + '/service/procurement/index.php', data, function(result) {
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

      //receiveQuotation
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
            $.post(System.coreRoot + '/service/procurement/index.php', data, function(result) {
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
          $.post(System.coreRoot + '/service/procurement/index.php', data, function(result) {
            if (result == 1) {
              view.triggerMethod("success");
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      addSupplier: function(a){ 
        var view = new View.Supplier();
        
        System.contentRegion.show(view);

        view.on('create', function(data) {
          data['operation'] = 'addSupplier';
            $.post(System.coreRoot + '/service/procurement/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("success");
              }else{
                view.triggerMethod("error");
              }
            });
        });

        view.on('edit', function(data) {
          data['operation'] = 'editSupplier';
            $.post(System.coreRoot + '/service/procurement/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("success");
              }else{
                view.triggerMethod("error");
              }
            });
        });

        view.on('delete', function(data) {
          data['operation'] = 'deleteSupplier';
            $.post(System.coreRoot + '/service/procurement/index.php', data, function(result) {
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

      receiveGoods: function(a){ 
        var view = new View.Supplier();
        

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
          data['operation'] = 'addSupplier';
            $.post(System.coreRoot + '/service/procurement/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("success");
              }else{
                view.triggerMethod("error");
              }
            });
        });

        view.on('edit', function(data) {
          data['operation'] = 'editClient';
            $.post(System.coreRoot + '/service/procurement/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("success");
              }else{
                view.triggerMethod("error");
              }
            });
        });

        view.on('delete', function(data) {
          data['operation'] = 'deleteClient';
            $.post(System.coreRoot + '/service/procurement/index.php', data, function(result) {
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

      returnGoods: function(a){ 
        var view = new View.Supplier();
        

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
          data['operation'] = 'addSupplier';
            $.post(System.coreRoot + '/service/procurement/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("success");
              }else{
                view.triggerMethod("error");
              }
            });
        });

        view.on('edit', function(data) {
          data['operation'] = 'editClient';
            $.post(System.coreRoot + '/service/procurement/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("success");
              }else{
                view.triggerMethod("error");
              }
            });
        });

        view.on('delete', function(data) {
          data['operation'] = 'deleteClient';
            $.post(System.coreRoot + '/service/procurement/index.php', data, function(result) {
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
      }
    };
  });

  return System.ProcurementApp.Show.Controller;
});
