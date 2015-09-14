define(["app", "tpl!apps/templates/suppliers.tpl", "tpl!apps/templates/enquiry.tpl", "tpl!apps/templates/waiting.tpl", 
  "tpl!apps/templates/supplier.tpl", "backbone.syphon"], 
	function(System, suppliersTpl, enquiryTpl, waitingTpl, supplierTpl){
  System.module('ProcurementApp.Show.View', function(View, System, Backbone, Marionette, $, _){
    
    View.Suppliers = Marionette.CompositeView.extend({

      template: suppliersTpl,

      onShow: function(){
        this.setup();
      },

      setup: function(){
          var THAT = this;
          var ul = $('tbody');
          ul.empty();
          $.get(System.coreRoot + '/service/procurement/index.php?suppliers', function(result) {
            var m = JSON.parse(result);
            m.forEach(function(elem){
              var tpl = $('<tr><td>'+elem['name']+'</td><td>'+elem['telephone']+'</td><td>'+elem['email']+'</td><td>'+elem['address']+'</td><td>Ksh. '+elem['balance']['amount']+'</td>'
                +'<td><p class="xid" style="display: none;">'+elem['id']+'</p><a class="btn btn-small js-edit xcheck" href="#"><i class="fa fa-trash" style="margin:0;"></i></a></td></tr>');
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
          //alert(JSON.stringify(id));
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

    View.Supplier = Marionette.ItemView.extend({      

        template: supplierTpl,

        events: {
          "click .nsave": "addSupplier",
          "click .esave": "editSupplier",
          "click .edelete": "deleteSupplier",
          "change .selectpicker": "getSupplier"
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
          var ul = $('#suppliers');
          ul.empty();
          $.get(System.coreRoot + '/service/procurement/index.php?suppliers', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select Supplier...</option>');
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

        addSupplier: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          //alert(JSON.stringify(data));
          //swal("Success!", "The record has been created.", "success");
          this.trigger("create", data);
        },

        editSupplier: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = {};
          data['id'] = parseInt($('#suppliers').find("option:selected").val());
          data['name'] = $('#ename').val();
          data['tel'] = $('#etel').val();
          data['email'] = $('#eemail').val();
          data['address'] = $('#eadd').val();
          //alert(JSON.stringify(data));
          //swal("Success!", "The record has been created.", "success");
          this.trigger("edit", data);
        },

        deleteSupplierr: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = {};
          data['id'] = parseInt($('#suppliers').find("option:selected").val());
          data['operation'] = 'deleteSupplier';
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

                  $.post(System.coreRoot + '/service/procurement/index.php', data, function(result) {
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

        getSupplier: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          
          var id = parseInt($('.selectpicker').find("option:selected").val());
          $.get(System.coreRoot + '/service/procurement/index.php?supplier&supplierid='+id, function(result) {
            var m = JSON.parse(result);
            $('#ename').val(m['name']);
            $('#etel').val(m['telephone']);
            $('#eemail').val(m['email']);
            $('#eadd').val(m['address']);
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

    View.EditSupplier = Marionette.ItemView.extend({      

        template: supplierTpl,

        events: {
          "click .btnsub": "addSupplier",
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          $('.selectpicker').selectpicker();
        },

        addSupplier: function(e) { 
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
          $.get(System.coreRoot + '/service/procurement/index.php?pending', function(result) {
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

  return System.ProcurementApp.Show.View;
});