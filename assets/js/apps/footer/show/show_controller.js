define(["app", "apps/footer/show/show_view"], function(Ecommerce, View){
  Ecommerce.module('FooterApp.Show', function(Show, Ecommerce, Backbone, Marionette, $, _){
    Show.Controller = {
      showFooter: function(){
        var footer = new View.Footer();
        Ecommerce.footerRegion.show(footer);

        footer.on('privacy', function() {
          Ecommerce.execute("set:active:header", "home");
        	Ecommerce.navigate("privacy");
           	var privacy = new View.Privacy();
        	Ecommerce.mainRegion.show(privacy);
        });

        footer.on('terms', function() {
          Ecommerce.execute("set:active:header", "home");
        	Ecommerce.navigate("terms");
        	var terms = new View.Terms();
        	Ecommerce.mainRegion.show(terms);
        });
      },

      showPrivacy: function(){
        var privacy = new View.Privacy();
        Ecommerce.mainRegion.show(privacy);
        $(document).scrollTop(0);
      },

      showTerms: function(){
        var terms = new View.Terms();
        Ecommerce.mainRegion.show(terms);
        $(document).scrollTop(0);
      }
    };
  });

  return Ecommerce.FooterApp.Show.Controller;
});