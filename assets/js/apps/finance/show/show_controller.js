define(["app", "apps/finance/show/show_view"], function(System, View){
  System.module('FinanceApp.Show', function(Show, System, Backbone, Marionette, $, _){
    Show.Controller = {
      
      raiseQuoteInvoice: function(a){ 
        var view = new View.QuoteInvoice();
        
        System.contentRegion.show(view);

        view.on('post', function(data) {
          data['operation'] = 'postQuoteInvoice';
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
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

      raiseGeneralInvoice: function(a){ 
        var view = new View.GeneralInvoice();
        
        System.contentRegion.show(view);

        view.on('post', function(data) {
          data['operation'] = 'postGenInvoice';
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
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

      receivePayment: function(a){ 
        var view = new View.Payment();

        System.contentRegion.show(view);

        view.on('submit', function(data) {
          data['operation'] = 'receivePayment';
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
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

      accountLedgers: function(a){ 
        var view = new View.Ledgers();
        
        System.contentRegion.show(view);

        view.on('create', function(data) {
          data['operation'] = 'createLedger';
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
            if (result == 1) {
              view.triggerMethod("success");     
            }else{
              view.triggerMethod("error");
            }
          });
        });

        view.on('delete', function(lid) {
          data = {};
          data['operation'] = 'deleteLedger';
          data['lid'] = lid;
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
            if (result == 1) {
              view.triggerMethod("success");     
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      accountChart: function(a){ 
        var view = new View.Chart();
        
        System.contentRegion.show(view);

        view.on('create', function(data) {
          data['operation'] = 'create';
            $.post('http://192.168.43.29/chases/leads.php', data, function(result) {
              if (result == 1) {
                alert('Success: Lead created');
                //admin.triggerMethod("form:done");
              };
            });
        });
      },

      ledgerTX: function(){ 
        var view = new View.LedgerTransactions();

        System.contentRegion.show(view);

        view.on('post', function(data) {
          data['operation'] = 'postTransaction';
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
            if (result == 1) {
              view.triggerMethod("success");     
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      findTx: function(){ 
        var view = new View.FindTransactions();
        
        System.contentRegion.show(view);

        view.on('search', function(data) {
          data['operation'] = 'findEntries';
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
            if (result != 0) {
              var res = JSON.parse(result);
              //alert(JSON.stringify(res));
              if (res.length) {
                view.triggerMethod("success", res);
              }else{
                view.triggerMethod("empty");
              }              
            }else{
              view.triggerMethod("error");
            }
          });
        });

        view.on('reverse', function(txid) {
          var data = {};
          data['operation'] = 'reverseTX';
          data['txid'] = txid;
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
            if (result == 1) {
              view.triggerMethod("reversalsuccess", txid);
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      findClientTx: function(){ 
        var view = new View.FindClientTransactions();
        
        System.contentRegion.show(view);

        view.on('search', function(data) {
          data['operation'] = 'findClientEntries';
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
            if (result != 0) {
              var res = JSON.parse(result);
              //alert(JSON.stringify(res));
              if (res.length) {
                view.triggerMethod("success", res);
              }else{
                view.triggerMethod("empty");
              }              
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      claims: function(){ 
        var view = new View.Claims();

        System.contentRegion.show(view);

        view.on('authorize', function(data) {
          data['operation'] = 'processClaim';
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
            if (result != 0) {
              var res = JSON.parse(result);
              //alert(JSON.stringify(res));
              if (res['transactionId']) {
                view.triggerMethod("success", res);
              }else{
                view.triggerMethod("empty");
              }              
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      bankTx: function(){ 
        var view = new View.BankTransactions();
        
        System.contentRegion.show(view);

        view.on('post', function(data) {
          data['operation'] = 'postBankTx';
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
            if (result == 1) {
              view.triggerMethod("success");     
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      capital: function(a){ 
        var view = new View.Capital();

        System.contentRegion.show(view);

        view.on('post', function(data) {
          data['operation'] = 'injectCapital';
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
            if (result == 1) {
              view.triggerMethod("success");     
            }else{
              view.triggerMethod("error");
            }
          });
        });
      },

      expenses: function(a){ 
        var view = new View.Expenses();

        System.contentRegion.show(view);

        view.on('post', function(data) {
          data['operation'] = 'postExpense';
          $.post(System.coreRoot + '/service/finance/index.php', data, function(result) {
            if (result == 1) {
              view.triggerMethod("success");     
            }else{
              view.triggerMethod("error");
            }
          });
        });
      }      
    };
  });

  return System.FinanceApp.Show.Controller;
});
