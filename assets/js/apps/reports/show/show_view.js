define(["app", "tpl!apps/templates/financialRpts.tpl", "tpl!apps/templates/clientRpts.tpl", "tpl!apps/templates/procurementRpts.tpl"], 
	function(System, financialRptsTpl, clientRptsTpl, procurementRptsTpl){
  System.module('ReportsApp.Show.View', function(View, System, Backbone, Marionette, $, _){

    View.Modals = {

      dateModal: function(id, title){
        swal({
            title: title,
            text: "<form class=\"form-horizontal\" id=\"frmi1\"><div class=\"form-group\"><label class=\"col-sm-2 control-label form-label\">As at</label>"+
                "<div class=\"col-sm-10\"><div class=\"control-group\"><div class=\"controls\"><div class=\"input-prepend input-group\"><span class=\"add-on input-group-addon\"><i class=\"fa fa-calendar\"></i></span>"+
                "<input type=\"text\" id=\"date-single\" class=\"form-control\" name=\"date\"/ value=\""+moment().format('DD/MM/YYYY')+"\"></div></div></div></div></div></form>",
            html: true,
            showCancelButton: true,
            confirmButtonText: "View Report",
            cancelButtonText: "Cancel",
            closeOnConfirm: false
          },
          function(isConfirm){
              if (isConfirm) {
                var day = $('#date-single').val();
                //alert('reports.php?id='+rid+'&sid='+sid+'&period='+period+'&all='+all);
                window.open('reports.php?id='+id+'&day='+day);             
              } else {
                swal("Cancelled", "Your have chosen not to view report.", "info");
              }
          }
        );
 
        setTimeout(function() {
         $('#date-single').daterangepicker({ singleDatePicker: true, format: 'DD/MM/YYYY', maxDate: moment().format('DD/MM/YYYY') }, function(start, end, label) {});
          $('.sweet-alert').css('overflow', 'visible');
          $('.daterangepicker.dropdown-menu').css('z-index', 300000);
        }, 150);
      },

      rangeModal: function(id, title){
        swal({
            title: title,
            text: "<form class=\"form-horizontal\" id=\"frmi1\"><div class=\"form-group\"><label class=\"col-sm-2 control-label form-label\">Period<span class=\"color10\">*</span></label>"+
                "<div class=\"col-sm-10\"><div class=\"control-group\"><div class=\"controls\"><div class=\"input-prepend input-group\"><span class=\"add-on input-group-addon\"><i class=\"fa fa-calendar\"></i></span>"+
                "<input type=\"text\" id=\"date-period\" class=\"form-control\" name=\"date\"/></div></div></div></div></div><div class=\"form-group\" style=\"padding-right:0\">"+
                  "<div class=\"checkbox checkbox-primary\" style=\"margin:0\"><input id=\"viewall\" name=\"viewall\" type=\"checkbox\"><label for=\"viewall\">View All</label></div></div></form>",
            html: true,
            showCancelButton: true,
            confirmButtonText: "View Report",
            cancelButtonText: "Cancel",
            closeOnConfirm: false
            },
            function(isConfirm){
              if (isConfirm) {
                var rid = id; 
                var period = $('#date-period').val();
                var all = '';
                if($('#viewall').is(':checked')){
                  all = 'true'
                }
                //alert('reports.php?id='+rid+'&sid='+sid+'&period='+period+'&all='+all);
                window.open('reports.php?id='+id+'&period='+period+'&all='+all);             
              } else {
                swal("Cancelled", "Your have chosen not to view report.", "info");
              }
            }
        );

        setTimeout(function() {
          $('#date-period').daterangepicker({ format: 'DD/MM/YYYY', maxDate: moment().format('DD/MM/YYYY')  }, function(start, end, label) {

          });
          $('.sweet-alert').css('overflow', 'visible');
          $('.daterangepicker.dropdown-menu').css('z-index', 300000);
        }, 150);
      },

      dateRangeModal: function(id, title){
        swal({
            title: title,
            text: "<form class=\"form-horizontal\" id=\"frmi1\"><div class=\"form-group\"><label class=\"col-sm-2 control-label form-label\">Period</label>"+
                "<div class=\"col-sm-10\"><div class=\"control-group\"><div class=\"controls\"><div class=\"input-prepend input-group\"><span class=\"add-on input-group-addon\"><i class=\"fa fa-calendar\"></i></span>"+
                "<input type=\"text\" id=\"date-period\" class=\"form-control\" name=\"date\"/></div></div></div></div></div><div class=\"form-group\" style=\"border-top: 2px dashed; padding-top:15px\"><label class=\"col-sm-2 control-label form-label\">As at</label>"+
                "<div class=\"col-sm-10\"><div class=\"control-group\"><div class=\"controls\"><div class=\"input-prepend input-group\"><span class=\"add-on input-group-addon\"><i class=\"fa fa-calendar\"></i></span>"+
                "<input type=\"text\" id=\"date-single\" class=\"form-control\" name=\"date\"/ value=\""+moment().format('DD/MM/YYYY')+"\"></div></div></div></div></div></form>",
            html: true,
            showCancelButton: true,
            confirmButtonText: "View Report",
            cancelButtonText: "Cancel",
            closeOnConfirm: false
            },
            function(isConfirm){
              if (isConfirm) {
                var period = $('#date-period').val();
                var day = $('#date-single').val();
                //alert('reports.php?id='+rid+'&sid='+sid+'&period='+period+'&all='+all);
                window.open('reports.php?id='+id+'&period='+period+'&day='+day);             
              } else {
                swal("Cancelled", "Your have chosen not to view report.", "info");
              }
            }
        );

        setTimeout(function() {
          $('#date-period').daterangepicker({ format: 'DD/MM/YYYY', maxDate: moment().format('DD/MM/YYYY')  }, function(start, end, label) {
             $('#date-single').val('');
          });
          $('#date-single').daterangepicker({ singleDatePicker: true, format: 'DD/MM/YYYY', maxDate: moment().format('DD/MM/YYYY') }, function(start, end, label) {
            $('#date-period').val('');
          });
          $('.sweet-alert').css('overflow', 'visible');
          $('.daterangepicker.dropdown-menu').css('z-index', 300000);
        }, 150);
      },

      subjectRangeModal: function(id, title, subjectUrl){
        swal({
            title: title,
            text: "<form class=\"form-horizontal\" id=\"frmi1\"><div class=\"form-group\"><label class=\"col-sm-2 control-label form-label\">Subject<span class=\"color10\">*</span></label><div class=\"col-sm-10\">"+
                  "<select class=\"selectpicker form-control\" name=\"subject\" id=\"subject\" data-live-search=\"true\" ><option data-icon=\"fa fa-user\">Select Supplier...</option>"+
                  "</select></div></div><div class=\"form-group\"><label class=\"col-sm-2 control-label form-label\">Period<span class=\"color10\">*</span></label>"+
                "<div class=\"col-sm-10\"><div class=\"control-group\"><div class=\"controls\"><div class=\"input-prepend input-group\"><span class=\"add-on input-group-addon\"><i class=\"fa fa-calendar\"></i></span>"+
                "<input type=\"text\" id=\"date-period\" class=\"form-control\" name=\"date\"/></div></div></div></div></div><div class=\"form-group\" style=\"padding-right:0\">"+
                  "<div class=\"checkbox checkbox-primary\" style=\"margin:0\"><input id=\"viewall\" name=\"viewall\" type=\"checkbox\"><label for=\"viewall\">View All</label></div></div></form>",
            html: true,
            showCancelButton: true,
            confirmButtonText: "View Report",
            cancelButtonText: "Cancel",
            closeOnConfirm: false
            },
            function(isConfirm){
              if (isConfirm) {
                var rid = id; 
                var sid = parseInt($('#subject').val(), 10);
                var period = $('#date-period').val();
                var all = '';
                if($('#viewall').is(':checked')){
                  all = 'true';
                }

                if (all == "" && period == "") {
                  all = 'true';
                };
                //alert('reports.php?id='+rid+'&sid='+sid+'&period='+period+'&all='+all);
                window.open('reports.php?id='+id+'&sid='+sid+'&period='+period+'&all='+all);             
              } else {
                swal("Cancelled", "Your have chosen not to view report.", "info");
              }
            }
        );

        var ul = $('#subject');
        ul.empty();
        $.get(System.coreRoot + subjectUrl, function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select One...</option>');
            tp.appendTo(ul);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem['name']+'</span></option>');
              tpl.appendTo(ul);
            });
            
            setTimeout(function() {
                $('#date-period').daterangepicker({ format: 'DD/MM/YYYY', maxDate: moment().format('DD/MM/YYYY')  }, function(start, end, label) {});
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
                $('.selectpicker').css('margin', 0);
                $('.sweet-alert').css('overflow', 'visible');
                $('.daterangepicker.dropdown-menu').css('z-index', 300000);
            }, 300);
        });
      }

    };
    
    View.FinancialReports = Marionette.CompositeView.extend({

      template: financialRptsTpl,

      events: {
        'click .reports tr > td:nth-child(2)': 'selectModal'
      },

      onShow: function(){
        //this.setup();
      },

      selectModal: function(e){
        e.preventDefault();
        e.stopPropagation();

        var id = parseInt($(e.currentTarget).find('a').data('rid'), 10);

        switch(id){
          case 100:
            View.Modals.dateRangeModal(id, 'Profit & Loss Statement');
            break;

          case 101:
            View.Modals.dateModal(id, 'Trial Balance');
            break;

          case 102:
            View.Modals.dateModal(id, 'Balance Sheet');
            break;

          case 103:
            View.Modals.dateRangeModal(id, 'Statement of Cash Flows');
            break;

          case 110:
            View.Modals.rangeModal(id, 'View Transactions');
            break;

          case 111:
            View.Modals.subjectRangeModal(id, 'Ledger Statement', '/service/finance/index.php?allLedgers');
            break;

          case 112:
            //Debtors list
            window.open('reports.php?id='+id);
            break;

          case 113:
            //Creditors list
            window.open('reports.php?id='+id);
            break;

          case 120:
            //Todays Revenues
            window.open('reports.php?id='+id);
            break;

          case 121:
            View.Modals.rangeModal(id, 'Revenue Report');
            break;

          case 122:
            View.Modals.subjectRangeModal(id, 'Revenue By Cashier/Agent', '/service/tools/index.php?users');
            break;

          case 123:
            View.Modals.subjectRangeModal(id, 'Revenue By Item', '/service/operations/index.php?services');
            break;

          case 124:
            View.Modals.subjectRangeModal(id, 'Revenue By Client', '/service/crm/index.php?clients');
            break;

          case 130:
            //Todays Expenses
            window.open('reports.php?id='+id);
            break;

          case 131:
            View.Modals.rangeModal(id, 'Expenses Report');
            break;

          case 132:
            View.Modals.subjectRangeModal(id, 'Expenses By Category', '/service/finance/index.php?ledgerType="Expenses"');
            break;

          case 133:
            View.Modals.subjectInputModal(id, 'Expenses By Description');
            break;

          case 134:
            View.Modals.subjectRangeModal(id, 'Expenses By Supplier', '/service/procurement/index.php?suppliers');
            break;

          case 135:
            View.Modals.subjectRangeModal(id, 'Claims Per Employee', '/service/hrm/index.php?employees');
            break;

          default:
            //statements_def
            break;
        }
      }
    });

    View.ClientReports = Marionette.CompositeView.extend({

      template: clientRptsTpl,

      events: {
        'click .reports tr > td:nth-child(2)': 'selectModal'
      },

      onShow: function(){
        //this.setup();
      },

      selectModal: function(e){
        e.preventDefault();
        e.stopPropagation();

        var id = parseInt($(e.currentTarget).find('a').data('rid'), 10);

        switch(id){
          case 200:
            //Client register
            window.open('reports.php?id='+id);
            break;

          case 201:
            //Client quotations
            View.Modals.subjectRangeModal(id, 'Client Quotations', '/service/crm/index.php?clients');
            break;

          case 202:
            //Client statements
            View.Modals.subjectRangeModal(id, 'Client Statement', '/service/crm/index.php?clients');
            break;

          case 203:
            //Client Sales
            View.Modals.rangeModal(id, 'Sales Invoices');
            break;

          case 204:
            //Client Quotations
            View.Modals.rangeModal(id, 'Quotations');
            break;

          default:
            //statements_def
            break;
        }
      }
    });

    View.ProcurementReports = Marionette.CompositeView.extend({

      template: procurementRptsTpl,

      events: {
        'click .reports tr > td:nth-child(2)': 'selectModal'
      },

      onShow: function(){
        //this.setup();
      },

      selectModal: function(e){
        e.preventDefault();
        e.stopPropagation();

        var id = parseInt($(e.currentTarget).find('a').data('rid'), 10);

        switch(id){
          case 300:
            //Supplier register
            window.open('reports.php?id='+id);
            break;

          case 301:
            //Supplier quotations
            View.Modals.subjectRangeModal(id, 'Supplier Quotations', '/service/procurement/index.php?suppliers');
            break;

          case 302:
            //Supplier statements
            View.Modals.subjectRangeModal(id, 'Supplier Statement', '/service/procurement/index.php?suppliers');
            break;

          case 303:
            //Supplier Sales
            View.Modals.rangeModal(id, 'Purchase Invoices');
            break;

          case 304:
            //Supplier Quotations
            View.Modals.rangeModal(id, 'Purchase Orders');
            break;

          default:
            //statements_def
            break;
        }
      }   
    });

  });

  return System.ReportsApp.Show.View;
});