define(["app", "tpl!apps/templates/menu.tpl", "tpl!apps/templates/empty.tpl"], 
	function(System, menuTpl, emptyTpl){
  System.module('MenuApp.Show.View', function(View, System, Backbone, Marionette, $, _){
    
    View.Menu = Marionette.ItemView.extend({      

        template: menuTpl,

        events: {
          "click .ioptions #btn-edit": "editIssue",
        },

        onShow: function(){
          $("#top").unwrap();
          var THAT = this;
          require.undef('plugins');
          require.undef('basics');
          require(["basics", "plugins"], function(){
            THAT.setup();
          });
        },

        onDomRefresh: function(){
          require.undef('plugins');
          require(["plugins"], function(){});
        },

        setup: function() { 
          var dom = $('#presentation');
          dom.empty();
          var data = this.model.get('user');
          System.role = data.role;
          System.username = data.record.name;
          System.interfaces = data.role.presentation;
          //System.user = data;
          //alert(JSON.stringify(System.views));          
          $.each(System.interfaces, function(i, module){
            var tpl = $('<li><a href="#"><span class="icon color11-bg"><i class="fa '+module['logo']+'"></i></span>'+module['name']+'<span class="caret"></span></a><ul id="mod'+i+'"><ul></li>');
            tpl.appendTo(dom);
            var views = module['views'];
            var idom = $('#mod'+i);
            views.forEach(function(view) {
              var itpl = $('<li><a href="#'+view['link']+'"><span class="icon"><i class="fa '+view['logo']+'"></i></span>'+view['name']+'</a></li>');
              itpl.appendTo(idom);
            });
          });
          $('#username').text(System.username);
          var THAT = this;
          setTimeout(function() {
            THAT.activateMenu();
          }, 300);
        },

        activateMenu: function(){
          var wide
          var width;
          var widthneg;
          //setup menu elements based on the user role

          $('#content').off('click');
          $('.sidebar-open-button').off('click');
          $('.sidebar-open-button-mobile').off('click');
          $('.nav > li > a').off('click');

          $('.nav > li > a').on('click', function(){
            if ($(this).attr('class') != 'active'){
              $('.nav li ul').slideUp();
              $(this).next().slideToggle();
              $('.nav li a').removeClass('active');
              $(this).addClass('active');
            }
          });

          $('#content').on('click', function(){
              wide = $(document).width();
              if (wide > 600) { width = 250; widthneg = -250;}else{width = '50%'; widthneg = '-50%';}
              if($('.sidebar').hasClass('hidden')){
                 
              }else{
                  $('.sidebar').addClass('hidden');
                  $('.content.main').animate({
                      'marginLeft' : 0
                  }, 250);    
              }
          });
          
          $('.sidebar-open-button').on('click', function(){
              wide = $(document).width();
              if (wide > 600) { width = 250; widthneg = -250;}else{width = '50%'; widthneg = '-50%';}
              if($('.sidebar').hasClass('hidden')){
                  $('.sidebar').removeClass('hidden');
                  $('.content.main').animate({
                      'marginLeft' : width
                  }, 250);  //if window.width > 700px, margin = 250px, otherwise: 50%
              }else{
                  $('.sidebar').addClass('hidden');
                  $('.content.main').animate({
                      'marginLeft' : 0
                  }, 250);    
              }
          });

          $('.sidebar-open-button-mobile').on('click', function(){
              wide = $(document).width();
              if (wide > 600) { width = 250; widthneg = -250;}else{width = '50%'; widthneg = '-50%';}
              if($('.sidebar').hasClass('hidden')){
                  $('.sidebar').removeClass('hidden');
                  $('.content.main').animate({
                      'marginLeft' : width,
                  }, 250);  
              }else{
                  $('.sidebar').addClass('hidden');
                  $('.content.main').animate({
                      'marginLeft' : 0
                  }, 250);    
              }
          });

          setTimeout(function() {
            $('.loading').hide();
            if(System.getCurrentRoute() === "" || System.getCurrentRoute() === "logout" || System.getCurrentRoute() === "login"){
              $('ul#presentation > li:first-child > ul > li > a').first().get(0).click();
            }
            
          }, 300);
        },
    });

    View.Empty = Marionette.ItemView.extend({      

        template: emptyTpl

    });

  });

  return System.MenuApp.Show.View;
});
