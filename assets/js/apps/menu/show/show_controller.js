define(["app", "apps/menu/show/show_view"], function(System, View){
  System.module('MenuApp.Show', function(Show, System, Backbone, Marionette, $, _){
    Show.Controller = {
      showMenu: function(data){ 

        var bbmodel = Backbone.Model.extend({
          urlRoot: "presentation/blog",
        });
        
        var model = new bbmodel(data);
        var view = new View.Menu({model: model});
        System.menuRegion.show(view);
	    }
    };
  });

  return System.MenuApp.Show.Controller;
});
