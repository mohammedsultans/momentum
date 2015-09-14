define(["marionette", "bcollapse"], function(Marionette){
  var LightningAbstracts = new Marionette.Application();

  LightningAbstracts.addRegions({
    headerRegion: "#header-region",
    mainRegion: "#main-region",
    footerRegion: "#footer-region"
  });

  LightningAbstracts.navigate = function(route,  options){
    options || (options = {});
    Backbone.history.navigate(route, options);
  };

  LightningAbstracts.getCurrentRoute = function(){
    return Backbone.history.fragment
  };

  LightningAbstracts.on("initialize:after", function(){
    if(Backbone.history){
      require(["apps/user/feeds/feeds_app", "apps/user/archives/archives_app", 
        "apps/user/journals/journals_app", "apps/user/trends/trends_app", 
        "apps/user/blog/blog_app", "apps/user/prefs/prefs_app"], function () {
        
        Backbone.history.start();//{ pushState: true, root: "/ecomadmin/user/"  }

        if(LightningAbstracts.getCurrentRoute() === ""){
          LightningAbstracts.trigger("feeds:show");
        }
      });
    }
  });

  return LightningAbstracts;
});
