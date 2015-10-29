define(["app", "tpl!apps/templates/supplier.tpl", "tpl!apps/templates/suppliers.tpl", "tpl!apps/templates/goodsreceived.tpl", 
  "tpl!apps/templates/paysupplier.tpl", "tpl!apps/templates/suppliertx.tpl", "backbone.syphon"], 
	function(System, supplierTpl, suppliersTpl, grnTpl, paySupplierTpl, supplierTxTpl){
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

    View.PurchaseOrder = Marionette.ItemView.extend({      

        template: grnTpl,

        events: {
          //"change #clients": "fetchProjects",
          "click .iadd": "addToGRN",
          "click .idiscard": "discardGRN",
          "click .igenerate": "generateGRN",
          "keyup #disc": "discountGRN"
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          $('.loading').hide();
          //this.setup();

          this['grnitems'] = [];
          this['tax'] = 0;
          this['disc'] = 0;
          this['totalamt'] = 0;
        },

        setup: function(){
          var ul = $('#suppliers');
          ul.empty();
          var uls = $('#ledgers');
          uls.empty();
          $.get(System.coreRoot + '/service/crm/index.php?suppliers', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select Supplier...</option>');
            tp.appendTo(ul);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem['name']+'<span style="font-size: 1px"> ['+elem['details']+']</span></option>');
              tpl.appendTo(ul);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          $.get(System.coreRoot + '/service/finance/index.php?purchaseLedgers', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-question-circle" value="0">Select Ledger...</option>');
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
          $('#disc').val(0);
          $('#total').val('');
          $('#taxes').val('');
          $('#amount').val('');
          var ulx = $('tbody');
          ulx.empty();

          $('#date-range').daterangepicker({ singleDatePicker: true }, function(start, end, label) {});
        },

        addToGRN: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          //var data = Backbone.Syphon.serialize($("#frmq1")[0]);
          var data = Backbone.Syphon.serialize($("#frmi2")[0]);
          //_.extend(data, data2);
          //alert(JSON.stringify(data));
          var ar = [];
          ar = this['invitems'];
          ar.push(data);
          this['invitems'] = ar;

          var ul = $('tbody');

          var total = parseInt(data['qty']) * parseFloat(data['price']);
          this['totalamt'] += parseFloat(total);
          this['tax'] += parseFloat(total * parseInt(data['tax'])/100);

          $('#taxes').val(this['tax']);
          $('#amount').val(this['totalamt']);
          $('#total').val(this['totalamt'] + this['tax']);

          var tpl = $('<tr><td>'+data['service']+'<br><span style="font-style:italic; font-size:11px">'+data['task']+'</span></td>'+
                      '<td>'+data['price']+'</td><td>'+data['qty']+'</td><td>Ksh. '+total+'</td></tr>');

          tpl.appendTo(ul);
          $('#services option[value="0"]').prop('selected', true);

          setTimeout(function (){
            $("#frmi2").find('input').val('');            
            $('.selectpicker').selectpicker('refresh');
          }, 150);
        },

        isInt: function (value){
          return !isNaN(value) && (function(x) { return (x | 0) === x; })(parseInt(value, 10));
        },

        discountGRN: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var disc = parseFloat($('#disc').val()) || 0;
          var tot = parseFloat(this['totalamt']+this['tax']) * (100 - disc)/100;
          $('#total').val(tot); 
          //Open printable quote in separate window
        },

        generateGRN: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmi1")[0]);
          var disc = Backbone.Syphon.serialize($("#frmi3")[0]);

          data['client'] = parseInt(data['client'], 10);
          if (disc['discount'] == "") {
              disc['discount'] = 0;
          };

          var items = this['invitems'];

          if (data['client'] && (parseInt(data['scope'], 10) || data['scope'] == 'G') && items.length > 0) {
            data['items'] = items;
            data['discount'] = disc['discount'];
            //alert(JSON.stringify(data));
            this.trigger("post", data);
          }else{
            swal("Missing Details!", "Ensure you have entered all supplier, scope and GRN items!", "warning");
          }
        },

        onSuccess: function(voucher) { 

          swal("Success!", "The invoice has been posted.", "success");
          //window.open("report.php?id=2&voucher=" + voucher);
          var rform = document.createElement("form");
          rform.target = "_blank";
          rform.method = "POST"; // or "post" if appropriate
          rform.action = "invoice.php";

          voucher['user'] = System.user;
          var vouch = document.createElement("input");
          vouch.name = "voucher";
          vouch.value = JSON.stringify(voucher);
          rform.appendChild(vouch);

          document.body.appendChild(rform);

          rform.submit();

          rform.parentNode.removeChild(rform);          
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "Quotation generation failed! Try again later.", "error");
        }
    });

    View.GRN = Marionette.ItemView.extend({      

        template: grnTpl,

        events: {
          //"change #clients": "fetchProjects",
          "click .iadd": "addToGRN",
          "click .idiscard": "discardGRN",
          "click .igenerate": "generateGRN"
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          $('.loading').hide();
          this.setup();

          this['grnitems'] = [];
          this['tax'] = 0;
          this['disc'] = 0;
          this['subtotal'] = 0;
          this['totalamt'] = 0;
        },

        setup: function(){
          var ul = $('#suppliers');
          ul.empty();
          var uls = $('#ledgers');
          uls.empty();
          $.get(System.coreRoot + '/service/procurement/index.php?suppliers', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select Supplier...</option>');
            tp.appendTo(ul);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem['name']+'</span></option>');
              tpl.appendTo(ul);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          $.get(System.coreRoot + '/service/finance/index.php?purchaseLedgers', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-question-circle" value="0">Select Ledger...</option>');
            tp.appendTo(uls);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-question-circle" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpl.appendTo(uls);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });
          $('#disc').val(0);
          $('#total').val('');
          $('#taxes').val('');
          $('#amount').val('');
          var ulx = $('tbody');
          ulx.empty();

          $('#date-picker').daterangepicker({ singleDatePicker: true, format: 'DD/MM/YYYY' }, function(start, end, label) {});

          $('button').prop({disabled: false});
        },

        addToGRN: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          //var data = Backbone.Syphon.serialize($("#frmq1")[0]);
          var data = Backbone.Syphon.serialize($("#frmi2")[0]);
          //_.extend(data, data2);
          var ul = $('tbody');
          //alert(JSON.stringify(data));
          if (data['item'] && parseFloat(data['price']) && parseInt(data['qty'], 10) && parseInt(data['ledger'], 10)) {
            var tax = 0; var disc = 0;
            if (parseFloat(data['tax'])) {
              tax = parseFloat(data['tax']);
            };

            if (parseFloat(data['discount'])) {
              disc = parseFloat(data['discount']);
            };

            data['tax'] = tax;
            data['disc'] = disc;

            var ar = [];
            ar = this['grnitems'];
            ar.push(data);
            this['grnitems'] = ar;

            var subtotal = parseFloat(data['qty']) * parseFloat(data['price']);
            var taxamt = parseFloat(subtotal * tax/100);
            var discamt = parseFloat((subtotal + taxamt) * disc/100);
            var total = subtotal + taxamt - discamt;
            this['disc'] += discamt;
            this['tax'] += taxamt;
            this['subtotal'] += subtotal;
            this['totalamt'] += total;

            $('#taxes').val(this['tax']);
            $('#disc').val(this['disc']);
            $('#amount').val(this['subtotal']);
            $('#total').val(this['totalamt']);
            
            var tpl = $('<tr><td>'+data['item']+'</td><td>'+(parseFloat(data['price'])).formatMoney(2, '.', ',')+'</td><td>'+data['qty']+'</td><td>'+tax+'</td><td>'+disc+'</td><td>Ksh. '+(total).formatMoney(2, '.', ',')+'</td></tr>');
            tpl.appendTo(ul);

            setTimeout(function (){
              $('#ledgers option[value="0"]').prop('selected', true);
              $("#frmi2").find('input').val('');            
              $('.selectpicker').selectpicker('refresh');
            }, 150);

          }else{
            swal("Missing Details!", "Please enter all mandatory fields!", "warning");
          }  

          
        },

        isInt: function (value){
          return !isNaN(value) && (function(x) { return (x | 0) === x; })(parseInt(value, 10));
        },

        generateGRN: function(e) { 
          $('button').prop({disabled: true});
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmi1")[0]);
          var disc = Backbone.Syphon.serialize($("#frmi3")[0]);

          data['supplier'] = parseInt(data['supplier'], 10);
          if (disc['discount'] == "") {
              disc['discount'] = 0;
          };

          var items = this['grnitems'];

          if (data['supplier'] && data['invno'] && data['date'] && items.length > 0) {
            data['items'] = items;
            //alert(JSON.stringify(data));
            this.trigger("post", data);
          }else{
            swal("Missing Details!", "Ensure you have entered all supplier and invoice particulars!", "warning");
            $('button').prop({disabled: false});
          }
        },

        onSuccess: function(voucher) { 
          swal("Success!", "The purchase invoice has been posted.", "success");
          //window.open("report.php?id=2&voucher=" + voucher);
          var rform = document.createElement("form");
          rform.target = "_blank";
          rform.method = "POST"; // or "post" if appropriate
          rform.action = "grn.php";

          voucher['user'] = System.user;
          var vouch = document.createElement("input");
          vouch.name = "voucher";
          vouch.value = JSON.stringify(voucher);
          rform.appendChild(vouch);

          document.body.appendChild(rform);

          rform.submit();

          rform.parentNode.removeChild(rform);          
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "Goods received note generation failed! Try again later.", "error");
          $('button').prop({disabled: false});
        }
    });

    View.GRO = Marionette.ItemView.extend({      

        template: grnTpl,

        events: {
          //"change #clients": "fetchProjects",
          "click .iadd": "addToGRN",
          "click .idiscard": "discardGRN",
          "click .igenerate": "generateGRN",
          "keyup #disc": "discountGRN"
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          $('.loading').hide();
          //this.setup();

          this['grnitems'] = [];
          this['tax'] = 0;
          this['disc'] = 0;
          this['totalamt'] = 0;
        },

        setup: function(){
          var ul = $('#suppliers');
          ul.empty();
          var uls = $('#ledgers');
          uls.empty();
          $.get(System.coreRoot + '/service/crm/index.php?suppliers', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select Supplier...</option>');
            tp.appendTo(ul);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem['name']+'<span style="font-size: 1px"> ['+elem['details']+']</span></option>');
              tpl.appendTo(ul);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          $.get(System.coreRoot + '/service/finance/index.php?purchaseLedgers', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-question-circle" value="0">Select Ledger...</option>');
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
          $('#disc').val(0);
          $('#total').val('');
          $('#taxes').val('');
          $('#amount').val('');
          var ulx = $('tbody');
          ulx.empty();

          $('#date-range').daterangepicker({ singleDatePicker: true }, function(start, end, label) {});
        },

        addToGRN: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          //var data = Backbone.Syphon.serialize($("#frmq1")[0]);
          var data = Backbone.Syphon.serialize($("#frmi2")[0]);
          //_.extend(data, data2);
          //alert(JSON.stringify(data));
          var ar = [];
          ar = this['invitems'];
          ar.push(data);
          this['invitems'] = ar;

          var ul = $('tbody');

          var total = parseInt(data['qty']) * parseFloat(data['price']);
          this['totalamt'] += parseFloat(total);
          this['tax'] += parseFloat(total * parseInt(data['tax'])/100);

          $('#taxes').val(this['tax']);
          $('#amount').val(this['totalamt']);
          $('#total').val(this['totalamt'] + this['tax']);

          var tpl = $('<tr><td>'+data['service']+'<br><span style="font-style:italic; font-size:11px">'+data['task']+'</span></td>'+
                      '<td>'+(parseFloat(data['price'])).formatMoney(2, '.', ',')+'</td><td>'+data['qty']+'</td><td>Ksh. '+(total).formatMoney(2, '.', ',')+'</td></tr>');

          tpl.appendTo(ul);
          $('#services option[value="0"]').prop('selected', true);

          setTimeout(function (){
            $("#frmi2").find('input').val('');            
            $('.selectpicker').selectpicker('refresh');
          }, 150);
        },

        isInt: function (value){
          return !isNaN(value) && (function(x) { return (x | 0) === x; })(parseInt(value, 10));
        },

        discountGRN: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var disc = parseFloat($('#disc').val()) || 0;
          var tot = parseFloat(this['totalamt']+this['tax']) * (100 - disc)/100;
          $('#total').val(tot); 
          //Open printable quote in separate window
        },

        generateGRN: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmi1")[0]);
          var disc = Backbone.Syphon.serialize($("#frmi3")[0]);

          data['client'] = parseInt(data['client'], 10);
          if (disc['discount'] == "") {
              disc['discount'] = 0;
          };

          var items = this['invitems'];

          if (data['client'] && (parseInt(data['scope'], 10) || data['scope'] == 'G') && items.length > 0) {
            data['items'] = items;
            data['discount'] = disc['discount'];
            //alert(JSON.stringify(data));
            this.trigger("post", data);
          }else{
            swal("Missing Details!", "Ensure you have entered all supplier, scope and GRN items!", "warning");
          }
        },

        onSuccess: function(voucher) { 

          swal("Success!", "The invoice has been posted.", "success");
          //window.open("report.php?id=2&voucher=" + voucher);
          var rform = document.createElement("form");
          rform.target = "_blank";
          rform.method = "POST"; // or "post" if appropriate
          rform.action = "invoice.php";

          voucher['user'] = System.user;
          var vouch = document.createElement("input");
          vouch.name = "voucher";
          vouch.value = JSON.stringify(voucher);
          rform.appendChild(vouch);

          document.body.appendChild(rform);

          rform.submit();

          rform.parentNode.removeChild(rform);          
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "Quotation generation failed! Try again later.", "error");
        }
    });
    
    View.PaySupplier = Marionette.ItemView.extend({      

        template: paySupplierTpl,

        events: {
          "change #suppliers": "fetchInvoices",
          "click .idiscard": "discard",
          "click .ipay": "makePayment",
          "keyup tbody": "setPaying"
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          $('.loading').hide();
          this.setup();

          this['payments'] = {};
        },

        setup: function(){
          var ul = $('#suppliers');
          ul.empty();
          var uls = $('#banks');
          uls.empty();
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

          $.get(System.coreRoot + '/service/finance/index.php?banks', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-question-circle" value="">Select Account...</option>');
            tp.appendTo(uls);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-question-circle" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpl.appendTo(uls);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });
          $('input').val('');
          var ulx = $('#paysup');
          ulx.empty();
          $('button').prop({disabled: false});
          $('#totamt').text('Ksh.'); 
          $('#totbal').text('Ksh.'); 
          $('#totpay').text('Ksh.'); 
        },

        fetchInvoices: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmp1")[0]);
          data['supplier'] = parseInt(data['supplier'], 10)
          if (data['supplier']) {
            var ul = $('#paysup');
            ul.empty();
            var THAT = this;
            THAT['invoices'] = [];
            $.get(System.coreRoot + '/service/procurement/index.php?unclearedinvoices&supplier='+data['supplier'], function(result) {
              var m = JSON.parse(result);
              var totamt = 0.00;
              var totbal = 0.00;
              m.forEach(function(elem){
                THAT['invoices'][elem['id']] = elem;
                 var tpl = $('<tr><td>'+elem['date']+'</td><td>'+elem['id']+'</td><td>'+(parseFloat(elem['total']['amount'])).formatMoney(2, '.', ',')+'</td><td>'+(parseFloat(elem['balance']['amount'])).formatMoney(2, '.', ',')+'</td><td>'+
                      '<form class="form-horizontal" style="margin:0"><div class="form-group"><div class="input-group"><p class="supbal" style="display: none;">'+elem['balance']['amount']+'</p><div class="input-group-addon">'+
                      '<i class="">Ksh.</i></div><input id="'+elem['id']+'"type="text" class="form-control paying" name="paying_'+elem['id']+'" value=""></div></div></form></td>');
                tpl.appendTo(ul);
                totamt += elem['total']['amount'];
                totbal += elem['balance']['amount']; 
              });

              setTimeout(function() {
                $('#totamt').text('Ksh. '+(totamt).formatMoney(2, '.', ',')); 
                $('#totbal').text('Ksh. '+(totbal).formatMoney(2, '.', ','));
               
              }, 150);

              
            });

            
          }else{
            swal("Error!", "Select a supplier first!", "error");
          }
        },

        setPaying: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          //alert($(this).val());
          var THAT = this;
          THAT.payments = {};
          var totpay = 0.00;
          $('#paysup .paying').each(function() {
            var pay = parseFloat($(this).val()) || 0.00;            
            var sbal = parseFloat($(this).parent().find('.supbal').text());
            if (pay > sbal) {
              swal("Balance Exceeded!", "The amount being can not exceeds the invoice balance!", "warning");
              pay = 0;
            };
            $(this).val(pay);
            totpay += pay;
            THAT.payments[$(this).prop('id')] = pay;
          })
          $('#totpay').text('Ksh. '+(totpay).formatMoney(2, '.', ',')); 
          $('#amount').val(totpay); 
          //Open printable quote in separate window
        },

        makePayment: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          //$('button').prop({disabled: true});
          var data = Backbone.Syphon.serialize($("#frmp1")[0]);
          var data2 = Backbone.Syphon.serialize($("#frmp3")[0]);
          _.extend(data, data2);

          data['supplier'] = parseInt(data['supplier'], 10);

          var ar = {};
          var payments = this.payments;

          for(var k in payments) {
             if (payments[k] > 0 && payments[k] != null) {
              ar[k] = payments[k];
            };
          }
          //alert(Object.keys(ar).length);
          //alert(JSON.stringify(data));
          if (data['supplier'] && data['account'] && data['mode'] && parseFloat(data['amount']) && Object.keys(ar).length > 0) {
            data['payments'] = ar;
            //alert(JSON.stringify(data));
            this.trigger("post", data);
          }else{
            swal("Missing Details!", "Ensure you have details and are paying at least one invoice!", "warning");
            $('button').prop({disabled: false});
          }
        },

        onSuccess: function(voucher) { 

          swal("Success!", "The payment has been remitted.", "success");
          //window.open("report.php?id=2&voucher=" + voucher);
          var rform = document.createElement("form");
          rform.target = "_blank";
          rform.method = "POST"; // or "post" if appropriate
          rform.action = "payment.php";

          voucher['user'] = System.user;
          var vouch = document.createElement("input");
          vouch.name = "voucher";
          vouch.value = JSON.stringify(voucher);
          rform.appendChild(vouch);

          document.body.appendChild(rform);

          rform.submit();

          rform.parentNode.removeChild(rform);          
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "Payment could not be made! Try again later.", "error");
        }
    });

    View.SupplierTx = Marionette.ItemView.extend({      

        template: supplierTxTpl,

        events: {
          "click .fsearch": "search",
          "change #date-range-picker": "resetScope"
        },

        onShow: function(){                  
          $('.loading').hide();
          var THAT = this;
          require(["money"], function(){
            THAT.setup();
          });
        },

        setup: function(){
          var THAT = this;
          var ulx = $('#results');
          ulx.empty();

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

          $('form input').val('');

          $('#date-range-picker').daterangepicker(null, function(start, end, label) {});
        },

        resetScope: function(e) {
          $('#vall').prop('checked', false);
        },

        search: function(e) { 
          e.preventDefault();
          e.stopPropagation();

          var data = Backbone.Syphon.serialize(this);
          data['supplier'] = parseInt(data['supplier'], 10);
          
          if (data['supplier'] && data['category'] && (data['dates'] != '' || data['vall'] != false)) {
            //alert(JSON.stringify(data));
            this.trigger("search", data);
          }else{
            swal("Error!", "Please set all search paramenters!", "error");
          }
        },

        onSuccess: function(result) {
          this['entries'] = result;
          swal("Results!", result.length + " entries found.", "success");
          var THAT = this;
          var el = $('#results');
          el.empty();

          result.forEach(function(entry, i){
           var tpl = $('<tr><td>'+entry['transactionId']+'</td><td>'+entry['date']+'</td><td>'+entry['type']+'<td>Ksh. '+(parseFloat(entry['amount'])).formatMoney(2, '.', ',')+'</td>'+
                '<td>'+entry['description']+'</td></td><td>'+entry['user']+'</td><td><p class="eid" style="display: none;">'+i+'</p><a class="btn btn-info vprint" href="#"><i class="fa fa-print" style="margin: 0px;"></i></a></td></tr>');
           
           tpl.appendTo(el);
          });

          setTimeout(function() {
            $('.vprint').on('click', function(e){
              e.preventDefault();
              e.stopPropagation();
              var eid = $(this).parent().find('.eid').text();
              THAT.printVoucher(eid);                  
            });
          }, 500);
        },

        printVoucher: function(eid) {
          var voucher = this['entries'][eid]; 
          voucher['user'] = System.user;
          var rform = document.createElement("form");
          rform.target = "_blank";
          rform.method = "POST"; // or "post" if appropriate

          if (voucher.type.toLowerCase().indexOf('payment') >= 0) {
            rform.action = "payment.php";
          }else if(voucher.type.toLowerCase().indexOf('order') >= 0){
            rform.action = "porder.php";
          }else{
            rform.action = "grn.php";
          }
          
          var vouch = document.createElement("input");
          vouch.name = "voucher";
          vouch.value = JSON.stringify(voucher);
          rform.appendChild(vouch);

          /*var id = document.createElement("input");
          id.name = "id";
          id.value = 2;
          rform.appendChild(id);*/

          document.body.appendChild(rform);

          rform.submit();
          rform.parentNode.removeChild(rform);
          //window.open("report.php?id=1&voucher=" + voucher);
        },

        onEmpty: function(e) { 
          var el = $('#results');
          el.empty();
          swal("No Result!", "No transactions found matching your parameters!", "error");          
        },

        onError: function(e) { 
          swal("Error!", "Search failed! try again later.", "error");          
        }
    });

  });

  return System.ProcurementApp.Show.View;
});