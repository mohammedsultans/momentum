define(["app", "tpl!apps/templates/register.tpl", "tpl!apps/templates/enquiry.tpl", "tpl!apps/templates/waiting.tpl", 
  "tpl!apps/templates/addcontactlead.tpl", "tpl!apps/templates/searchhead.tpl", "tpl!apps/templates/contact.tpl", 
  "tpl!apps/templates/contacts.tpl", "tpl!apps/templates/customer.tpl", "backbone.syphon"], 
	function(System, registerTpl, enquiryTpl, waitingTpl, addcontactleadTpl, searchheadTpl, contactTpl, contactsTpl, customerTpl){
  System.module('ClientsApp.Show.View', function(View, System, Backbone, Marionette, $, _){
    
    View.Clients = Marionette.CompositeView.extend({

      template: registerTpl,

      onShow: function(){
        var THAT = this;
          require(["money"], function(){
            THAT.setup();
          });
      },

      setup: function(){
          var THAT = this;
          var ul = $('tbody');
          ul.empty();
          $.get(System.coreRoot + '/service/crm/index.php?clients', function(result) {
            var m = JSON.parse(result);
            m.forEach(function(elem){
              var tpl = $('<tr><td>'+elem['name']+'<span style="font-size: 10px"> ['+elem['details']+']</span></td><td>'+elem['telephone']+'</td><td>Ksh. '+(elem['balance']['amount']).formatMoney(2, '.', ',')+'</td>'
                +'<td><p class="xid" style="display: none;">'+elem['id']+'</p><a class="btn btn-small js-edit xcheck" href="#"><i class="icon-pencil"></i>Delete</a></td></tr>');
              tpl.appendTo(ul);
            });

            $('.xcheck').on('click', function(e){
              e.preventDefault();
              e.stopPropagation();
              var data = {};
              data['id'] = parseInt($(this).parent().find('.xid').text());
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
                  THAT.deleteRecord(data);
                  alert(data.id);  
                } else {
                  swal("Cancelled", "Your record is safe :)", "error");
                }
              });
              
            });

            setTimeout(function() {
              $('#example0').DataTable();
              $('button').prop({disabled: false});
            }, 700);
            
          });
        },

        deleteRecord: function(data) { 
          //alert(JSON.stringify(data));
          $('button').prop({disabled: true});
          this.trigger("del", data);
        },

        onDelete: function(e) { 
          swal("Deleted!", "Your record has been deleted.", "success");
          this.setup();
          //alert(JSON.stringify(data));
          //this.trigger("create", data);
        },

        onError: function(e) { 
          swal("Error!", "Transaction failed! Try again later.", "error");
          $('button').prop({disabled: false});
          //alert(JSON.stringify(data));
          //this.trigger("create", data);
        }

    });

    View.Client = Marionette.ItemView.extend({      

        template: customerTpl,

        events: {
          "click .nsave": "addCustomer",
          "click .esave": "editCustomer",
          "click .edelete": "deleteCustomer",
          "change .selectpicker": "getClient"
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
          var ul = $('#clients');
          ul.empty();
          $.get(System.coreRoot + '/service/crm/index.php?clients', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-institution">Select Customer...</option>');
            tp.appendTo(ul);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-institution" value="'+elem['id']+'">'+elem['name']+'<span style="font-size: 1px"> ['+elem['details']+']</span></option>');
              tpl.appendTo(ul);
            });
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });
        },

        addCustomer: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          //alert(JSON.stringify(data));
          //swal("Success!", "The record has been created.", "success");
          this.trigger("create", data);
        },

        editCustomer: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = {};
          data['id'] = parseInt($('.selectpicker').find("option:selected").val());
          data['name'] = $('#ename').val();
          data['tel'] = $('#etel').val();
          data['email'] = $('#eemail').val();
          data['address'] = $('#eadd').val();
          data['details'] = $('#edetail').val();
          //alert(JSON.stringify(data));
          //swal("Success!", "The record has been created.", "success");
          this.trigger("edit", data);
        },

        deleteCustomer: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = {};
          data['id'] = parseInt($('.selectpicker').find("option:selected").val());
          data['operation'] = 'deleteClient';
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

                  $.post(System.coreRoot + '/service/crm/index.php', data, function(result) {
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
                  swal("Cancelled", "Your record is safe :)", "info");
                }
              });
          
        },

        getClient: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          
          var id = parseInt($('.selectpicker').find("option:selected").val());
          $.get(System.coreRoot + '/service/crm/index.php?client&clientid='+id, function(result) {
            var m = JSON.parse(result);
            $('#ename').val(m['name']);
            $('#etel').val(m['telephone']);
            $('#eemail').val(m['email']);
            $('#eadd').val(m['address']);
            $('#edetail').val(m['details']);
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

    View.EditClient = Marionette.ItemView.extend({      

        template: customerTpl,

        events: {
          "click .btnsub": "addCustomer",
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          $('.selectpicker').selectpicker();
        },

        addCustomer: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          //alert(JSON.stringify(data));
          this.trigger("create", data);
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
          $.get(System.coreRoot + '/service/crm/index.php?pending', function(result) {
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

    View.SearchHeader = Marionette.ItemView.extend({      

        template: searchheadTpl,

        events: {
          "click #search": "search",
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          //$('.selectpicker').selectpicker();
        },

        search: function(e) { 
          e.preventDefault();
          e.stopPropagation();
        },

        onError: function (er){
          alert('contact search error');
        }
    });

    View.Contact = Marionette.ItemView.extend({      

        template: contactTpl,

        tagName: "li",

        events: {
          "click #btn-read": "itemClicked"
        },

        itemClicked: function(e) {
          e.preventDefault();
          e.stopPropagation();
          this.trigger("submit", this.model);
        }
    });

    View.Contacts = Marionette.CompositeView.extend({

      className: "col-md-12",

      template: contactsTpl,

      itemView: View.Contact,

      itemViewContainer: "ul"
    });

  });

  return System.ClientsApp.Show.View;
});