define(["app", "apps/header/list/list_view"], function(Ecommerce, View){
  Ecommerce.module('HeaderApp.List', function(List, Ecommerce, Backbone, Marionette, $, _){
    List.Controller = {
      /*listHeader: function(){
        require(["entities/header"], function(){
          var links = Ecommerce.request("header:entities");
          var headers = new View.Headers({collection: links});

          headers.on("brand:clicked", function(){
            Ecommerce.trigger("home:show");
          });

          headers.on("itemview:navigate", function(childView, model){
            var url = model.get('url');
            if(url){
              Ecommerce.trigger(url + ":show");
            }else{
              throw "No such sub-application: " + url;
            }
            $(".btn-navbar").click();
          });

          
          Ecommerce.headerRegion.show(headers);
        });
      },

      setActiveHeader: function(headerUrl){
        var links = Ecommerce.request("header:entities");
        var headerToSelect = links.find(function(header){ return header.get("url") === headerUrl; });
        headerToSelect.select();
        links.trigger("reset");
      },*/

      showHeader: function(headerUrl){
        var view = new View.Header();
        Ecommerce.headerRegion.show(view);
      }
    };
  });

  return Ecommerce.HeaderApp.List.Controller;
});
