define(["app", "tpl!apps/templates/check_out.tpl", "backbone.syphon"], 
	function(Ecommerce, checkoutTpl){
  Ecommerce.module('CheckOutApp.Show.View', function(View, Ecommerce, Backbone, Marionette, $, _){

	View.CheckOut = Marionette.ItemView.extend({
	    template: checkoutTpl,

	    events: {
	        'click button.csubmit': 'submitClicked'
	    },

	      onShow: function(){

          	require(["catalog", "scripts"], function(){
          		require.undef('scripts');
            	require(["scripts"], function(){});
        	});     
           
        },


	    submitClicked: function(e){
	        e.preventDefault();
	        var data = Backbone.Syphon.serialize(this);
	        alert(data);
	        this.trigger("form:submit", data);
	    }
	});

	/*View.ContactForm = Marionette.ItemView.extend({
	    template: contactTpl,

	      events: {
	        'click button.csubmit': 'submitClicked'
	      },

	      submitClicked: function(e){
	        e.preventDefault();
	        var data = Backbone.Syphon.serialize(this);
	        alert(data);
	        this.trigger("form:submit", data);
	      }

	      /*onFormDataInvalid: function(errors){
	        var $view = this.$el;

	        var clearFormErrors = function(){
	          var $form = $view.find("form");
	          $form.find(".help-inline.error").each(function(){
	            $(this).remove();
	          });
	          $form.find(".control-group.error").each(function(){
	            $(this).removeClass("error");
	          });
	        }

	        var markErrors = function(value, key){
	          var $controlGroup = $view.find("#contact-" + key).parent();
	          var $errorEl = $('<span>', { class: "help-inline error", text: value });
	          $controlGroup.append($errorEl).addClass("error");
	        }

	        clearFormErrors();
	        _.each(errors, markErrors);
	      }-*-/
	});

	View.ContactPage = Marionette.ItemView.extend({

	      template: contactTpl,

	      events: {
	      	'click button.csubmit': 'submitClicked',
	        "click .btn-art": "viewNode"
	      },

	      initialize: function(){ },

	      onRender: function(){ },

	      onShow: function(){ },

	      viewNode: function (e) {
	        e.preventDefault();
	        e.stopPropagation();
	        alert("Get more here");
	      },

	      submitClicked: function(e){
	        e.preventDefault();
	        var data = Backbone.Syphon.serialize(this);
	        this.trigger("csubmit", data);
	      },

	      onFormClear: function(){
	      	var $view = this.$el;
	        //$("input[name=password]").val("");
        	//$("input[name=password]").focus();
	        var $form = $view.find("form");
	        $form.find("input").each(function(){
	           $(this).val("");
	        });
	        $form.find("textarea").each(function(){
	           $(this).val("");
	        });
	        $("input[name=name]").focus();
	        alert("Query sent successfully. Please wait for an email from us. Thank you");
	      }
	});*/
  });
  return Ecommerce.CheckOutApp.Show.View;
});
