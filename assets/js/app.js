define(["marionette", "plugins", "sweetalert"], function(Marionette){
  var System = new Marionette.Application();

  System.addRegions({
    menuRegion: "#menu",
    contentRegion: "#content"
  });

  System.navigate = function(route,  options){
    options || (options = {});
    Backbone.history.navigate(route, options);
  };

  System.coreRoot = "http://code.dev/momentum";

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
        //"apps/notifications/notifications_app",
        //"apps/reports/reports_app",
        //"apps/profile/profile_app",
        //"apps/about/about_app"
        ], function () {
        Backbone.history.start();//{ pushState: true, root: "/ecomadmin/frontend/" }
        System.trigger("menu:show");
        if(System.getCurrentRoute() === ""){
          System.trigger("dash:show");
        }
      });
    }
  });

  return System;
});