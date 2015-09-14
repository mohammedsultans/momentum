define(["app", "apps/dash/show/show_view"], function(System, View){
  System.module('DashApp.Show', function(Show, System, Backbone, Marionette, $, _){
    Show.Controller = {
      showDash: function(){ 
        var view = new View.Dash();
        System.contentRegion.show(view);
        /*require(["apps/entities/inventory"], function(){
          $.when(System.request("product:featured")).done(function(response){
            //alert(JSON.stringify(response.length));
            var view = new View.Slides({ collection: response });
            layout.slidesRegion.show(view); 
          });

          $.when(System.request("product:latest")).done(function(response){
            //alert(JSON.stringify(response.length));
            var view = new View.Latest({ collection: response });
            layout.latestRegion.show(view); 
          });
        }); 

              

        require(["entities/lightning"], function(){
          $.when(System.request("twitter:entities")).done(function(content){
            if(content !== undefined){
              var tweets = new View.Tweets({ collection: content });          
              layout.tweetsRegion.show(tweets);
            }
          });
        });

        var newsletter = new View.Newsletter();
        layout.newsletterRegion.show(newsletter);

        newsletter.on('subscribe', function(data) {
          data['operation'] = 'newsletter';
          $.post('/ecomadmin/presentation/users/index.php', data, function(res) {
            if (res == 1) {
                newsletter.triggerMethod("form:clear");
            }else{
                newsletter.triggerMethod("subscribe:error");
            }
          });
	      });*/
	    }
    };
  });

  return System.DashApp.Show.Controller;
});
