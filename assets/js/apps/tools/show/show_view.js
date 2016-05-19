define(["app", "tpl!apps/templates/users.tpl", "tpl!apps/templates/roles.tpl", "tpl!apps/templates/password.tpl", "backbone.syphon"], 
	function(System, usersTpl, rolesTpl, passwordTpl){
  System.module('ToolsApp.Show.View', function(View, System, Backbone, Marionette, $, _){   

    View.Users = Marionette.ItemView.extend({      

        template: usersTpl,

        events: {
          "click .ucreate": "createUser",
          "click .usave": "modifyUser",
          "click .udel": "deleteUser",
          "change #users": "setCredentials"
        },

        onShow: function(){                  
          $('.loading').hide();
          this.setup();
          this['users'] = {};
        },

        setup: function(){
          var THAT = this;
          var ul = $('#employees');
          ul.empty();
          var ula = $('#users');
          ula.empty();

          var ulb = $('#roles');
          ulb.empty();
          var ulc = $('#uroles');
          ulc.empty();
          $.get(System.coreRoot + '/service/hrm/index.php?employees', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select Employee...</option>');
            tp.appendTo(ul);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpl.appendTo(ul);              
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          $.get(System.coreRoot + '/service/tools/index.php?users', function(result) {
            var m = JSON.parse(result);
            var tpa = $('<option data-icon="fa fa-user">Select User...</option>');
            tpa.appendTo(ula);
            
            m.forEach(function(elem){
              var tpla = $('<option data-icon="fa fa-user" value="'+parseInt(elem['id'], 10)+'">'+elem['record']['name']+'</option>');
              tpla.appendTo(ula);
              THAT['users'][parseInt(elem['id'], 10)] = elem;
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          $.get(System.coreRoot + '/service/tools/index.php?roles', function(result) {
            var m = JSON.parse(result);
            var tpa = $('<option data-icon="fa fa-briefcase">Select Role...</option>');
            tpa.appendTo(ulb);
            var tpb = $('<option data-icon="fa fa-briefcase">Select Role...</option>');
            tpb.appendTo(ulc);
            
            m.forEach(function(elem){
              var tpla = $('<option data-icon="fa fa-briefcase" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpla.appendTo(ulb);
              var tplb = $('<option data-icon="fa fa-briefcase" value="'+elem['id']+'">'+elem['name']+'</option>');
              tplb.appendTo(ulc);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          $('form input').val('');
        },

        setCredentials: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmu2")[0]);
          if (data['user']) {
            var obj = this['users'][data['user']];
            //alert(JSON.stringify(obj));
            $('#uname').val(obj['username']);
            $('#uroles option[value="'+obj['role']['id']+'"]').prop('selected', true);
            $('#uaccess option[value="'+obj['access']+'"]').prop('selected', true);

            setTimeout(function() {
              $('.selectpicker').selectpicker('refresh');
            }, 300);

          }else{
            swal("Error!", "Select a user first!", "error");
          }
        },

        createUser: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmu1")[0]);
          data['empid'] = parseInt(data['employee'], 10);

          if (data['empid'] && data['uname'] && data['pass'] && data['role']) {
            this.trigger("create", data);
          }else{
            swal("Error!", "Enter All Details!", "error");
          }
        },

        modifyUser: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmu2")[0]);
          data['empid'] = data['user'];
          //alert(JSON.stringify(data));
          if (data['empid'] && data['uname2'] && data['role2'] && (data['access'] || 1)) {
            //alert(JSON.stringify(data));
            this.trigger("modify", data);
          }else{
            swal("Error!", "Enter All Details!", "error");
          }
        },

        deleteUser: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmu2")[0]);
          data['id'] = parseInt(data['user'], 10);

          if (data['id']) {
            //alert(JSON.stringify(data));
            this.trigger("erase", data);
          }else{
            swal("Error!", "Enter All Details!", "error");
          }
        },

        onSuccess: function(voucher) { 
          swal("Success!", "The user has been saved.", "success");
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "User record could not be saved! Try again.", "error");
        }
    });

    View.Roles = Marionette.ItemView.extend({      

        template: rolesTpl,

        events: {
          "click .rcreate": "createRole",
          "click .rsave": "modifyRole",
          "click .rdel": "deleteRole",
          "click .reset": "resetList",
          "change #roles": "setViews"
        },

        onShow: function(){                  
          $('.loading').hide();
          this.setup();
          this['roles'] = [];
        },

        setup: function(){
          var THAT = this;

          var ul = $('#roles');
          ul.empty();

          $.get(System.coreRoot + '/service/tools/index.php?roles', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-briefcase">Select Role...</option>');
            tp.appendTo(ul);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-briefcase" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpl.appendTo(ul);

              THAT['roles'][elem['id']] = elem;
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          var ulx = $('#ux');
          ulx.empty();

          $.get(System.coreRoot + '/service/tools/index.php?modules', function(result) {
            var res = JSON.parse(result);
            var tp = $('<thead><tr><td>Interface</td><td>Access</td></tr></thead>');
            tp.appendTo(ulx);

            res.forEach(function(module){
              var tpl = $('<tr class="success"><td colspan="2" class="text-left" style="font-size:12px;text-transform:uppercase;font-weight:bolder;padding:5px 15px;">'+module['name']+'</td></tr>');
              tpl.appendTo(ulx);
              var views = module['views'];
              views.forEach(function(view) {
                var tplb = $('<tr><td>'+view['name']+'</td><td><div class="checkbox checkbox-primary" style="margin:0"><input id="'+view['id']+'" name="view" type="checkbox"><label for="'+view['id']+'"></label></div></td></tr>');
                tplb.appendTo(ulx);
              });
            });
            
          });


          $('form input').val('');
        },

        resetList: function() { 
          $('#ux :checkbox').each(function(i,item){
            $(item).prop('checked', false);
          });
        },
      
        setViews: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmr2")[0]);
          data['role2'] = parseInt(data['role2'], 10);
          
          if (data['role2']) {

            this.resetList();
            $('#role2').prop('rid', this['roles'][data['role2']]['id']);
            $('#role2').val(this['roles'][data['role2']]['name']);

            var mod =  this['roles'][data['role2']]['presentation'];

            setTimeout(function() {
              $.each(mod, function(i, module){
                var views = module['views'];
                views.forEach(function(view) {
                  $('#'+view['id']).prop('checked', true);
                });
              });
            }, 300);

          }else{
            swal("Error!", "Select a role first!", "error");
          }
        },

        createRole: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = {};
          var views = [];
          $("#frmr3").find('input[name=view]:checked').each(function (chkbx) {
            views.push($(this).attr('id'));
          });
          data['views'] = views;
          data['name'] = $('#role').val();
          if (data['name'] && views.length > 0) {
            //alert(JSON.stringify(data));
            this.trigger("create", data);
          }else{
            swal("Error!", "Enter All Details!", "error");
          }
        },

        modifyRole: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = {};
          var views = [];
          $("#frmr3").find('input[name=view]:checked').each(function (chkbx) {
            views.push($(this).attr('id'));
          });
          data['views'] = views;
          data['name'] = $('#role2').val();
          data['id'] = $('#role2').prop('rid');
          if (data['id'] && data['name'] && views.length > 0) {
            //alert(JSON.stringify(data));
            this.trigger("modify", data);
          }else{
            swal("Error!", "Enter All Details!", "error");
          }
        },

        deleteRole: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmr2")[0]);
          data['id'] = parseInt(data['role2'], 10);

          if (data['id']) {
            //alert(JSON.stringify(data));
            this.trigger("erase", data);
          }else{
            swal("Error!", "Enter All Details!", "error");
          }
        },

        onSuccess: function() {
          swal("Success!", "The role has been saved.", "success");
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "Role could not be saved! Try again later.", "error");
          this.setup();
        }
    });

    View.ChangePassword = Marionette.ItemView.extend({      

        template: passwordTpl,

        events: {
          "click .usave": "changePassword"
        },

        onShow: function(){                  
          $('.loading').hide();
          this.setup();
        },

        setup: function(){          
          $('form input').val('');
        },
      
        changePassword: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          
          if (data['opass'] && data['npass'] && data['cpass']) {
            //alert(JSON.stringify(data));
            if (data['npass'] == data['cpass']) {
              this.trigger("submit", data);
            }else{
            swal("Error!", "Enter ensure you enter matching passwords!", "error");
          }
            
          }else{
            swal("Error!", "Enter all fields!", "error");
          }
        },

        onSuccess: function(voucher) { 
          swal("Success!", "The password has been changed.", "success");
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "Password could not be changed! Please, try again.", "error");
        }
    });
  });

  return System.ToolsApp.Show.View;
});
