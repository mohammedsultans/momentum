define(["app", "tpl!apps/templates/footer.tpl"],
 function(Ecommerce, footerTpl){
  Ecommerce.module('FooterApp.Show.View', function(View, Ecommerce, Backbone, Marionette, $, _){

    View.Footer = Marionette.ItemView.extend({
      template: footerTpl,

      events: {
        "click a#privacy": "privacyClicked",
        "click a#terms": "termsClicked",
        "click a#about": "aboutClicked",
        "click a#contact": "aboutClicked"
      },

      privacyClicked: function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.trigger("privacy");
      },

      termsClicked: function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.trigger("terms");
      },

      aboutClicked: function(e) {
        e.preventDefault();
        e.stopPropagation();
        Ecommerce.trigger("about:show");
      },

      aboutClicked: function(e) {
        e.preventDefault();
        e.stopPropagation();
        Ecommerce.trigger("about:show");
      }

    });

    /*View.Privacy = Marionette.ItemView.extend({
      template: privacyTpl 
    });

    View.Terms = Marionette.ItemView.extend({
      template: termsTpl 
    });*/
  });

  return Ecommerce.FooterApp.Show.View;
});
