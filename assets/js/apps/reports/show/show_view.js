define(["app", "tpl!apps/templates/financialRpts.tpl"], 
	function(System, financialRptsTpl){
  System.module('ReportsApp.Show.View', function(View, System, Backbone, Marionette, $, _){
    
    View.FinancialReports = Marionette.CompositeView.extend({

      template: financialRptsTpl,

      events: {
        'click .reports tr > td:nth-child(2) > a': 'viewModal'
      },

      onShow: function(){
        //this.setup();
      },

      setup: function(){
          /*var THAT = this;
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
            
          });*/
        },

        viewModal: function(e){
          e.preventDefault();
          e.stopPropagation();
          
          swal({
            title: "Report Title",
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
              alert($(e.currentTarget).data('link'));              
            } else {
              swal("Cancelled", "Your have chosen not to view report.", "info");
            }
          });

          var ul = $('#subject');
          ul.empty();
          $.get(System.coreRoot + '/service/procurement/index.php?suppliers', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select Supplier...</option>');
            tp.appendTo(ul);
            
            m.forEach(function(elem){
              var tpl = $('<option data-icon="fa fa-user" value="'+elem['id']+'">'+elem['name']+'</span></option>');
              tpl.appendTo(ul);
            });
            
            setTimeout(function() {
                $('#date-period').daterangepicker({ format: 'DD/MM/YYYY' }, function(start, end, label) {});
                $('.selectpicker').selectpicker();
                $('.selectpicker').selectpicker('refresh');
                $('.selectpicker').css('margin', 0);
                $('.sweet-alert').css('overflow', 'visible');
                $('.daterangepicker.dropdown-menu').css('z-index', 300000);
            }, 300);
          });
        }

    });

  });

  return System.ReportsApp.Show.View;
});