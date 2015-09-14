define(["marionette"], function(Marionette){
  var TheMarket = new Marionette.Application();

  var checkLogin = function(callback) {
    $.ajax("/ecomadmin/presentation/admin/?adminDetails", {
      method: "GET",
      success: function(data) {
        //data == OK
        data = JSON.parse(data);
        if (data) {
          return callback(data);
        }else{
          return callback(false);
        }
      },
      error: function(data) {
        return callback(false);
      }
    });
  };

  var runApplication = function(data) { 
    if (data) {
      TheMarket.execute("auth:ok", data);
    } else {
      TheMarket.trigger("auth:displayLogin");
    }
  };

  TheMarket.addRegions({
    topRegion: "#top-menu",
    sidebarRegion: "#sidebar",
    mainContentRegion: "#main-content",
    menuRightRegion: "#menu-right"
  });

  TheMarket.navigate = function(route,  options){
    options || (options = {});
    Backbone.history.navigate(route, options);
  };

  TheMarket.getCurrentRoute = function(){
    return Backbone.history.fragment
  };

  TheMarket.on("initialize:after", function(){
    if(Backbone.history){
      //checkLogin(runApplication); 
      Backbone.history.start();
      //Backbone.history.start({ pushState: true, root: "/ecomadmin/admin/" });
      var data = {};
      TheMarket.execute("auth:ok", data);
    }
  });

  return TheMarket;
});
