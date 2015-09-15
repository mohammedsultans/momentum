define(["app", "tpl!apps/login/show/templates/login.tpl", "tpl!apps/login/show/templates/recover.tpl", "backbone.syphon"], 
	function(System, loginTpl, recoverTpl){
  System.module('LoginApp.Show.View', function(View, System, Backbone, Marionette, $, _){
    
    View.Login = Marionette.ItemView.extend({
	    template: loginTpl,

	    events: {
	    	'click button.btn': 'submitClicked',
	    	'click #forgot-pass': 'forgotClicked',
	        "click .btn-art": "viewNode"
	    },

	  	initialize: function(){},

	  	onRender: function(){	  		
	  		$("#loading").hide();
	  		$('.content.main').animate({'marginLeft' : 0}, 250); 
        	//$("#error").hide();
        	$("input[name=username]").focus();
	  	},

	    onShow: function(){
	    	//this.delegateEvents();
	    	this.$el.find("form input").val("");
	        $("input[name=name]").focus();
	    },

	    submitClicked: function(e){
	        e.preventDefault();
	        e.stopPropagation();
	        var data = Backbone.Syphon.serialize(this);
	        this.trigger("login", data);	     
	    },

	    forgotClicked: function(e){
	        e.preventDefault();
	        e.stopPropagation();
	        this.trigger("forgot");
	    },

	    onFormClear: function(){
	      	this.$el.find("form input").val("");
	        $("input[name=name]").focus();
	    },

	    retryClicked: function(e){
	        e.preventDefault();
	        $("#error").slideUp();
	        $("input[name=password]").val("");
	        $("input[name=password]").focus();
	    },

	    onFormLoginInvalid: function(){
	        $("#loading").slideUp();
	        //$("#error-msg").text('Unable to login.');
	        $("#error").slideDown();
	    },

	    onFormLoginLoading: function(){
	        $("#loading").slideDown();
	    },

	    onSuccess: function(data) { 
          swal("Welcome!", "Login successful.", "success");
          System.trigger("menu:show", data);
        },

        onError: function(e) { 
          $("input[name=password]").val("");
	      $("input[name=password]").focus();
          swal("Access Denied!", "Login failed: Check your details and try again", "error");
        }
    });

	View.Recover = Marionette.ItemView.extend({
	    template: recoverTpl,

	    events: {
	    	'click button.ssmall': 'submitClicked',
	    	'click #login-link': 'loginClicked'
	    },

	  	initialize: function(){},

	  	onRender: function(){
	  		$("#loading").hide();
        	$("#error").hide();
	  	},

	    onShow: function(){
	    	var $view = this.$el;
	        var $form = $view.find("form");
	    	$form.find("input").each(function(){
	           $(this).val("");
	        });
	        $("input[name=email]").focus();
	    },

	    submitClicked: function(e){
	        e.preventDefault();
	        var data = Backbone.Syphon.serialize(this);
	        this.trigger("submit", data);	     
	    },

	    loginClicked: function(e){
	        e.preventDefault();
	        this.trigger("login");
	    },

	    onFormClear: function(){
	      	var $view = this.$el;
	        //$("input[name=password]").val("");
	       	//$("input[name=password]").focus();
	       	var $name = $("input[name=name]").val();
	        var $form = $view.find("form");
	        $form.find("input").each(function(){
	           $(this).val("");
	        });
	        $("input[name=name]").focus();
	        alert('Success: Your password has been reset. Please check your email for your new credentials');
	        //window.location.replace('http://localhost/user');
	    },

	    onAuthError: function(){
	      	//var $view = this.$el;
	        $("input[name=password]").val("");
	       	$("input[name=password]").focus();
	       	alert('Error: Your email did not match any in our records. Check your details and try again');
	    },

	    retryClicked: function(e){
	        e.preventDefault();
	        $("#error").slideUp();
	        $("input[name=password]").val("");
	        $("input[name=password]").focus();
	    },

	    onFormLoginInvalid: function(){
	        $("#loading").slideUp();
	        //$("#error-msg").text('Unable to login.');
	        $("#error").slideDown();
	    },

	    onFormLoginLoading: function(){
	        $("#loading").slideDown();
	    }
    });
  });

  return System.LoginApp.Show.View;
});
