define(["app", "apps/tools/show/show_view"], function(System, View){
  System.module('ToolsApp.Show', function(Show, System, Backbone, Marionette, $, _){
    Show.Controller = {
      
      userManager: function(a){ 
        var view = new View.Users();
        
        System.contentRegion.show(view);

        view.on('create', function(data) {
          data['operation'] = 'createUser';
          $.post(System.coreRoot + '/service/tools/index.php', data, function(result) {
            if (result == 1) {
                view.triggerMethod("success");
            }else{
              view.triggerMethod("error");
            }
          });
        });

        view.on('modify', function(data) {
          data['operation'] = 'modifyUser';
          $.post(System.coreRoot + '/service/tools/index.php', data, function(result) {
            if (result == 1) {
                view.triggerMethod("success");
            }else{
              view.triggerMethod("error");
            }
          });
        });

        view.on('erase', function(data) {
          data['operation'] = 'removeUser';
          $.post(System.coreRoot + '/service/tools/index.php', data, function(result) {
            if (result == 1) {
                view.triggerMethod("success");
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      userRoles: function(a){ 
        var view = new View.Roles();

        System.contentRegion.show(view);

        view.on('create', function(data) {
          data['operation'] = 'createRole';
          $.post(System.coreRoot + '/service/tools/index.php', data, function(result) {
            if (result == 1) {
                view.triggerMethod("success");
            }else{
              view.triggerMethod("error");
            }
          });
        });

        view.on('modify', function(data) {
          data['operation'] = 'modifyRole';
          $.post(System.coreRoot + '/service/tools/index.php', data, function(result) {
            if (result == 1) {
                view.triggerMethod("success");
            }else{
              view.triggerMethod("error");
            }
          });
        });

        view.on('erase', function(data) {
          data['operation'] = 'deleteRole';
          $.post(System.coreRoot + '/service/tools/index.php', data, function(result) {
            if (result == 1) {
                view.triggerMethod("success");
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      changePassword: function(a){ 
        var view = new View.ChangePassword();
        
        System.contentRegion.show(view);

        view.on('submit', function(data) {
          data['operation'] = 'changePassword';
          $.post(System.coreRoot + '/service/tools/index.php', data, function(result) {
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

  return System.ToolsApp.Show.Controller;
});
