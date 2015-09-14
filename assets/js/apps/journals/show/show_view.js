define(["app", "tpl!apps/journals/show/templates/message.tpl", "tpl!apps/journals/show/templates/journals.tpl", 
	"tpl!apps/journals/show/templates/journal_item.tpl", "tpl!apps/journals/show/templates/article_item.tpl",
  "tpl!apps/journals/show/templates/issue.tpl"], function(Ecommerce, messageTpl, journalsTpl, journalItemTpl, articleItemTpl, issueTpl){
  Ecommerce.module('JournalsApp.Show.View', function(View, Ecommerce, Backbone, Marionette, $, _){
    
    View.Message = Marionette.ItemView.extend({
      template: messageTpl 
    });

    View.JournalItem = Marionette.ItemView.extend({      

      	template: journalItemTpl,

      	tagName: "li",

      	className: "ajournal",   	

      	events: {
        	"click #btn-read": "itemClicked"
      	},

      	itemClicked: function(e) {
        	e.preventDefault();
        	e.stopPropagation();
        	this.trigger("read", this.model);
      	}
    });

    View.Journals = Marionette.CompositeView.extend({

      template: journalsTpl,

      itemView: View.JournalItem,

      itemViewContainer: "ul",

      events: {
      },

      initialize: function(){ },

      onRender: function(){ },

      onShow: function(){ },

      viewNode: function (e) {
        e.preventDefault();
        e.stopPropagation();
      }
    });

    View.ArticleItem = Marionette.ItemView.extend({      

        template: articleItemTpl,

        tagName: "li",

        className: "anarticle"

    });

    View.Issue = Marionette.CompositeView.extend({

      template: issueTpl,

      itemView: View.ArticleItem,

      itemViewContainer: "ul#article-roll",

      events: {
        "click .issueslink": "viewJournals",
      },

      initialize: function(){  
        this.collection = this.model.get('articles');
      },

      viewJournals: function (e) {
        e.preventDefault();
        e.stopPropagation();
        this.trigger("navjournals");
      },

      onRender: function(){  },

      onShow: function(){  }
    });

  });

  return Ecommerce.JournalsApp.Show.View;
});
 