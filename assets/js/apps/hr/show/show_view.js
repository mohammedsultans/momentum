define(["app", "tpl!apps/templates/employees.tpl", "tpl!apps/templates/enquiry.tpl", "tpl!apps/templates/waiting.tpl",  "tpl!apps/templates/employee.tpl", "backbone.syphon"], 
	function(System, employeesTpl, enquiryTpl, waitingTpl, employeeTpl){
  System.module('HRApp.Show.View', function(View, System, Backbone, Marionette, $, _){
    
    View.Employees = Marionette.CompositeView.extend({

      template: employeesTpl,

      onShow: function(){
        this.setup();
      },

      setup: function(){
          var THAT = this;
          var ul = $('tbody');
          ul.empty();
          $.get(System.coreRoot + '/service/hrm/index.php?employees', function(result) {
            var m = JSON.parse(result);
            m.forEach(function(elem){
              var tpl = $('<tr><td>'+elem['name']+'</td><td>'+elem['telephone']+'</td><td>'+elem['email']+'</td><td>'+elem['department']+'</td><td>'+elem['position']+'</td>'
                +'<td><p class="xid" style="display: none;">'+elem['id']+'</p><a class="btn btn-small js-edit xcheck" href="#"><i class="fa fa-trash"></i></a></td></tr>');
              tpl.appendTo(ul);
            });

            $('.xcheck').on('click', function(e){
              e.preventDefault();
              e.stopPropagation();
              var id = $(this).parent().find('.xid');
              id = parseInt(id.text());
              swal({
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
              },
              function(isConfirm){
                if (isConfirm) {
                  THAT.deleteRecord(id);               
                } else {
                  swal("Cancelled", "Your record is safe :)", "error");
                }
              });
              
            });
            
          });
        },

        deleteRecord: function(id) {
          this.trigger("del", id);
        },

        onDelete: function(e) { 
          swal("Deleted!", "Your record has been deleted.", "success");
          this.setup();
          //alert(JSON.stringify(data));
          //this.trigger("create", data);
        },

        onError: function(e) { 
          swal("Error!", "Transaction failed! Try again later.", "error");
          //alert(JSON.stringify(data));
          //this.trigger("create", data);
        }

    });

    View.Employee = Marionette.ItemView.extend({      

        template: employeeTpl,

        events: {
          "click .nsave": "addEmployee",
          "click .esave": "editEmployee",
          "click .edelete": "deleteEmployee",
          "change .selectpicker": "getEmployee"
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          require(["sweetalert"], function(){
              require.undef('sweetalert');
              require(["sweetalert"], function(){});
          });
         
          this.setup();
        },

        setup: function(){
          var ul = $('#employees');
          ul.empty();
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
        },

        addEmployee: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          //alert(JSON.stringify(data));
          //swal("Success!", "The record has been created.", "success");
          this.trigger("create", data);
        },

        editEmployee: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = {};
          data['id'] = parseInt($('#employees').find("option:selected").val());
          data['name'] = $('#ename').val();
          data['tel'] = $('#etel').val();
          data['email'] = $('#eemail').val();
          data['address'] = $('#eadd').val();
          data['gender'] = $('#egender').val();
          data['department'] = $('#edept').val();
          data['position'] = $('#epos').val();
          data['salary'] = $('#esalary').val();
          //alert(JSON.stringify(data));
          //swal("Success!", "The record has been created.", "success");
          this.trigger("edit", data);
        },

        deleteEmployee: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = {};
          data['id'] = parseInt($('#employees').find("option:selected").val());
          data['operation'] = 'deleteEmployee';
          var THAT = this;
          swal({
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
              },
              function(isConfirm){
                if (isConfirm) {
                  //THAT.trigger("delete", data);

                  $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
                    if (result == 1) {
                      swal("Deleted!", "Your record has been deleted.", "success"); 
                      $('input').val('');
                      $('textarea').val('');
                      THAT.setup();
                    }else{
                      swal("Error!", "Transaction failed! Try again later.", "error");
                    }
                  });
                  
                             
                } else {
                  swal("Cancelled", "Your record is safe :)", "error");
                }
              });
          
        },

        getEmployee: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          
          var id = parseInt($('#employees').find("option:selected").val());
          $.get(System.coreRoot + '/service/hrm/index.php?employee&empid='+id, function(result) {
            var m = JSON.parse(result);
            $('#ename').val(m['name']);
            $('#etel').val(m['telephone']);
            $('#eemail').val(m['email']);
            $('#eadd').val(m['address']);
            $('#edept').val(m['department']);
            $('#epos').val(m['position']);
            $('#egender option[value="'+m['gender']+'"]').prop('selected', true);
            $('select[name=gender2]').val(m['gender']);
            $('#esalary').val(m['salary']);

            setTimeout(function() {
              $('.selectpicker').selectpicker('refresh');
            }, 150);

          });
          //swal("Success!", "The record has been created.", "success");
          //this.trigger("delete", data);
        },

        onDelete: function(e) { 
          swal("Deleted!", "Your record has been deleted.", "success");
          $('input').val('');
          $('textarea').val('');
          this.setup();
          //alert(JSON.stringify(data));
          //this.trigger("create", data);
        },

        onSuccess: function(e) { 
          swal("Success!", "The record has been saved.", "success");
          $('input').val('');
          $('textarea').val('');
          this.setup();
          //alert(JSON.stringify(data));
          //this.trigger("create", data);
        },

        onError: function(e) { 
          swal("Error!", "Transaction failed! Try again later.", "error");
          //alert(JSON.stringify(data));
          //this.trigger("create", data);
        }
    });

    View.Enquiry = Marionette.ItemView.extend({      

        template: enquiryTpl,

        events: {
          "click .nsave": "postQuery",
        },

        onShow: function(){
          $('.loading').hide();
          this.setup();
        },

        setup: function(){
          var uls = $('#services');
          uls.empty();
          
          $.get(System.coreRoot + '/service/operations/index.php?services', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-question-circle" class="defserve">Select One...</option>');
            tp.appendTo(uls);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-question-circle" value="'+elem['name']+'">'+elem['name']+'</option>');
              tpl.appendTo(uls);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });
        },

        postQuery: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          //alert(JSON.stringify(data));
          //swal("Thank you!", "Query has been posted. Please wait to see consultant.", "success");
          this.trigger("create", data);
        },

        onSuccess: function(e) { 
          swal("Success!", "The record has been created.", "success");
          $('input').val('');
          $('textarea').val('');
          this.setup();
          //alert(JSON.stringify(data));
          //this.trigger("create", data);
        },

        onError: function(e) { 
          swal("Error!", "Transaction failed! Try again later.", "error");
          //alert(JSON.stringify(data));
          //this.trigger("create", data);
        }
    });

    View.PendingQueries = Marionette.ItemView.extend({      

        template: waitingTpl,

        events: {
          "click .xcheck": "postQuery",
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          this.setup();

        },

        setup: function(){
          var THAT = this;
          var ul = $('tbody');
          ul.empty();
          $.get(System.coreRoot + '/service/hrm/index.php?pending', function(result) {
            var m = JSON.parse(result);
            
            m.forEach(function(elem){
              var tpl = $('<tr><td>'+elem['name']+'</td><td>'+elem['tel']+'</td><td>'+elem['services']+'</td><td>'+elem['details']+'</td><td>'+elem['date']+'</td>'
                +'<td><p class="xstamp" style="display: none;">'+elem['stamp']+'</p><a class="btn btn-small js-edit xcheck" href="#"><i class="icon-pencil"></i>Check</a></td></tr>');
              tpl.appendTo(ul);
            });

            $('.xcheck').on('click', function(e){
              e.preventDefault();
              e.stopPropagation();
              var stamp = $(this).parent().find('.xstamp');
              stamp = parseInt(stamp.text());
              THAT.trigger("check", stamp);
            });
            
          });
        },

        postQuery: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          //alert(JSON.stringify(data));
          swal("Thank you!", "Query has been posted. Please wait to see consultant.", "success");
          //this.trigger("create", data);
        },

        onSuccess: function(e) { 
          swal("Success!", "The entry has been checked off from your list.", "success");
          this.setup();
          //alert(JSON.stringify(data));
          //this.trigger("create", data);
        },

        onError: function(e) { 
          swal("Error!", "Transaction failed! Try again later.", "error");
          //alert(JSON.stringify(data));
          //this.trigger("create", data);
        }
    });

  });

  return System.HRApp.Show.View;
});