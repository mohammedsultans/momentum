define(["app", "tpl!apps/templates/dash.tpl"], 
	function(System, dashTpl){
  System.module('DashApp.Show.View', function(View, System, Backbone, Marionette, $, _){
    
    View.Dash = Marionette.ItemView.extend({      

        template: dashTpl,

        events: {
          "click .ioptions #btn-edit": "editIssue",
        },

        onShow: function(){
          $("#dashcont").unwrap();
          require.undef('dash');
          require(["dash", "graphs"], function(){
            var optionss = {
              scaleColor: false,
              trackColor: 'rgba(0, 0, 0, 0.2)',
              barColor: '#399BFF',
              lineWidth: 6,
              lineCap: 'butt',
            }; 

            var charts = [];
            [].forEach.call(document.querySelectorAll('.easypie'),  function(el) {
              charts.push(new EasyPieChart(el, optionss));
            });
            $('.loading').hide();
          });
        },

        onDomRefresh: function(){          
          require.undef('pieplug');
          require(["pieplug"], function(){});
        },

        editIssue: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          this.trigger("edit", this.model);
          //alert("Head to latest article");
          //this.trigger("edit:division", this);
        }
    });

  });

  return System.DashApp.Show.View;
});
