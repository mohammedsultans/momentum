define(["app", "apps/tools/show/show_view"], function(System, View){
  System.module('ToolsApp.Show', function(Show, System, Backbone, Marionette, $, _){
    Show.Controller = {
      
      userManager: function(a){ 
        var view = new View.Users();
        
        System.contentRegion.show(view);

        view.on('post', function(data) {
          data['operation'] = 'postInvoice';
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
            if (result != 0) {
              var res = JSON.parse(result);
              //alert(JSON.stringify(res));
              if (res['transactionId']) {
                view.triggerMethod("success", res);
              }else{
                view.triggerMethod("error");
              }              
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      userRoles: function(a){ 
        var view = new View.Roles();

        System.contentRegion.show(view);

        view.on('submit', function(data) {
          data['operation'] = 'receivePayment';
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
            if (result != 0) {
              var res = JSON.parse(result);
              //alert(JSON.stringify(res));
              if (res['transactionId']) {
                view.triggerMethod("success", res);
              }else{
                view.triggerMethod("error");
              }              
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      changePassword: function(a){ 
        var view = new View.ChangePassword();
        
        System.contentRegion.show(view);

        view.on('create', function(data) {
          data['operation'] = 'createLedger';
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
            if (result == 1) {
              view.triggerMethod("success");     
            }else{
              view.triggerMethod("error");
            }
          });
        });

        view.on('delete', function(lid) {
          data = {};
          data['operation'] = 'deleteLedger';
          data['lid'] = lid;
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
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
