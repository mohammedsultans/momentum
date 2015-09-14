define(["app", "apps/login/show/show_view"], function(System, View){
  System.module('LoginApp.Show', function(Show, System, Backbone, Marionette, $, _){
    Show.Controller = {
      showLogin: function(){
        var view = new View.Login();
        var empty = new System.MenuApp.Show.View.Empty();
        System.menuRegion.show(empty);
        System.contentRegion.show(view);
        view.on('login', function(data) {
            System.trigger("menu:show");
            
        	/*if (data['cookie']) {
        		data['operation'] = 'logincookie';
        	}else{
        		data['operation'] = 'login';
        	}
        	
            $.post('http://192.168.0.103:3030/chase/service/users/index.php', data, function(result) {
                if (result == 1) {
                    var dat = {'operation':'checkauth'};
                    $.post('http://192.168.0.103:3030/chase/service/users/index.php', dat, function(result) {
                        if (result == 1) {                  
                            view.triggerMethod("form:clear");
                        }else{
                            view.triggerMethod("auth:error");
                        }
                    })
                }else{
                	view.triggerMethod("auth:error");
                }
            });*/
        });

        view.on('forgot', function(data) {

            var recoverview = new View.Recover();
            System.contentRegion.show(recoverview);

            recoverview.on('submit', function(data) {
                data['operation'] = 'recoverPassword';
                                
                $.post('http://192.168.0.103:3030/chase/service/users/index.php', data, function(result) {
                    if (result == 1) {
                        recoverview.triggerMethod("form:clear");
                    }else{
                        recoverview.triggerMethod("auth:error");
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
