define(["app", "tpl!apps/templates/financialRpts.tpl", "tpl!apps/templates/clientRpts.tpl", "tpl!apps/templates/procurementRpts.tpl", 
  "tpl!apps/templates/hrRpts.tpl", "tpl!apps/templates/projectRpts.tpl"], 
	function(System, financialRptsTpl, clientRptsTpl, procurementRptsTpl, hrRptsTpl, projectRptsTpl){
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

      monthModal: function(id, title){
        swal({
            title: title,
            text: "<form class=\"form-horizontal\" id=\"frmi1\"><div class=\"form-group\"><label class=\"col-sm-2 control-label form-label\">Month</label>"+
                "<div class=\"col-sm-10\"><div class=\"control-group\"><div class=\"controls\"><div class=\"input-prepend input-group\"><span class=\"add-on input-group-addon\"><i class=\"fa fa-calendar\"></i></span>"+
                "<input type=\"text\" id=\"month-single\" class=\"form-control\" name=\"month\"/ value=\""+moment().format('MM/YYYY')+"\"></div></div></div></div></div></form>",
            html: true,
            showCancelButton: true,
            confirmButtonText: "View Report",
            cancelButtonText: "Cancel",
            closeOnConfirm: false
          },
          function(isConfirm){
              if (isConfirm) {
                var month = $('#month-single').val();
                //alert('reports.php?id='+rid+'&sid='+sid+'&period='+period+'&all='+all);
                window.open('reports.php?id='+id+'&month='+month);             
              } else {
                swal("Cancelled", "Your have chosen not to view report.", "info");
              }
          }
        );
 
        setTimeout(function() {
         //$('#month-single').daterangepicker({ singleDatePicker: true, format: 'MM/YYYY', maxDate: moment().format('MM/YYYY') }, function(start, end, label) {});
          $('#month-single').daterangepicker({
            singleDatePicker: true,
            format: 'MM/YYYY',
          }).on('hide.daterangepicker', function (ev, picker) {
            $('.table-condensed tbody tr:nth-child(2) td').click();
            //setTimeout(alert(picker.startDate.format('MM/YYYY')), 100)
            //alert(picker.startDate.format('MM/YYYY'))
            //alert($('.table-condensed thead .month').text())
            //$('#month-picker').val(picker.startDate.format('MM/YYYY'));
            var mon = moment().format('MM/YYYY');
            var mon2 = picker.startDate.format('MM/YYYY');

            var a1 = mon.split('/');
            var a2 = mon2.split('/');
            
           // alert(parseInt(a2[1], 10)+' - '+parseInt(a1[1], 10));
             //|| (parseInt(a2[1], 10) == parseInt(a1[1], 10) && parseInt(a2[0], 10) > parseInt(a1[0], 10))
             //(parseInt(a2[1], 10) > parseInt(a1[1], 10))
             //alert(parseInt(a2[0], 10) +'>'+ parseInt(a1[0], 10));
            if ((parseInt(a2[1], 10) > parseInt(a1[1], 10)) || ((parseInt(a2[1], 10) == parseInt(a1[1], 10)) && (parseInt(a2[0], 10) > parseInt(a1[0], 10)))) {
              
              $('#month-single').val('');
            }
            
            $('.table-condensed thead tr:nth-child(2)').hide();
            $('.table-condensed tbody').hide();
          }).on('show.daterangepicker', function (ev, picker) {
            $('.table-condensed thead tr:nth-child(2)').hide();
            $('.table-condensed tbody').hide();
          }).on('showCalendar.daterangepicker', function (ev, picker) {
            $('.table-condensed thead tr:nth-child(2)').hide();
            $('.table-condensed tbody').hide();
          });
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

      subjectModal: function(id, title, subjectUrl){

        swal({
            title: title,
            text: "<form class=\"form-horizontal\" id=\"frmi1\"><div class=\"form-group\"><label class=\"col-sm-2 control-label form-label\">Subject<span class=\"color10\">*</span></label><div class=\"col-sm-10\">"+
                  "<select class=\"selectpicker form-control\" name=\"subject\" id=\"subject\" data-live-search=\"true\" ><option data-icon=\"fa fa-user\">Select Supplier...</option></select></div></div></form>",
            html: true,
            showCancelButton: true,
            confirmButtonText: "View Report",
            cancelButtonText: "Cancel",
            closeOnConfirm: false
            },
            function(isConfirm){
              if (isConfirm) {
                var rid = id; 
                var sid = '';
                if (subjectUrl == '/service/operations/index.php?services') {
                  sid = $('#subject').val();
                }else{
                  sid = parseInt($('#subject').val(), 10);
                }
                window.open('reports.php?id='+id+'&sid='+sid);             
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
              var tpl = '';
              if (subjectUrl == '/service/crm/index.php?clients') {
                tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem.name+'<span style="font-size: 1px"> ['+elem['details']+']</span></option>');
              } else if (subjectUrl == '/service/tools/index.php?users') {
                tpl = $('<option data-icon="fa fa-user" value="'+elem.id+'">'+elem.record.name+'</option>');
              } else if (subjectUrl == '/service/operations/index.php?services') {
                tpl = $('<option data-icon="fa fa-user" value="'+elem.name+'">'+elem.name+'</option>');
              } else {
                tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem.name+'</option>');
              }
              tpl.appendTo(ul);
            });

            setTimeout(function() {
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
                $('.selectpicker').css('margin', 0);
                $('.sweet-alert').css('overflow', 'visible');
            }, 300);
        });
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
                var sid = '';
                if (subjectUrl == '/service/operations/index.php?services') {
                  sid = $('#subject').val();
                }else{
                  sid = parseInt($('#subject').val(), 10);
                }
               
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
              var tpl = '';
              if (subjectUrl == '/service/crm/index.php?clients') {
                tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem.name+'<span style="font-size: 1px"> ['+elem['details']+']</span></option>');
              } else if (subjectUrl == '/service/tools/index.php?users') {
                tpl = $('<option data-icon="fa fa-user" value="'+elem.id+'">'+elem.record.name+'</option>');
              } else if (subjectUrl == '/service/operations/index.php?services') {
                tpl = $('<option data-icon="fa fa-user" value="'+elem.name+'">'+elem.name+'</option>');
              } else {
                tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem.name+'</option>');
              }
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
      },

      subjectMonthModal: function(id, title, subjectUrl){
        swal({
            title: title,
            text: "<form class=\"form-horizontal\" id=\"frmi1\"><div class=\"form-group\"><label class=\"col-sm-2 control-label form-label\">Subject<span class=\"color10\">*</span></label><div class=\"col-sm-10\">"+
                  "<select class=\"selectpicker form-control\" name=\"subject\" id=\"subject\" data-live-search=\"true\" ><option data-icon=\"fa fa-user\">Select Supplier...</option>"+
                  "</select></div></div><div class=\"form-group\"><label class=\"col-sm-2 control-label form-label\">Month</label>"+
                "<div class=\"col-sm-10\"><div class=\"control-group\"><div class=\"controls\"><div class=\"input-prepend input-group\"><span class=\"add-on input-group-addon\"><i class=\"fa fa-calendar\"></i></span>"+
                "<input type=\"text\" id=\"month-single\" class=\"form-control\" name=\"month\"/ value=\""+moment().format('MM/YYYY')+"\"></div></div></div></div></div></form>",
            html: true,
            showCancelButton: true,
            confirmButtonText: "View Report",
            cancelButtonText: "Cancel",
            closeOnConfirm: false
          },
          function(isConfirm){
              if (isConfirm) {
                var sid = parseInt($('#subject').val(), 10);
                var month = $('#month-single').val();
                //alert('reports.php?id='+rid+'&sid='+sid+'&period='+period+'&all='+all);
                window.open('reports.php?id='+id+'&sid='+sid+'&month='+month);             
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
              var tpl = '';
              if (subjectUrl == '/service/crm/index.php?clients') {
                tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem['name']+'<span style="font-size: 1px"> ['+elem['details']+']</span></option>');
              }else {
                tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem['name']+'</option>');
              }
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
 
        setTimeout(function() {
         //$('#month-single').daterangepicker({ singleDatePicker: true, format: 'MM/YYYY', maxDate: moment().format('MM/YYYY') }, function(start, end, label) {});
          $('#month-single').daterangepicker({
            singleDatePicker: true,
            format: 'MM/YYYY',
          }).on('hide.daterangepicker', function (ev, picker) {
            $('.table-condensed tbody tr:nth-child(2) td').click();
            //setTimeout(alert(picker.startDate.format('MM/YYYY')), 100)
            //alert(picker.startDate.format('MM/YYYY'))
            //alert($('.table-condensed thead .month').text())
            //$('#month-picker').val(picker.startDate.format('MM/YYYY'));
            var mon = moment().format('MM/YYYY');
            var mon2 = picker.startDate.format('MM/YYYY');

            var a1 = mon.split('/');
            var a2 = mon2.split('/');
            
           // alert(parseInt(a2[1], 10)+' - '+parseInt(a1[1], 10));
             //|| (parseInt(a2[1], 10) == parseInt(a1[1], 10) && parseInt(a2[0], 10) > parseInt(a1[0], 10))
             //(parseInt(a2[1], 10) > parseInt(a1[1], 10))
             //alert(parseInt(a2[0], 10) +'>'+ parseInt(a1[0], 10));
            if ((parseInt(a2[1], 10) > parseInt(a1[1], 10)) || ((parseInt(a2[1], 10) == parseInt(a1[1], 10)) && (parseInt(a2[0], 10) > parseInt(a1[0], 10)))) {
              
              $('#month-single').val('');
            }
            
            $('.table-condensed thead tr:nth-child(2)').hide();
            $('.table-condensed tbody').hide();
          }).on('show.daterangepicker', function (ev, picker) {
            $('.table-condensed thead tr:nth-child(2)').hide();
            $('.table-condensed tbody').hide();
          }).on('showCalendar.daterangepicker', function (ev, picker) {
            $('.table-condensed thead tr:nth-child(2)').hide();
            $('.table-condensed tbody').hide();
          });
          $('.sweet-alert').css('overflow', 'visible');
          $('.daterangepicker.dropdown-menu').css('z-index', 300000);
        }, 150);
      },

      inputRangeModal: function(id, title){

        swal({
            title: title,
            text: "<form class=\"form-horizontal\" id=\"frmi1\"><div class=\"form-group\"><label class=\"col-sm-2 control-label form-label\">Description<span class=\"color10\">*</span></label><div class=\"col-sm-10\">"+
                  "<input type=\"text\" name=\"input\" id=\"input\" class=\"form-control\"></div></div>"+
                  "<div class=\"form-group\"><label class=\"col-sm-2 control-label form-label\">Period<span class=\"color10\">*</span></label>"+
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
                var sid = $('#input').val();
               
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
      },
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
            View.Modals.rangeModal(id, 'Cash Book');
            break;

          case 113:
            View.Modals.rangeModal(id, 'Credit Book');
            break;

          case 114:
            //Debtors list
            window.open('reports.php?id='+id);
            break;

          case 115:
            //Creditors list
            window.open('reports.php?id='+id);
            break;

          case 120:
            View.Modals.rangeModal(id, 'Revenue Report');
            break;

          case 121:
            View.Modals.subjectRangeModal(id, 'Revenue By User', '/service/tools/index.php?users');
            break;

          case 122:
            View.Modals.subjectRangeModal(id, 'Revenue By Item', '/service/operations/index.php?services');
            break;

          case 123:
            View.Modals.subjectRangeModal(id, 'Revenue By Client', '/service/crm/index.php?clients');
            break;

          case 130:
            View.Modals.rangeModal(id, 'Expenses Report');
            break;

          case 131:
            View.Modals.subjectRangeModal(id, 'Expenses By Category', '/service/finance/index.php?ledgerType="Expense"');
            break;

          case 132:
            View.Modals.inputRangeModal(id, 'Expenses By Description');
            break;

          case 133:
            View.Modals.subjectRangeModal(id, 'Expenses By Supplier', '/service/procurement/index.php?suppliers');
            break;

          case 134:
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

    View.HRReports = Marionette.CompositeView.extend({

      template: hrRptsTpl,

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
          case 400:
            //Supplier register
            window.open('reports.php?id='+id);
            break;

          case 401:
            View.Modals.subjectRangeModal(id, 'Employee Statement', '/service/hrm/index.php?employees');
            break;

          case 410:
            View.Modals.subjectMonthModal(id, 'Advances Report', '/service/hrm/index.php?employees');
            break;

          case 411:
            View.Modals.subjectMonthModal(id, 'Allowances Report', '/service/hrm/index.php?employees');
            break;

          case 412:
            View.Modals.subjectMonthModal(id, 'Overtime Report', '/service/hrm/index.php?employees');
            break;

          case 413:
            View.Modals.monthModal(id, 'Payroll Summary');
            break;

          default:
            //statements_def
            break;
        }
      }   
    });

    View.ProjectReports = Marionette.CompositeView.extend({

      template: projectRptsTpl,

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
          case 500:
            //Projects register
            window.open('reports.php?id='+id);
            break;

          case 501:
            //Project Report
            View.Modals.subjectModal(id, 'Project Report', '/service/operations/index.php?allProjects');
            break;

          case 502:
             //Minor Works Report
            View.Modals.subjectModal(id, 'Minor Works Receipts', '/service/crm/index.php?clients');
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