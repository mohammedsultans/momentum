define(["marionette", "sweetalert"], function(Marionette){
  var System = new Marionette.Application();

  window.momentum = System;

  var coreURL = window.location.href.split(window.location.pathname);

  System.coreRoot = coreURL[0]+"/momentum";
  System.cache = {};

  var checkLogin = function(callback) {
    $.get(System.coreRoot + '/service/tools/index.php?session', function(result) {
      if (result != 0) {
        var data = JSON.parse(result);
        if (data['user']['id']) {
          //alert(JSON.stringify(data));
          return callback(data);
        }else{
          return callback(false);
        }
      }else{
        return callback(false);
      }   
    });
  };

  var runApplication = function(data, options) {
    if (data) {
      //alert(JSON.stringify(data));
      System.trigger("menu:show", data);
    } else {
      System.trigger("login:show");
    }
    Backbone.history.start();
  };

  System.addRegions({
    menuRegion: "#menu",
    contentRegion: "#content"
  });

  System.navigate = function(route,  options){
    options || (options = {});
    Backbone.history.navigate(route, options);
  };  

  System.getCurrentRoute = function(){
    return Backbone.history.fragment
  };

  System.on("initialize:after", function(){
    if(Backbone.history){
      require([
        "apps/login/login_app",
        "apps/menu/menu_app",
        "apps/dash/dash_app",
        "apps/clients/clients_app",
        "apps/operations/operations_app",
        "apps/finance/finance_app",
        "apps/procurement/procurement_app",
        "apps/hr/hr_app",
        "apps/tools/tools_app",
        "apps/reports/reports_app",
        //"apps/notifications/notifications_app",
        //"apps/profile/profile_app",
        //"apps/about/about_app"
        ], function () {
        //Backbone.history.start();//{ pushState: true, root: "/ecomadmin/frontend/" }
        checkLogin(runApplication);
        //System.trigger("menu:show");
        //if(System.getCurrentRoute() === ""){
          //System.trigger("dash:show");
        //}
      });
    }
  });

  return System;
});