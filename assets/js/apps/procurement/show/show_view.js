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
              var tpl = $('<tr><td>'+elem['name']+'</td><td>'+elem['person']+'</td><td>'+elem['telephone']+'</td><td>'+elem['email']+'</td><td>'+elem['address']+'</td><td>Ksh. '+elem['balance']['amount']+'</td>'
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
                  swal("Cancelled", "Your record is safe :)", "success");
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
          data['person'] = $('#eperson').val();
          data['tel'] = $('#etel').val();
          data['email'] = $('#eemail').val();
          data['address'] = $('#eadd').val();
          //alert(JSON.stringify(data));
          //swal("Success!", "The record has been created.", "success");
          this.trigger("edit", data);
        },

        deleteSupplier: function(e) { 
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
                  swal("Cancelled", "Your record is safe :)", "success");
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
            $('#eperson').val(m['person']);
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

  });

  return System.ProcurementApp.Show.View;
});