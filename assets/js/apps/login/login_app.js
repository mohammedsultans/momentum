define(["app"], function(System){
  System.module('LoginApp', function(LoginApp, System, Backbone, Marionette, $, _){

    LoginApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "login" : "showLogin"
      }
    });

    var API = {
      showLogin: function(){
        require(["apps/login/show/show_controller"], function(ShowController){
          ShowController.showLogin();
          $('.loading').css({
                'display' : 'none'
            });  
          //System.execute("set:active:header", "login");
        });
      }
    };

    System.on("login:show", function(){
      System.navigate("login");
      API.showLogin();
    });

    System.addInitializer(function(){
      new LoginApp.Router({
        controller: API
      });
    });
  });

  return System.LoginApp;
});
