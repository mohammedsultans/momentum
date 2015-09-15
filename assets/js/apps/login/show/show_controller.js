define(["app", "apps/login/show/show_view"], function(System, View){
  System.module('LoginApp.Show', function(Show, System, Backbone, Marionette, $, _){
    Show.Controller = {
      showLogin: function(){
        var view = new View.Login();
        var empty = new System.MenuApp.Show.View.Empty();
        System.menuRegion.show(empty);
        System.contentRegion.show(view);

        view.on('login', function(data) {
            data['operation'] = 'login';
            $.post(System.coreRoot + '/service/tools/index.php', data, function(result) {
                if (result != 0) {
                    var data = JSON.parse(result);
                    if (data['user']['id']) {
                        view.triggerMethod("success", data);
                    };                    
                }else{
                  view.triggerMethod("error");
                }
            });
        });

        view.on('forgot', function(data) {
            var recoverview = new View.Recover();
            System.contentRegion.show(recoverview);

            recoverview.on('submit', function(data) {
                data['operation'] = 'login';
                $.post(System.coreRoot + '/service/tools/index.php', data, function(result) {
                    if (result != 0) {
                        var data = JSON.parse(result);
                        if (data['id']) {
                            view.triggerMethod("success");
                        };                    
                    }else{
                      view.triggerMethod("error");
                    }
                });
            });

            recoverview.on('login', function() {
                System.trigger("login:show");
            });
        });
      }
    };
  });

  return System.LoginApp.Show.Controller;
});
