define(["app", "apps/hr/show/show_view"], function(System, View){
  System.module('HRApp.Show', function(Show, System, Backbone, Marionette, $, _){
    Show.Controller = {

      addEmployee: function(){ 
        var view = new View.Employee();
        
        System.contentRegion.show(view);

        view.on('create', function(data) {
          data['operation'] = 'addEmployee';
            $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("success");
              }else{
                view.triggerMethod("error");
              }
            });
        });

        view.on('edit', function(data) {
          data['operation'] = 'editEmployee';
            $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("success");
              }else{
                view.triggerMethod("error");
              }
            });
        });

        view.on('del', function(data) {
          data['operation'] = 'deleteEmployee';
            $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
              if (result == 1) {
                view.triggerMethod("delete");
              }else{
                view.triggerMethod("error");
              }
            });
        });
      },

      showEmployees: function(){ 
        var x = Backbone.Model.extend({
          urlRoot: "service/hrm",
        });
        var model = new x;
        
        model.set('type', 'employees');

        var view = new View.Employees({model: model});
        System.contentRegion.show(view);

        view.on('del', function(id) {
          var data = {};
          data['operation'] = 'deleteEmployee';
          data['id'] = id;
          $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
            if (result == 1) {
              view.triggerMethod("delete");
            }else{
              view.triggerMethod("error");
            }
          });
        });
	    },

      postAllowance: function(){ 
        var view = new View.Allowance();

        System.contentRegion.show(view);

        view.on('post', function(data) {
          data['operation'] = 'postAllowance';
          $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
            if (result != 0) {
              var res = JSON.parse(result);
              //alert(JSON.stringify(res));
              if (res['transactionId']) {
                view.triggerMethod("success", res);
              }else{
                view.triggerMethod("error");
              }
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      postOvertime: function(){ 
        var view = new View.Overtime();

        System.contentRegion.show(view);

        view.on('post', function(data) {
          data['operation'] = 'postOvertime';
          $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
            if (result != 0) {
              var res = JSON.parse(result);
              //alert(JSON.stringify(res));
              if (res['transactionId']) {
                view.triggerMethod("success", res);
              }else{
                view.triggerMethod("error");
              }
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      salaryAdvance: function(){ 
        var view = new View.Advance();

        System.contentRegion.show(view);

        view.on('post', function(data) {
          data['operation'] = 'payAdvance';
          $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
            if (result != 0) {
              var res = JSON.parse(result);
              //alert(JSON.stringify(res));
              if (res['transactionId']) {
                view.triggerMethod("success", res);
              }else{
                view.triggerMethod("error");
              }
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      payroll: function(){ 
        var view = new View.Payroll();

        System.contentRegion.show(view);

        view.on('generate', function(data) {
          data['operation'] = 'generatePayroll';
          $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
            if (result != 0) {
              var res = JSON.parse(result);
              //alert(JSON.stringify(res));
              if (res.slips.length > 0) {
                view.triggerMethod("success", res, 'generate');
              }else{
                view.triggerMethod("error");
              }
            }else{
              view.triggerMethod("error");
            }
          });
        });

        view.on('preview', function(data) {
          data['operation'] = 'previewPayroll';
          $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
            if (result != 0) {
              var res = JSON.parse(result);
              //alert(JSON.stringify(res));
              if (res.slips.length > 0) {
                view.triggerMethod("success", res, 'preview');
              }else{
                view.triggerMethod("error");
              }
            }else{
              view.triggerMethod("error");
            }
          });
        });

        view.on('commit', function(data) {
          data['operation'] = 'commitPayroll';
          $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
            if (result != 0) {
              var res = JSON.parse(result);
              //alert(JSON.stringify(res));
              if (res.slips.length > 0) {
                view.triggerMethod("success", res, 'commit');
              }else{
                view.triggerMethod("error");
              }
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      paySalaries: function(){ 
        var view = new View.PaySalary();

        System.contentRegion.show(view);

        view.on('post', function(data) {
          data['operation'] = 'paySalary';
          $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
            if (result != 0) {
              var res = JSON.parse(result);
              //alert(JSON.stringify(res));
              if (res['transactionId']) {
                view.triggerMethod("success", res);
              }else{
                view.triggerMethod("error");
              }
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      exEmployees: function(){ 
        var x = Backbone.Model.extend({
          urlRoot: "service/hrm",
        });
        var model = new x;

        model.set('type', 'exemployees');

        var view = new View.Employees({model: model});
        System.contentRegion.show(view);

        view.on('del', function(id) {
          var data = {};
          data['operation'] = 'deleteEmployee';
          data['id'] = id;
          $.post(System.coreRoot + '/service/hrm/index.php', data, function(result) {
            if (result == 1) {
              view.triggerMethod("delete");
            }else{
              view.triggerMethod("error");
            }
          });
        });
      }
      
    };
  });

  return System.HRApp.Show.Controller;
});
