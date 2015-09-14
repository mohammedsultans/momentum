define(["app", "tpl!apps/templates/catalog.tpl", "tpl!apps/templates/catalog_item.tpl", "tpl!apps/templates/catalog_empty.tpl", "tpl!apps/templates/product.tpl", "backbone.syphon"], 
	function(Ecommerce, catalogTpl, catalogItemTpl, catalogEmptyTpl, productTpl){
  Ecommerce.module('CatalogApp.Show.View', function(View, Ecommerce, Backbone, Marionette, $, _){

	View.NoItem = Marionette.ItemView.extend({      

        template: catalogEmptyTpl,

        tagName: "div",

        className: "col-lg-12 col-md-12 col-sm-12 col-xs-12"

    });

	View.CatalogItem = Marionette.ItemView.extend({      

        template: catalogItemTpl,

        tagName: "div",

        className: "col-lg-4 col-md-6 col-sm-6 col-xs-12",

        events: {
	      'click a.cart_btn_1': 'addToCart',
	      'click a.info_btn': 'viewProduct'
	    },

	    addToCart: function(e){ 
	        e.preventDefault();
	        this.trigger("addtocart", this.model);
	    },

	    viewProduct: function(e){ 
	        e.preventDefault();
	        this.trigger("viewproduct", this.model);
	    }

    });

	View.Catalog = Marionette.CompositeView.extend({
	    template: catalogTpl,

	    itemView: View.CatalogItem,

      	emptyView: View.NoItem,

      	itemViewContainer: "#catitems",

	    events: {
	      'click button#searchbtn': 'filter',
	      'keyup #searchfil': 'trysearch',
	      'change #catlist > li > input': 'filter',
	      'change #sortfil': 'filter',
	      'change #qtyfil': 'filter',
	    },

	    onShow: function(){
	    	this.showCategories();
	    	this.showPagination();
          	require(["catalog"], function(){
          		require.undef('catalog');
          		require.undef('scripts');

            	require(["catalog", "scripts"], function(){});
        	});
        },

        initialize: function(){ 
	        this.collection = this.model.get('products');
	    },

	    onBeforeRender: function(){ 
	        this.collection = this.model.get('products');
	    },

	    showCategories: function(){ 
	        var ul = $('#catlist');
	    	ul.empty();
	    	
	    	this.model.get('categories').forEach(function(element, index){
	        	var tpl = $('<li>'+element.get('name')+'<input type="checkbox" name="category" class="icheckbox_minimal" id="cat'+element.get('category_id')+'" value="'+element.get('category_id')+'"></li>');
	        	tpl.appendTo(ul);
	        	//this.$el.find("#cat" + element['id']).prop('checked', true);
	        });
	    },

	    showPagination: function(){ 
	        var ul = $('#pagelist');
	    	ul.empty();
	    	var rescount = this.model.get('resultcount');
	    	var qty = this.model.get('qty');
	    	var page = this.model.get('page');
	    	var pages;

	    	if (rescount % qty == 0) {
	    		pages = rescount/qty;
	    	}else{
	    		pages = rescount/qty + 1;
	    	}

	    	for (var i = 1; i <= pages; i++) {
	    		var tpl;
	    		if (page == i) {
	    			tpl = $('<li class="active"><a href="#">'+i+'</a></li>');
	    			tpl.appendTo(ul);
	    		}else{
	    			tpl = $('<li><a href="#">'+i+'</a></li>');
	    			tpl.appendTo(ul);
	    		}	    		
	    		
	    	};
	    },

	    trysearch: function(e){ 
	        e.preventDefault();
	        if (e.which == 13) {
	        	this.filter(e);
	        };
	    },

	    filter: function(e){ 
	    	e.preventDefault();
	        var params = {}
	        var cats = [];
	        params['name'] = $('#searchfil').val();
	        params['page'] = 1;
	        params['qty'] = $('#qtyfil').val();
	        params['sort'] = $('#sortfil').val();
	        params['cats'] = Array();
	        $('#catlist > li > div.checked > input').each(function(){
	        	params['cats'].push($(this).val());
	        });
	        params['maincat'] = this.model.get('maincat');//gents(1) - ladies(2) - kids(3)
	        params['max'] = $('#pend').val();
	        params['min'] = $('#pstart').val();
	        this.trigger("filter", params);
	    },

	    onFormReload: function(e){
			this.showCategories();
	    	this.showPagination();
	    	$('#searchfil').val(this.model.get('name'));
	        //params['page'] = 1;
	        $('#qtyfil option[value='+this.model.get('qty')+']').attr('selected', 'selected');
	        $('#sortfil option[value='+this.model.get('sort')+']').attr('selected', 'selected');
	        for (var i = 0; i < this.model.get('cats').length; i++) {
	        	$('#catlist').find("#cat" + this.model.get('cats')[i]).prop('checked', true);
	        };
	        //params['categories'].push(this.model.get('maincat'));//gents(1) - ladies(2) - kids(3)
	        $('#pend').val(this.model.get('max'));
	        $('#pstart').val(this.model.get('min'));

	        require.undef('catalog');
          	require.undef('scripts');
            require(["catalog", "scripts"], function(){});
	    }
	});

	View.Product = Marionette.ItemView.extend({
	    template: productTpl,

	    events: {
	      'click button.csubmit': 'submitClicked'
	    },

	    onShow: function(){

          	require(["catalog"], function(){
          		require.undef('scripts');
            	require(["scripts"], function(){});
        	});     
           
        },

        addToCart: function(e){ 
	        e.preventDefault();
	        this.trigger("addtocart", this.model);
	    },

	    submitClicked: function(e){
	        e.preventDefault();
	        var data = Backbone.Syphon.serialize(this);
	        alert(data);
	        this.trigger("form:submit", data);
	    }
	});

	/*View.CatalogForm = Marionette.ItemView.extend({
	    template: catalogTpl,

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
	          var $controlGroup = $view.find("#Catalog-" + key).parent();
	          var $errorEl = $('<span>', { class: "help-inline error", text: value });
	          $controlGroup.append($errorEl).addClass("error");
	        }

	        clearFormErrors();
	        _.each(errors, markErrors);
	      }-*-/
	});

	View.CatalogPage = Marionette.ItemView.extend({

	      template: catalogTpl,

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
  return Ecommerce.CatalogApp.Show.View;
});
