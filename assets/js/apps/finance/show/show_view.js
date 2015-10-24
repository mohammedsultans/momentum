define(["app", "tpl!apps/templates/qinvoice.tpl", "tpl!apps/templates/ginvoice.tpl", "tpl!apps/templates/payment.tpl", "tpl!apps/templates/ledgers.tpl", "tpl!apps/templates/accountschart.tpl", 
  "tpl!apps/templates/ledgerentries.tpl", "tpl!apps/templates/searchtx.tpl", "tpl!apps/templates/claims.tpl",  "tpl!apps/templates/expenses.tpl", 
  "tpl!apps/templates/banktx.tpl", "tpl!apps/templates/capital.tpl", "tpl!apps/templates/clienttx.tpl", "backbone.syphon"], 
	function(System, qinvoiceTpl, ginvoiceTpl, paymentTpl, ledgersTpl, chartTpl, ledgerTxTpl, findTxTpl, claimsTpl, expensesTpl, bankTxTpl, capitalTpl, findClientTxTpl){
  System.module('FinanceApp.Show.View', function(View, System, Backbone, Marionette, $, _){   

    View.Payment = Marionette.ItemView.extend({      

        template: paymentTpl,

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
          var ul = $('#clients');
          ul.empty();
          var ulb = $('#banks');
          ulb.empty();
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

          $.get(System.coreRoot + '/service/finance/index.php?banks', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-money">Select Mode...</option>');
            tp.appendTo(ulb);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-money" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpl.appendTo(ulb);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          //Also get collection account ledgers
          var uly = $('#projects');
          uly.empty();
          $('form input').val('');
          $('form textarea').val('');
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

          voucher['user'] = System.user;
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

    View.QuoteInvoice = Marionette.ItemView.extend({      

        template: qinvoiceTpl,

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
          this.setup();
          this['quotes'] = [];
          this['tax'] = 0;
          this['amount'] = 0;
          this['total'] = 0;
          //alert(System.coreRoot);
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
            swal("Missing Details!", "Select a client first!", "warning");
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
            swal("Missing Details!", "Select a purpose first!", "warning");
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
            swal("Misiing Details!", "Select a quotation to add!", "warning");
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

          voucher['user'] = System.user;
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

    View.GeneralInvoice = Marionette.ItemView.extend({      

        template: ginvoiceTpl,

        events: {
          "change #clients": "fetchProjects",
          "click .iadd": "addToInvoice",
          "click .idiscard": "discardInvoice",
          "click .igenerate": "generateInvoice",
          "keyup #disc": "discountInvoice"
        },

        onShow: function(){
          //$("#leadscont").unwrap();
          $('.loading').hide();
          this.setup();

          this['invitems'] = [];
          this['tax'] = 0;
          this['totalamt'] = 0;
        },

        setup: function(){
          var ul = $('#clients');
          ul.empty();
          var uls = $('#services');
          uls.empty();
          $.get(System.coreRoot + '/service/crm/index.php?clients', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select Customer...</option>');
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

          $.get(System.coreRoot + '/service/operations/index.php?services', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-question-circle" value="0">Select One...</option>');
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
        },

        fetchProjects: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize($("#frmi1")[0]);
          data['client'] = parseInt(data['client'], 10);
          
          if (data['client']) {
            var ul = $('#scopes');
            ul.empty();
            $.get(System.coreRoot + '/service/operations/index.php?projects&clientid='+data['client'], function(result) {
              var m = JSON.parse(result);
              var tp = $('<option data-icon="fa fa-suitcase">Select scope...</option>');
              tp.appendTo(ul);

              tp = $('<option data-icon="fa fa-suitcase" value="G">General Services</option>');
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
            swal("Missing Details!", "Select a client first!", "warning");
          }
        },

        addToInvoice: function(e) { 
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

        discountInvoice: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var disc = parseFloat($('#disc').val()) || 0;
          var tot = parseFloat(this['totalamt']+this['tax']) * (100 - disc)/100;
          $('#total').val(tot); 
          //Open printable quote in separate window
        },

        generateInvoice: function(e) { 
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
            swal("Missing Details!", "Ensure you have client, scope and invoice items!", "warning");
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

    View.Ledgers = Marionette.ItemView.extend({      

        template: ledgersTpl,

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

    View.Chart = Marionette.ItemView.extend({      

        template: chartTpl,

        onShow: function(){                  
          $('.loading').hide();
          var THAT = this;
          require(["money"], function(){
            THAT.setup();
          });
          
        },

        setup: function(){
          var ula = $('#asset');
          ula.empty(); 
          var ulb = $('#expense');
          ulb.empty(); 
          var ulc = $('#liability');
          ulc.empty(); 
          var uld = $('#equity');
          uld.empty();          
          var ule = $('#revenue');
          ule.empty();

          var drtotal = 0;
          var crtotal = 0;

          $.get(System.coreRoot + '/service/finance/index.php?ledgerType="Asset"', function(result) {
            var m = JSON.parse(result);            
            m.forEach(function(elem){
              var tpl = $('<tr><td>'+elem['id']+'</td><td>'+elem['name']+'</td><td>'+elem['type']+'<span style="font-style:italic; font-size:11px"> - '+elem['group']+'</span></td><td>Ksh. '+(elem['balance']['amount']).formatMoney(2, '.', ',')+'</td></tr>');
              tpl.appendTo(ula);
              crtotal += elem['balance']['amount'];
            });
          });

          $.get(System.coreRoot + '/service/finance/index.php?ledgerType="Expense"', function(result) {
            var m = JSON.parse(result);            
            m.forEach(function(elem){
              var tpl = $('<tr><td>'+elem['id']+'</td><td>'+elem['name']+'</td><td>'+elem['type']+'<span style="font-style:italic; font-size:11px"> - '+elem['group']+'</span></td><td>Ksh. '+(elem['balance']['amount']).formatMoney(2, '.', ',')+'</td></tr>');
              tpl.appendTo(ulb);
              crtotal += elem['balance']['amount'];
            });
          });

          $.get(System.coreRoot + '/service/finance/index.php?ledgerType="Liability"', function(result) {
            var m = JSON.parse(result);            
            m.forEach(function(elem){
              var tpl = $('<tr><td>'+elem['id']+'</td><td>'+elem['name']+'</td><td>'+elem['type']+'<span style="font-style:italic; font-size:11px"> - '+elem['group']+'</span></td><td>Ksh. '+(elem['balance']['amount']).formatMoney(2, '.', ',')+'</td></tr>');
              tpl.appendTo(ulc);
              drtotal += elem['balance']['amount'];
            });
          });

          $.get(System.coreRoot + '/service/finance/index.php?ledgerType="Equity"', function(result) {
            var m = JSON.parse(result);            
            m.forEach(function(elem){
              var tpl = $('<tr><td>'+elem['id']+'</td><td>'+elem['name']+'</td><td>'+elem['type']+'<span style="font-style:italic; font-size:11px"> - '+elem['group']+'</span></td><td>Ksh. '+(elem['balance']['amount']).formatMoney(2, '.', ',')+'</td></tr>');
              tpl.appendTo(uld);
              drtotal += elem['balance']['amount'];
            });
          });

          $.get(System.coreRoot + '/service/finance/index.php?ledgerType="Revenue"', function(result) {
            var m = JSON.parse(result);            
            m.forEach(function(elem){
              var tpl = $('<tr><td>'+elem['id']+'</td><td>'+elem['name']+'</td><td>'+elem['type']+'<span style="font-style:italic; font-size:11px"> - '+elem['group']+'</span></td><td>Ksh. '+(elem['balance']['amount']).formatMoney(2, '.', ',')+'</td></tr>');
              tpl.appendTo(ule);
              drtotal += elem['balance']['amount'];
            });
          });

          setTimeout(function() {
            $('#drtotal').text('Ksh. '+(drtotal).formatMoney(2, '.', ','));
            $('#crtotal').text('Ksh. '+(crtotal).formatMoney(2, '.', ','));
          }, 1000);
        },
    });

    View.LedgerTransactions = Marionette.ItemView.extend({      

        template: ledgerTxTpl,

        events: {
          "click .dadd": "addDebit",
          "click .cadd": "addCredit",
          "click .tpost": "postTransaction"
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
          
          var uld = $('#drledger');
          uld.empty();
          var ulc = $('#crledger');
          ulc.empty();
          var ulx = $('tbody');
          ulx.empty();   

          this['ledgers'] = [];       

          $.get(System.coreRoot + '/service/finance/index.php?allLedgers', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-question-circle">Select one ...</option>');
            var tpb = $('<option data-icon="fa fa-question-circle">Select one ...</option>');
            tp.appendTo(ulc);
            tpb.appendTo(uld);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-list-alt" value="'+elem['id']+'">'+elem['name']+'</option>');
              var tplb = $('<option data-icon="fa fa-list-alt" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpl.appendTo(ulc);
              tplb.appendTo(uld);
              var q = THAT['ledgers'];
              q[elem['id']] = elem;
              THAT['ledgers'] = q;
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 500);
          });

          $('form input, form textarea').val('');
          $("#txstatus").html('<a class="btn btn-warning" href="#"><i class="fa fa-exclamation"></i></a>');
          $("#crtot").text('Ksh. '+(parseFloat(0)).formatMoney(2, '.', ','));
          $("#drtot").text('Ksh. '+(parseFloat(0)).formatMoney(2, '.', ','));
        },

        addDebit: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var THAT = this;
          var data = Backbone.Syphon.serialize($("#frm1")[0]);
          //_.extend(data, data2);

          if (data['debit'] && data['dramount'] != 0) {
            var ulx = $('tbody');
            var led = this['ledgers'];

            var tpl = $('<tr lid="'+data['debit']+'" effect="dr" amount="'+parseFloat(data['dramount'])+'"><td>'+led[data['debit']]['name']+'</td><td>'+(parseFloat(data['dramount'])).formatMoney(2, '.', ',')+'</td><td></td><td><a class="btn btn-danger tdel" href="#"><i class="fa fa-trash"></i></a></td></tr>');
            tpl.appendTo(ulx);

            setTimeout(function() {
              $('.tdel').off('click');
              $('.tdel').on('click', function(e){
                e.preventDefault();
                e.stopPropagation();
                $(this).parent().parent().remove();
                delete $(this).parent().parent();
                THAT.computeTotals();
              });
            }, 200);

            this.computeTotals();

          }else{
            swal("Error!", "Enter debit particulars!", "error");
          }
        },

        addCredit: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var THAT = this;
          var data = Backbone.Syphon.serialize($("#frm2")[0]);
          //_.extend(data, data2);

          if (data['credit'] && data['cramount'] != 0) {
            var ulx = $('tbody');
            var led = this['ledgers'];

            var tpl = $('<tr lid="'+data['credit']+'" effect="cr" amount="'+parseFloat(data['cramount'])+'"><td>'+led[data['credit']]['name']+'</td><td></td><td>'+(parseFloat(data['cramount'])).formatMoney(2, '.', ',')+'</td><td><a class="btn btn-danger tdel" href="#"><i class="fa fa-trash"></i></a></td></tr>');
            tpl.appendTo(ulx);

            setTimeout(function() {
              $('.tdel').off('click');
              $('.tdel').on('click', function(e){
                e.preventDefault();
                e.stopPropagation();
                $(this).parent().parent().remove();
                delete $(this).parent().parent();
                THAT.computeTotals();
              });
            }, 200);            

            this.computeTotals();

          }else{
            swal("Error!", "Enter credit particulars!", "error");
          }
        },

        computeTotals: function() { 
          var cr = 0;
          var dr = 0;
          $("tbody tr td:nth-child(3)").each(function(){
              cr += parseFloat($(this).text().replace(/,/g , "") || 0);
          });

          $("tbody tr td:nth-child(2)").each(function(){
              dr += parseFloat($(this).text().replace(/,/g , "") || 0);
          });

          this['amount'] = 0;

          setTimeout(function() {
            $("#crtot").text('Ksh. '+(parseFloat(cr)).formatMoney(2, '.', ','));
            $("#drtot").text('Ksh. '+(parseFloat(dr)).formatMoney(2, '.', ','));
            if (cr == dr) {
              this['amount'] = cr;
              $("#txstatus").html('<a class="btn btn-success" href="#"><i class="fa fa-check"></i></a>');
            }else{
              $("#txstatus").html('<a class="btn btn-warning" href="#"><i class="fa fa-close"></i></a>');
            }
          }, 300);
        },


        postTransaction: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = {}
          var entries = [];
          $("tbody tr").each(function(){
            var entry = {};
            entry['lid'] = $(this).attr('lid');
            entry['effect'] = $(this).attr('effect');
            entry['amount'] = $(this).attr('amount');
            entries.push(entry);
          });

          var THAT = this;

          setTimeout(function() {
            if (!($.isEmptyObject(entries)) && $("#descr").val() != "" && this['amount'] != 0) {
              data['entries'] = entries;
              data['descr'] = $("#descr").val();
              data['amount'] = this['amount'];
              //alert(JSON.stringify(data));
              THAT.trigger("post", data);
            }else{
              swal("Error!", "Ensure you ADD the entries and the description is written!", "error");
            }
          }, 300);
        },

        onSuccess: function() {
          swal("Success!", "Transaction completed.", "success");
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "Transaction failed! Try again later.", "error");          
        }
    });

    View.FindTransactions = Marionette.ItemView.extend({      

        template: findTxTpl,

        events: {
          "click .fsearch": "search",
          "change #param": "setFilter"
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
          var ulx = $('tbody');
          ulx.empty();   

          $('form input').val('');

          $('.selectpicker').selectpicker();
          $('.selectpicker').selectpicker('refresh');
        },

        setFilter: function(e) {
          e.preventDefault();
          e.stopPropagation();

          var tpl;

          var filter = $('#filter');
          filter.empty();

          switch(parseInt($('#param').val(), 10)){
            case 1:
              tpl = $('<input type="text" name="value" class="form-control">');
              
              filter.append(tpl);

              break;
            case 2:
              tpl = $('<input type="text" name="value" class="form-control">');

              filter.append(tpl);

              break;
            case 3:
              tpl = $('<div class="control-group"><div class="controls"><div class="input-prepend input-group"><span class="add-on input-group-addon">'+
                    '<i class="fa fa-calendar"></i></span><input type="text" id="date-picker" class="form-control" name="value"/></div></div></div>');
              
              filter.append(tpl);

              setTimeout(function() {
                $('#date-picker').daterangepicker({ singleDatePicker: true }, function(start, end, label) {
                  console.log(start.toISOString(), end.toISOString(), label);
                });
              }, 300);

              break;
            case 4:
              tpl = $('<div class="control-group"><div class="controls"><div class="input-prepend input-group"><span class="add-on input-group-addon">'+
                    '<i class="fa fa-calendar"></i></span><input type="text" id="date-range-picker" class="form-control" name="value"/></div></div></div>');

              filter.append(tpl);

              setTimeout(function() {
                $('#date-range-picker').daterangepicker(null, function(start, end, label) {
                  console.log(start.toISOString(), end.toISOString(), label);
                });
              }, 300);
              break;
            case 5:
              //Ledger

              var el = $('<select class="selectpicker form-control" name="value" style="padding-left:5px"></select>');

              $.get(System.coreRoot + '/service/finance/index.php?allLedgers', function(result) {
                var m = JSON.parse(result);
                
                m.forEach(function(elem){
                  var tpl = $('<option data-icon="fa fa-arrow-circle-o-right" value="'+elem['id']+'">'+elem['name']+'</option>');
                  tpl.appendTo(el);
                });        

                setTimeout(function() {
                  filter.append(el);
                  $('.selectpicker').selectpicker();
                  $('.selectpicker').selectpicker('refresh');
                }, 300);
              });
              break;
            case 6:
              //Amount
              tpl = $('<div class="input-group"><div class="input-group-addon"><i class="">Ksh.</i></div>' +
                      '<input type="text" class="form-control" name="value" id=""></div>');

              filter.append(tpl);
              break;
            default:
              break;
          }
        },

        search: function(e) { 
          e.preventDefault();
          e.stopPropagation();

          var data = Backbone.Syphon.serialize(this);
          
          if (data['param'] && data['value']) {
            //alert(JSON.stringify(data));
            this.trigger("search", data);
          }else{
            swal("Error!", "Please set all search paramenters!", "error");
          }
        },

        reverseTx: function(txid) {        
          $('#results').find('.txid').each(function() {
            if (parseInt($(this).text(), 10) == txid) {
              $(this).parent().empty().append($('<a class="btn btn-info" href="#"><i class="fa fa-exclamation" style="margin: 0px;"></i></a>'));
            };
          });
        },

        onSuccess: function(result) {
          swal("Results!", result.length + " entries found.", "success");
          var THAT = this;
          var el = $('tbody');
          el.empty();

          result.forEach(function(entry){
           var tpl = $('<tr><td>'+entry['txid']+'</td><td>'+entry['date']+'</td><td>'+entry['ledger']+'</td><td style="text-transform:uppercase;">'+entry['effect']+'</td>'+
                '<td>Ksh. '+(parseFloat(entry['amount'])).formatMoney(2, '.', ',')+'</td><td>'+entry['description']+'</td><td><p class="txid" style="display: none;">'+entry['txid']+'</p><a class="btn btn-warning treverse" href="#"><i class="fa fa-undo" style="margin: 0px;"></i></a></td></tr>');
           
           tpl.appendTo(el);
          });

          setTimeout(function() {
            $('.treverse').on('click', function(e){
                  e.preventDefault();
                  e.stopPropagation();
                  //var el = $(this);
                  var txid = $(this).parent().find('.txid').text();
                  swal({
                    title: "Are you sure?",
                    text: "Once you reverse this transaction, there is NO going back again!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, reverse!",
                    cancelButtonText: "No, cancel!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                  },
                  function(isConfirm){
                    if (isConfirm) {
                      THAT.reverseTx(txid); 
                      //Enter reversing logic here                          
                    }else {
                      swal("Cancelled", "The transaction has NOT been reversed.", "error");
                    }
                  });
                  
                });
            }, 500);
        },

        onEmpty: function(e) { 
          var el = $('tbody');
          el.empty();
          swal("No Result!", "No entries found matching your parameters!", "error");          
        },

        onError: function(e) { 
          swal("Error!", "Search failed! try again later.", "error");          
        }
    });

    View.Claims = Marionette.ItemView.extend({      

        template: claimsTpl,

        events: {
          "click .bauth": "authorizeExpense",
          "click .brevoke": "revokeExpense",
          "change #projects": "fetchExpenses",
          "change #vouchers": "setVoucher"
        },

        onShow: function(){                  
          $('.loading').hide();
          var THAT = this;
          this['vouchers'] = [];
          require(["money"], function(){
            THAT.setup();
          });
        },

        setup: function(){
          
          var ula = $('#accounts');
          ula.empty();

          var ulb = $('#projects');
          ulb.empty();

          var ulc = $('#vouchers');
          ulc.empty();

          $.get(System.coreRoot + '/service/finance/index.php?banks', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-money">Select Account...</option>');
            tp.appendTo(ula);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-money" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpl.appendTo(ula);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          $.get(System.coreRoot + '/service/operations/index.php?allProjects', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-arrow-circle-o-right">Select Project...</option>');
            tp.appendTo(ulb);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-archive" value="'+elem['id']+'">PRJ-'+elem['name']+'</option>');
              tpl.appendTo(ulb);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          var uly = $('tbody');
          uly.empty();
        },
      
        fetchExpenses: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          data['project'] = parseInt(data['project'], 10);

          this['vouchers'] = [];
          var THAT = this;
          if (data['project']) {
            var ul = $('#vouchers');
            ul.empty();
            var ula = $('tbody');
            ula.empty();

            $.get(System.coreRoot + '/service/operations/index.php?projectClaims='+data['project'], function(result) {
              var m = JSON.parse(result);
              var tp = $('<option data-icon="fa fa-money" value="G">Select voucher...</option>');
              tp.appendTo(ul);
              
              m.forEach(function(elem){
                var tpl = $('<option data-icon="fa fa-money" value="'+elem['id']+'">EXP-'+elem['date']+'</option>');
                tpl.appendTo(ul);
                THAT['vouchers'][elem['id']] = elem;
              });

              setTimeout(function() {
                  $('.selectpicker').selectpicker('refresh');
              }, 300);
            });

          }else{
            swal("Error!", "Select a project first!", "error");
          }
        },

        setVoucher: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);

          data['voucher'] = parseInt(data['voucher'], 10);

          if (data['voucher']) {
            
            if (!(_.contains(this['vouchers'], data['voucher']))) {

              var ul = $('tbody');
              ul.empty();

              var items = this['vouchers'][data['voucher']]['items'];
                
              items.forEach(function(elem){
                var tpl = $('<tr><td>'+elem['claimant']+'</td><td>'+elem['description']+'</td><td><p class="lid" style="display: none;">'+elem['ledger']['id']+'</p>'+elem['ledger']['name']+'</td><td>Ksh. '+(parseFloat(elem['claimed'])).formatMoney(2, '.', ',')+'</td>'+
                  '<td><form class="form-horizontal" style="margin:0"><div class="form-group"><div class="input-group"><p class="viid" style="display: none;">'+elem['id']+'</p><div class="input-group-addon">'+
                  '<i class="">Ksh.</i></div><input type="text" class="form-control adjusted" name="adjusted" value="'+elem['adjusted']+'"></div></div></form></td></tr>');
                tpl.appendTo(ul);
              });
            }
          }else{
            swal("Error!", "Select a quotation to add!", "error");
          }
        },

        authorizeExpense: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var THAT = this;
          var data = Backbone.Syphon.serialize(this);
          var items = [];
          $("tbody tr").each(function(){
            var item = {};
            item['viid'] = $(this).find('.viid').text();
            item['lid'] = $(this).find('.lid').text();
            item['amount'] = $(this).find('.adjusted').val();
            items.push(item);
          });

          setTimeout(function() {
            if (!($.isEmptyObject(items)) && data['account'] && data['project']) {
              data['items'] = items;
              //alert(JSON.stringify(data));
              THAT.trigger("authorize", data);
            }else{
              swal("Error!", "Ensure you select an expense voucher and the details are given!", "error");
            }
          }, 300);
        },

        onSuccess: function(voucher) { 
          swal("Success!", "The claim has been paid.", "success");
          //alert(JSON.stringify(voucher));

          /*var rform = document.createElement("form");
          rform.target = "_blank";
          rform.method = "POST"; // or "post" if appropriate
          rform.action = "claim.php";

          var vouch = document.createElement("input");
          vouch.name = "voucher";
          vouch.value = JSON.stringify(voucher);
          rform.appendChild(vouch);

          /*var id = document.createElement("input");
          id.name = "id";
          id.value = 2;
          rform.appendChild(id);

          document.body.appendChild(rform);

          rform.submit();
          rform.parentNode.removeChild(rform);
          //window.open("report.php?id=1&voucher=" + voucher);*/
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "Payment could not be received! Try again.", "error");
        }
    });

    View.Expenses = Marionette.ItemView.extend({      

        template: expensesTpl,

        events: {
          "click .ediscard": "setup",
          "click .esubmit": "postTransaction"
        },

        onShow: function(){                  
          $('.loading').hide();
          this.setup();
        },

        setup: function(){          
          var uld = $('#banks');
          uld.empty();
          var ulc = $('#expenses');
          ulc.empty();

          $.get(System.coreRoot + '/service/finance/index.php?banks', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-money">Select Ledger...</option>');
            tp.appendTo(uld);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-money" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpl.appendTo(uld);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          $.get(System.coreRoot + '/service/finance/index.php?ledgerType="Expense"', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-money">Select Ledger...</option>');
            tp.appendTo(ulc);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-money" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpl.appendTo(ulc);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });  

          $('form input, form textarea').val('');
        },

        postTransaction: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          
          var THAT = this;

          setTimeout(function() {
            if (data['credit'] && data['debit'] && data['amount'] != 0 && data['descr'] != "") {
              //alert(JSON.stringify(data));
              THAT.trigger("post", data);
            }else{
              swal("Error!", "Ensure ledgers are selected, the amount is entered and the description is written!", "error");
            }
          }, 300);
        },

        onSuccess: function() {
          swal("Success!", "Transaction completed.", "success");
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "Transaction failed! Try again later.", "error");          
        }
    });

    View.Capital = Marionette.ItemView.extend({      

        template: capitalTpl,

        events: {
          "click .cdiscard": "setup",
          "click .csubmit": "postTransaction"
        },

        onShow: function(){                  
          $('.loading').hide();
          this.setup();
        },

        setup: function(){          
          var uld = $('#owners');
          uld.empty();
          var ulc = $('#assets');
          ulc.empty();

          $.get(System.coreRoot + '/service/finance/index.php?ledgerType="Equity"', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-money">Select Ledger...</option>');
            tp.appendTo(uld);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-money" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpl.appendTo(uld);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          $.get(System.coreRoot + '/service/finance/index.php?ledgerType="Asset"', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-money">Select Ledger...</option>');
            tp.appendTo(ulc);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-money" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpl.appendTo(ulc);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });  

          $('form input, form textarea').val('');
        },

        postTransaction: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          
          var THAT = this;

          setTimeout(function() {
            if (data['credit'] && data['debit'] && data['amount'] != 0 && data['descr'] != "") {
              //alert(JSON.stringify(data));
              THAT.trigger("post", data);
            }else{
              swal("Error!", "Ensure ledgers are selected, the amount is entered and the description is written!", "error");
            }
          }, 300);
        },

        onSuccess: function() {
          swal("Success!", "Transaction completed. Capital added to enterprise.", "success");
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "Transaction failed! Try again later.", "error");          
        }
    });

    View.BankTransactions = Marionette.ItemView.extend({      

        template: bankTxTpl,

        events: {
          "click .bdiscard": "setup",
          "click .bpost": "postTransaction"
        },

        onShow: function(){                  
          $('.loading').hide();
          this.setup();
        },

        setup: function(){          
          var uld = $('#accounts');
          uld.empty();

          $.get(System.coreRoot + '/service/finance/index.php?banks', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-money">Select Account...</option>');
            tp.appendTo(uld);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-money" value="'+elem['id']+'">'+elem['name']+'</option>');
              tpl.appendTo(uld);
            });
            
            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
            }, 300);
          });

          $.get(System.coreRoot + '/service/finance/index.php?ledgerName="Cash in Hand"', function(result) {
            var m = JSON.parse(result);            
            $('#cash').val(m['balance']['amount']);
          });

          $('form textarea').val('');
          $('#amt').val('');
        },

        postTransaction: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          
          var THAT = this;

          setTimeout(function() {
            if (data['action'] && data['account'] && data['amount'] != 0 && data['descr'] != "") {
              //alert(JSON.stringify(data));              
              if (data['action'] == "CashDeposit" && parseFloat($('#cash').val()) <  parseFloat(data['amount'])) {
                swal("Error!", "Amount to deposit is more than cash in hand!", "error");
              }else{
                THAT.trigger("post", data);
              }
            }else{
              swal("Error!", "Ensure action is selected, the account is selected, the amount is entered and the note is written!", "error");
            }
          }, 300);
        },

        onSuccess: function() {
          swal("Success!", "Banking Transaction completed.", "success");
          this.setup();
        },

        onError: function(e) { 
          swal("Error!", "Transaction failed! Try again later.", "error");          
        }
    });

    View.FindClientTransactions = Marionette.ItemView.extend({      

        template: findClientTxTpl,

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

          var ul = $('#clients');
          ul.empty();
          $.get(System.coreRoot + '/service/crm/index.php?clients', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select Customer...</option>');
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
          data['client'] = parseInt(data['client'], 10);
          
          if (data['client'] && data['category'] && (data['dates'] != '' || data['vall'] != false)) {
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
          if (voucher.type.toLowerCase() == 'receipt') {
            rform.action = "receipt.php";
          }else if(voucher.type.toLowerCase() == 'quotation'){
            rform.action = "quotation.php";
          }else{
            rform.action = "invoice.php";
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

  return System.FinanceApp.Show.View;
});
