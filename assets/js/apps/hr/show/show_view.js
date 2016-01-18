define(["app", "tpl!apps/templates/employees.tpl", "tpl!apps/templates/employee.tpl", "tpl!apps/templates/allowance.tpl", "tpl!apps/templates/overtime.tpl",
   "tpl!apps/templates/advance.tpl", "tpl!apps/templates/salary.tpl", "tpl!apps/templates/payroll.tpl", "tpl!apps/templates/employeetx.tpl", "backbone.syphon"], 
	function(System, employeesTpl, employeeTpl, allowanceTpl, overtimeTpl, advanceTpl, salaryTpl, payrollTpl, employeeTxTpl){
  System.module('HRApp.Show.View', function(View, System, Backbone, Marionette, $, _){
    
    View.Employees = Marionette.CompositeView.extend({

      template: employeesTpl,

      onShow: function(){
        this.setup();
      },

      setup: function(){
          var THAT = this;
          var ext = 'employees';
          if (this.model.get('type') == 'exemployees') {
            ext = 'exemployees';
          }

          var ul = $('tbody');
          ul.empty();
          $.get(System.coreRoot + '/service/hrm/index.php?'+ext, function(result) {
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
            $('#esalary').val(m['salary']['amount']);

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

    View.Allowance = Marionette.ItemView.extend({

        template: allowanceTpl,

        events: {
          "click .ediscard": "setup",
          "click .esubmit": "postAllowance"
        },

        onShow: function(){                  
          $('.loading').hide();
          this.setup();
        },

        setup: function(){          
          var ul = $('#employees');
          ul.empty();
          var ulc = $('#expenses');
          ulc.empty();

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
          $('button').prop({disabled: false});
          $('form input, form textarea').val('');
          $('#date-picker').daterangepicker({ singleDatePicker: true, format: 'DD/MM/YYYY', maxDate: moment().format('DD/MM/YYYY') }, function(start, end, label) {});
        },

        postAllowance: function(e) { 
          $('button').prop({disabled: true});
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          
          data['id'] = parseInt(data['id'], 10);
          data['ledger'] = parseInt(data['ledger'], 10);
          
          if (data['id'] && data['date'] && data['ledger'] && data['amount'] != 0 && data['descr'] != "") {
              //alert(JSON.stringify(data));
              this.trigger("post", data);
          }else{
              swal("Missing Information!", "Ensure all fields are filled!", "info");
              $('button').prop({disabled: false});
          }
        },

        onSuccess: function(voucher) {
          swal("Success!", "The employee allowance has been posted.", "success");
          //window.open("report.php?id=2&voucher=" + voucher);
          var rform = document.createElement("form");
          rform.target = "_blank";
          rform.method = "POST"; // or "post" if appropriate
          rform.action = "empvoucher.php";

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
          swal("Error!", "Transaction failed! Try again later.", "error");   
          $('button').prop({disabled: false});       
        }
    });

    View.Overtime = Marionette.ItemView.extend({

        template: overtimeTpl,

        events: {
          "click .ediscard": "setup",
          "click .esubmit": "postOvertime"
        },

        onShow: function(){                  
          $('.loading').hide();
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
 
          $('button').prop({disabled: false});
          $('form input, form textarea').val('');
          $('#date-picker').daterangepicker({ singleDatePicker: true, format: 'DD/MM/YYYY', maxDate: moment().format('DD/MM/YYYY') }, function(start, end, label) {});
        },

        postOvertime: function(e) { 
          $('button').prop({disabled: true});
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);

          data['id'] = parseInt(data['id'], 10);
          
          if (data['id'] && data['date'] && data['rate'] != 0 && data['hours'] != 0 && data['descr'] != "") {
              //alert(JSON.stringify(data));
              this.trigger("post", data);
          }else{
              swal("Missing Information!", "Ensure all fields are filled!", "info");
              $('button').prop({disabled: false});
          }
        },

        onSuccess: function(voucher) {
          swal("Success!", "The employee overtime has been posted.", "success");
          //window.open("report.php?id=2&voucher=" + voucher);
          var rform = document.createElement("form");
          rform.target = "_blank";
          rform.method = "POST"; // or "post" if appropriate
          rform.action = "empvoucher.php";

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
          swal("Error!", "Transaction failed! Try again later.", "error");   
          $('button').prop({disabled: false});       
        }
    });

    View.Advance = Marionette.ItemView.extend({

        template: advanceTpl,

        events: {
          "click .ediscard": "setup",
          "click .esubmit": "payAdvance"
        },

        onShow: function(){                  
          $('.loading').hide();
          this.setup();
        },

        setup: function(){          
          var ul = $('#employees');
          ul.empty();
          var uld = $('#banks');
          uld.empty();

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
 
          $('button').prop({disabled: false});
          $('form input, form textarea').val('');
          $('#date-picker').daterangepicker({ singleDatePicker: true, format: 'DD/MM/YYYY', maxDate: moment().format('DD/MM/YYYY') }, function(start, end, label) {});
        },

        payAdvance: function(e) { 
          $('button').prop({disabled: true});
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);

          data['id'] = parseInt(data['id'], 10);
          
          if (data['id'] && data['date'] && data['amount'] != 0 && data['descr'] != "") {
              //alert(JSON.stringify(data));
              this.trigger("post", data);
          }else{
              swal("Missing Information!", "Ensure all fields are filled!", "info");
              $('button').prop({disabled: false});
          }
        },

        onSuccess: function(voucher) {
          swal("Success!", "The employee has been advanced.", "success");
          //window.open("report.php?id=2&voucher=" + voucher);
          var rform = document.createElement("form");
          rform.target = "_blank";
          rform.method = "POST"; // or "post" if appropriate
          rform.action = "empvoucher.php";

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
          swal("Error!", "Transaction failed! Try again later.", "error");   
          $('button').prop({disabled: false});       
        }
    });

    View.PaySalary = Marionette.ItemView.extend({

        template: salaryTpl,

        events: {
          "click .ediscard": "setup",
          "click .esubmit": "paySalary",
          "change #employees": "getSlips",
          "change #slips": "setAmount"
        },

        onShow: function(){                  
          $('.loading').hide();
          this.setup();
        },

        setup: function(){
          var ul = $('#employees');
          ul.empty();
          var uld = $('#banks');
          uld.empty();


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
 
          $('button').prop({disabled: false});
          $('form input, form textarea').val('');
        },

        getSlips: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          data['employee'] = parseInt(data['employee'], 10);
          
          if (data['employee']) {
            var ul = $('#slips');
            ul.empty();
            $.get(System.coreRoot + '/service/hrm/index.php?unclearedslips&empid='+data['employee'], function(result) {
              var m = JSON.parse(result);

              var tp = $('<option data-icon="fa fa-clock-o" value = "0" >Select Payslip ...</option>');
              tp.appendTo(ul);

              System.payslips = m;          
              m.forEach(function(elem, index){
                var tpl = $('<option data-icon="fa fa-clock-o" data-index="'+index+'" value="'+elem.id+'">'+elem.month+'</option>');
                tpl.appendTo(ul);
              });
              
              setTimeout(function() {
                  $('.selectpicker').selectpicker('refresh');
              }, 300);
            });

          }else{
            swal("Error!", "Select an employee first!", "error");
          }
        },

        setAmount: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          var ind = parseInt($("#slips option:selected").data('index'), 10);

          if (ind >= 0) {
            var slip = System.payslips[ind];
            $('#amount').val(slip.netpay);
          }else{
            swal("Error!", "Select a payslip first!", "error");
          }
        },

        paySalary: function(e) { 
          $('button').prop({disabled: true});
          e.preventDefault();
          e.stopPropagation();
          var data = Backbone.Syphon.serialize(this);
          
          data['employee'] = parseInt(data['employee'], 10);
          data['ledger'] = parseInt(data['ledger'], 10);
          
          if (data['employee'] && data['slip'] && data['amount'] != 0 && data['ledger'] && data['mode']) {
              //alert(JSON.stringify(data));
              this.trigger("post", data);
          }else{
              swal("Missing Information!", "Ensure all fields are filled!", "info");
              $('button').prop({disabled: false});
          }
        },

        onSuccess: function(voucher) {
          swal("Success!", "The employee salary has been paid.", "success");
          //window.open("report.php?id=2&voucher=" + voucher);

          var ind = parseInt($("#slips option:selected").data('index'), 10);

          var payslip = System.payslips[ind];

          var rform = document.createElement("form");
          rform.target = "_blank";
          rform.method = "POST"; // or "post" if appropriate

          rform.action = "payslip.php";
          
          var slip = document.createElement("input");
          slip.name = "payslip";
          slip.value = JSON.stringify(payslip);
          rform.appendChild(slip);

          document.body.appendChild(rform);

          rform.submit();
          rform.parentNode.removeChild(rform);

          this.setup();

        },

        onError: function(e) { 
          swal("Error!", "Transaction failed! Try again later.", "error");   
          $('button').prop({disabled: false});       
        }
    });

    View.Payroll = Marionette.ItemView.extend({

        template: payrollTpl,

        events: {
          "click .pgenerate": "generate",
          "click .ppreview": "preview",
          "click .pcommit": "commit",
          "click .ppayslips": "payslips"
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

          $('#month-picker').daterangepicker({
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
              
              $('#month-picker').val('');
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
          //$('.pcommit').prop({disabled: true});
          $('.ppayslips').attr({disabled: true});
          $('.pcommit').attr({disabled: true});
        },

        preview: function(e) { 
          e.preventDefault();
          e.stopPropagation();

          var data = Backbone.Syphon.serialize(this);
          
          if (data['month']) {
            //alert(JSON.stringify(data));
            this.trigger("preview", data);
          }else{
            swal("Missing Data!", "Please set month!", "info");
          }
        },

        commit: function(e) { 
          e.preventDefault();
          e.stopPropagation();
          $('.pcommit').attr({disabled: true});

          var data = Backbone.Syphon.serialize(this);
          
          if (data['month']) {
            //alert(JSON.stringify(data));
            this.trigger("commit", data);
          }else{
            swal("Missing Data!", "Please set month!", "info");
            $('.pcommit').attr({disabled: false});
          }
        },

        payslips: function(e) { 
          e.preventDefault();
          e.stopPropagation();

          var THAT = this;

          System.payroll.slips.forEach(function(element, index){
            THAT.printSlip(element);
          });
        },

        reverseTx: function(txid) {        
          $('#results').find('.txid').each(function() {
            if (parseInt($(this).text(), 10) == txid) {
              $(this).parent().empty().append($('<a class="btn btn-info" href="#"><i class="fa fa-exclamation" style="margin: 0px;"></i></a>'));
            };
          });
        },

        onSuccess: function(result, category) {
          swal("Query Complete!", result.slips.length + " payslips found.", "success");
          var THAT = this;
          var el = $('#results');
          el.empty();

          System.payroll = result;

          result.slips.forEach(function(entry, index){
            var tpl = $('<tr><td>'+parseInt(entry.employee.id)+'</td><td>'+entry.employee.name+'</td><td>'+entry.employee.position+' [Department: '+entry.employee.department+']</td><td>Ksh. '+(parseFloat(entry.salary)).formatMoney(2, '.', ',')+'</td>'+
                '<td>Ksh. '+(parseFloat(entry.t_additions)).formatMoney(2, '.', ',')+'</td><td>Ksh. '+(parseFloat(entry.t_deductions)).formatMoney(2, '.', ',')+'</td><td>Ksh. '+(parseFloat(entry.netpay)).formatMoney(2, '.', ',')+'</td><td><p class="psid" style="display: none;">'+index+'</p><a class="btn viewslip" href="#"><i class="fa fa-print" style="margin: 0px;"></i></a></td></tr>');
            tpl.appendTo(el);
          });

          var months = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];
          //var mon = (new Date()).getMonth();

          var pm = ($('#month-picker').val()).split('/');
          var mon = parseInt(pm[0], 10) - 1;

          $('#prmonth').text(months[mon]+', '+pm[1]);
          
          $('#prstat').text(result.status);

          if (result.status == 'COMMITED') {
            $('.pcommit').attr({disabled: true});
            $('.pcommit').removeClass('btn-option1');
          } else{
            $('.pcommit').attr({disabled: false});
            $('.pcommit').addClass('btn-option1');
          };

          setTimeout(function() {
            $('.viewslip').on('click', function(e){
              e.preventDefault();
              e.stopPropagation();
              var psid = $(this).parent().find('.psid').text();
              THAT.printSlip(System.payroll.slips[parseInt(psid, 10)]);                  
            });
          }, 500);
        },

        printSlip: function(payslip) {
          var rform = document.createElement("form");
          rform.target = "_blank";
          rform.method = "POST"; // or "post" if appropriate

          rform.action = "payslip.php";
          
          var slip = document.createElement("input");
          slip.name = "payslip";
          slip.value = JSON.stringify(payslip);
          rform.appendChild(slip);

          document.body.appendChild(rform);

          rform.submit();
          rform.parentNode.removeChild(rform);
          //window.open("report.php?id=1&voucher=" + voucher);
        },

        onEmpty: function(e) { 
          var el = $('tbody');
          el.empty();
          swal("No Result!", "No entries found matching your parameters!", "error");
          $('.pcommit').attr({disabled: true});     
        },

        onError: function(e) { 
          swal("Error!", "Search failed! try again later.", "error");
          $('.pcommit').attr({disabled: true});    
        }
    });

    View.EmployeeTx = Marionette.ItemView.extend({      

        template: employeeTxTpl,

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

          var ul = $('#employees');
          ul.empty();
          $.get(System.coreRoot + '/service/hrm/index.php?employees', function(result) {
            var m = JSON.parse(result);
            var tp = $('<option data-icon="fa fa-user">Select employee...</option>');
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
          data['employee'] = parseInt(data['employee'], 10);
          
          if (data['employee'] && (data['dates'] != '' || data['vall'] != false)) {
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
            var tpl = $('<tr><td>'+entry['transactionId']+'</td><td>'+entry['date']+'</td><td>'+entry['type']+'</td><td>'+entry['effect'].toUpperCase()+'</td><td>Ksh. '+(parseFloat(entry['amount'])).formatMoney(2, '.', ',')+'</td>'+
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
          rform.action = "empvoucher.php";
          /*if (voucher.type.toLowerCase().indexOf('payment') >= 0) {
            rform.action = "payment.php";
          }else if(voucher.type.toLowerCase().indexOf('invoice') >= 0){
            rform.action = "grn.php";
          }else{
            rform.action = "order.php";
          }*/
          
          var vouch = document.createElement("input");
          vouch.name = "voucher";
          vouch.value = JSON.stringify(voucher);
          rform.appendChild(vouch);

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

  return System.HRApp.Show.View;
});