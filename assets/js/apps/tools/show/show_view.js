define(["app", "tpl!apps/templates/users.tpl", "tpl!apps/templates/roles.tpl", "tpl!apps/templates/password.tpl", "backbone.syphon"], 
	function(System, usersTpl, rolesTpl, passwordTpl){
  System.module('ToolsApp.Show.View', function(View, System, Backbone, Marionette, $, _){   

    View.Users = Marionette.ItemView.extend({      

        template: usersTpl,

        events: {
          "click .psubmit": "submitPayment",
          "click .pcancel": "cancelPayment",
          "change #clients": "fetchProjects"
        },

        onShow: function(){                  
          $('.loading').hide();
          this.setup();
        },

        setup: function(){
          var ul = $('#employees');
          ul.empty();
          var ula = $('#employees2');
          ula.empty();
          var ulb = $('#banks');
          ulb.empty();
          $.get(System.coreRoot + '/service/hrm/index.php?employees', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select Employee...</option>');
            var tpa = $('<option data-icon="fa fa-user">Select Employee...</option>');
            tp.appendTo(ul);
            tpa.appendTo(ula);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpl.appendTo(ul);
              var tpla = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpla.appendTo(ula);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          $('form input').val('');
        },
      
        fetchProjects: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          data['client'] = parseInt(data['client'], 10);
          
          if (data['client']) {
            var ul = $('#projects');
            ul.empty();
            $.get(System.coreRoot + '/service/operations/index.php?projects&clientid='+data['client'], function(result) {
              var m = JSON.parse(result);
              var tp = $('<option data-icon="fa fa-suitcase" value="G">General Payment</option>');
              tp.appendTo(ul);
              
              m.forEach(function(elem){
                var tpl = $('<option data-icon="fa fa-archive" value="'+elem['id']+'">PRJ-'+elem['name']+'</option>');
                tpl.appendTo(ul);
              });
              
              setTimeout(function() {
                  $('.selectpicker').selectpicker('refresh');
              }, 300);
            });

          }else{
            swal("Error!", "Select a client first!", "error");
          }
        },

        submitPayment: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          data['client'] = parseInt(data['client'], 10);
          if (data['client'] && data['category'] && data['mode'] && data['amount']) {
            //alert(JSON.stringify(data));
            this.trigger("submit", data);
          }else{
            swal("Error!", "Enter All Payment Details!", "error");
          }
        },

        onSuccess: function(voucher) { 
          swal("Success!", "The payment has been received.", "success");
          var rform = document.createElement("form");
          rform.target = "_blank";
          rform.method = "POST"; // or "post" if appropriate
          rform.action = "receipt.php";

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
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "Payment could not be received! Try again.", "error");
        }
    });

    View.Roles = Marionette.ItemView.extend({      

        template: rolesTpl,

        events: {
          "click .ipost": "postInvoice",
          "click .idiscard": "discardInvoice",
          "change #clients": "fetchProjects",
          "change #projects": "fetchQuotes",
          "change #quotes": "addToInvoice",
          "keyup #disc": "discountInvoice"
        },

        onShow: function(){                  
          $('.loading').hide();
          //this.setup();
          this['roles'] = [];
          $('.checkbox').button();
        },

        setup: function(){
          var ul = $('#clients');
          ul.empty();
          $.get(System.coreRoot + '/service/crm/index.php?clients', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-institution">Select Customer...</option>');
            tp.appendTo(ul);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-institution" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpl.appendTo(ul);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });
          var uly = $('#projects');
          uly.empty();
          var ult = $('#quotes');
          ult.empty();
          var ulx = $('tbody');
          ulx.empty();
          $('form input').val('');
          $('form textarea').val('');
        },
      
        fetchProjects: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmi1")[0]);
          data['client'] = parseInt(data['client'], 10);
          
          if (data['client']) {
            var ul = $('#projects');
            ul.empty();
            $.get(System.coreRoot + '/service/operations/index.php?projects&clientid='+data['client'], function(result) {
              var m = JSON.parse(result);
              var tp = $('<option data-icon="fa fa-suitcase">Select purpose...</option>');
              tp.appendTo(ul);

              tp = $('<option data-icon="fa fa-suitcase" value="G">General Invoice</option>');
              tp.appendTo(ul);
              
              m.forEach(function(elem){
                var tpl = $('<option data-icon="fa fa-archive" value="'+elem['id']+'">PRJ-'+elem['name']+'</option>');
                tpl.appendTo(ul);
              });
              
              setTimeout(function() {
                  $('.selectpicker').selectpicker('refresh');
              }, 300);
            });

          }else{
            swal("Error!", "Select a client first!", "error");
          }
        },

        fetchQuotes: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmi1")[0]);
          //alert(JSON.stringify(data));
          //data['project'] = parseInt(data['project'], 10);
          //alert(JSON.stringify(data));
          if (parseInt(data['purpose'], 10) || data['purpose'] == 'G') {
            this['tax'] = 0;
            this['amount'] = 0;
            this['total'] = 0;
            $('#taxes').val('');
            $('#total').val(''); 
            $('#amount').val('');
            this['quotes'] = [];
            var ulx = $('tbody');
            ulx.empty();
            var ul = $('#quotes');
            ul.empty();
            if (parseInt(data['purpose'], 10)) {
              $.get(System.coreRoot + '/service/operations/index.php?quotes&project='+data['purpose'], function(result) {
                var quotes = JSON.parse(result);
                //alert(JSON.stringify(res));
                var tp = $('<option data-icon="fa fa-calculator" value="0">Select Quotation...</option>');
                tp.appendTo(ul);
                
                quotes.forEach(function(elem){
                  if (elem['status'] < 3) {
                    var tpl = $('<option data-icon="fa fa-calculator" value="'+elem['id']+'">QUOT-'+elem['id']+'</option>');
                    tpl.appendTo(ul);
                  };                  
                });
                
                setTimeout(function() {
                  $('.selectpicker').selectpicker('refresh');
                }, 300);
              });
            }else if(data['purpose'] == 'G'){
              $.get(System.coreRoot + '/service/operations/index.php?genquotes='+parseInt(data['client'], 10), function(result) {
                var quotes = JSON.parse(result);
                //alert(JSON.stringify(res));
                var tp = $('<option data-icon="fa fa-calculator" value="0">Select Quotation...</option>');
                tp.appendTo(ul);
                
                quotes.forEach(function(elem){
                  if (elem['status'] < 3) {
                    var tpl = $('<option data-icon="fa fa-calculator" value="'+elem['id']+'">QUOT-'+elem['id']+'</option>');
                    tpl.appendTo(ul);
                  };
                });
                
                setTimeout(function() {
                  $('.selectpicker').selectpicker('refresh');
                }, 300);
              });
            }
            

            
          }else{
            swal("Error!", "Select a purpose first!", "error");
          }
        },

        addToInvoice: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          //var data = Backbone.Syphon.serialize($("#frmq1")[0]);
          var data = Backbone.Syphon.serialize($("#frmi1")[0]);
          //_.extend(data, data2);
          //alert(JSON.stringify(data));
          data['quote'] = parseInt(data['quote'], 10);

          if (data['quote']) {
            var qt = [];
            qt = this['quotes'];
            
            if (!(_.contains(qt, data['quote']))) {
              qt.push(data['quote']);
              this['quotes'] = qt;
              var THAT = this;
              var ul = $('tbody');
              $.get(System.coreRoot + '/service/operations/index.php?quote='+data['quote'], function(result) {                
                var res = JSON.parse(result);

                var items = res['lineItems'];
                
                items.forEach(function(elem){
                  var total = parseInt(elem['quantity']) * parseFloat(elem['unitPrice']);
                  var tpl = $('<tr><td>'+elem['itemName']+'<br><span style="font-style:italic; font-size:11px">'+elem['itemDesc']+'</span></td>'+
                    '<td>'+elem['unitPrice']+'</td><td>'+elem['quantity']+'</td><td>Ksh. '+total+'</td></tr>');
                  tpl.appendTo(ul);
                });

                THAT['amount'] +=  parseFloat(res['amount']);
                THAT['total'] += parseFloat(res['total']);
                THAT['tax'] += parseFloat(res['taxamt']);

                $('#taxes').val(THAT['tax']);
                $('#total').val(THAT['total']); 
                $('#amount').val(THAT['amount']);
 
                setTimeout(function() {
                  $("select[name=quote] option[value='"+data['quote']+"']").css('display', 'none'); 
                  $("select[name=quote]").val(0);  
                  $('.selectpicker').selectpicker('refresh');
                }, 100);  
              });
            };
          }else{
            swal("Error!", "Select a quotation to add!", "error");
          }
        },

        discountInvoice: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var disc = parseFloat($('#disc').val()) || 0;
          var tot = parseFloat(this['total']) * (100 - disc)/100;
          $('#total').val(tot); 
          //Open printable quote in separate window
        },


        postInvoice: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmi1")[0]);
          var data3 = Backbone.Syphon.serialize($("#frmi3")[0]);
          _.extend(data, data3);
          data['client'] = parseInt(data['client'], 10);
          if (data['client'] && data['purpose']) {
            data['quotes'] = this['quotes'];
            if (data['discount'] == "") {
              data['discount'] = 0;
            };
            //alert(JSON.stringify(data));
            this.trigger("post", data);
          }else{
            swal("Error!", "Enter All Details!", "error");
          }
        },

        onSuccess: function(voucher) { 

          swal("Success!", "The invoice has been posted.", "success");
          //window.open("report.php?id=2&voucher=" + voucher);
          var rform = document.createElement("form");
          rform.target = "_blank";
          rform.method = "POST"; // or "post" if appropriate
          rform.action = "invoice.php";

          var vouch = document.createElement("input");
          vouch.name = "voucher";
          vouch.value = JSON.stringify(voucher);
          rform.appendChild(vouch);

          document.body.appendChild(rform);

          rform.submit();

          this.setup();
          rform.parentNode.removeChild(rform);
          //Open printable quote in separate window
        },

        onError: function(e) { 
          swal("Error!", "Invoice could not be posted! Try again later.", "error");
          this.setup();
        }
    });

    View.ChangePassword = Marionette.ItemView.extend({      

        template: passwordTpl,

        events: {
          "click .lsave": "createLedger"
        },

        onShow: function(){                  
          $('.loading').hide();
          this.setup();
        },

        setup: function(){
          var THAT = this;
          var ul = $('#subacc');
          ul.empty();
          var ulx = $('tbody');
          ulx.empty();          

          $.get(System.coreRoot + '/service/finance/index.php?allLedgers', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-list-alt">N/A</option>');
            tp.appendTo(ul);
            
            m.forEach(function(elem){
              var tpla = $('<option data-icon="fa fa-list-alt" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpla.appendTo(ul);
              var tplb = $('<tr><td>'+elem['name']+'</td><td>'+elem['type']+'<span style="font-style:italic; font-size:11px"> - '+elem['group']+'</span></td>'+
                    '<td><p class="lbal" style="display: none;">'+elem['balance']['amount']+'</p>Ksh. '+elem['balance']['amount']+'</td><td><p class="lid" style="display: none;">'+elem['id']+'</p><a class="btn btn-danger ldel" href="#"><i class="fa fa-trash"></i></a></td></tr>');
              tplb.appendTo(ulx);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');

                $('.ldel').on('click', function(e){
                  e.preventDefault();
                  e.stopPropagation();
                  var lid = $(this).parent().find('.lid').text();
                  var bal = $(this).parent().parent().find('.lbal').text();
                  swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this ledger!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                  },
                  function(isConfirm){
                    if (isConfirm && parseFloat(bal) == 0) {
                      THAT.deleteLedger(lid);                             
                    } else if (isConfirm && parseFloat(bal) > 0) {
                      swal("Restricted", "Deletion Prevented. Ensure balance is ZERO before deletion.", "error");
                    }else {
                      swal("Cancelled", "The ledger has NOT been deleted :)", "error");
                    }
                  });
                  
                });
            }, 300);
          });

          
          
          $('form input').val('');
        },
      
        createLedger: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          
          if (data['name'] && data['type'] && data['group'] && data['category'] && data['subaccount']) {
            //alert(JSON.stringify(data));
            this.trigger("create", data);
          }else{
            swal("Error!", "Enter all parameters!", "error");
          }
        },

        deleteLedger: function(lid) { 
          this.trigger("delete", lid);
        },

        onSuccess: function(voucher) { 
          swal("Success!", "The ledger has been created.", "success");
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "Ledger could not be created! Please, try again.", "error");
        }
    });
  });

  return System.ToolsApp.Show.View;
});
