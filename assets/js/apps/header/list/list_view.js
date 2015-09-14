define(["app", "tpl!apps/templates/header.tpl"],//"tpl!apps/header/list/templates/list_item.tpl"
        function(Ecommerce, headerTpl){
  Ecommerce.module('HeaderApp.List.View', function(View, Ecommerce, Backbone, Marionette, $, _){
    View.Header = Marionette.ItemView.extend({
      template: headerTpl
    });

    /*View.HeaderItem = Marionette.ItemView.extend({
      template: listItemTpl,
      tagName: "li",

      events: {
        "click a": "navigate"
      },

      navigate: function(e){
        e.preventDefault();
        this.trigger("navigate", this.model);
      },

      onRender: function(){
        if(this.model.selected){
          // add class so Bootstrap will highlight the active entry in the navbar
          this.$el.addClass("active");
        };
      }
    });

    View.Headers = Marionette.CompositeView.extend({
      template: listTpl,
      className: "navbar navbar-fixed-top",
      itemView: View.HeaderItem,
      itemViewContainer: "ul",

      events: {
        "click a.brand": "brandClicked"
      },

      brandClicked: function(e){
        e.preventDefault();
        this.trigger("brand:clicked");
      }
    });*/

    
  });

  return Ecommerce.HeaderApp.List.View;
});
