define(["app", "apps/hr/show/show_view"], function(System, View){
  System.module('HRApp.Show', function(Show, System, Backbone, Marionette, $, _){
    Show.Controller = {

      addEmployee: function(a){ 
        var view = new View.Employee();
        
        System.contentRegion.show(view);

        view.on('create', function(data) {
          data['operation'] = 'addEmployee';
            $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("success");
              }else{
                view.triggerMethod("error");
              }
            });
        });

        view.on('edit', function(data) {
          data['operation'] = 'editEmployee';
            $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("success");
              }else{
                view.triggerMethod("error");
              }
            });
        });

        view.on('del', function(data) {
          data['operation'] = 'deleteEmployee';
            $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("delete");
              }else{
                view.triggerMethod("error");
              }
            });
        });
      },

      showEmployees: function(){ 
        var view = new View.Employees();
        System.contentRegion.show(view);

        view.on('del', function(id) {
          var data = {};
          data['operation'] = 'deleteEmployee';
          data['id'] = id;
          $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
            if (result == 1) {
              view.triggerMethod("delete");
            }else{
              view.triggerMethod("error");
            }
          });
        });
	    },

      enquiries: function(a){ 
        var view = new View.Enquiry();
        
        System.contentRegion.show(view);

        view.on('create', function(data) {
          data['operation'] = 'enquiry';
            $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("success");
              }else{
                view.triggerMethod("error");
              }
            });
        });
      },

      pendingQueries: function(a){ 
        var view = new View.PendingQueries();

        System.contentRegion.show(view);

        view.on('check', function(stamp) {
          var data = {};
          data['operation'] = 'checkenquiry';
          data['stamp'] = stamp;
          $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
            if (result == 1) {
              view.triggerMethod("success");
            }else{
              view.triggerMethod("error");
            }
          });
        });
      }
      
    };
  });

  return System.HRApp.Show.Controller;
});
