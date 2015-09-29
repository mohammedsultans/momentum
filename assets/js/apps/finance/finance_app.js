define(["app", "apps/finance/show/show_controller"], function(System, showController){
  System.module('FinanceApp', function(FinanceApp, System, Backbone, Marionette, $, _){

    FinanceApp.Router = Marionette.AppRouter.extend({
      appRoutes: {
        "qinvoicing" : "raiseQuoteInvoice",
        "ginvoicing" : "raiseGeneralInvoice",
        "payments" : "receivePayment",
        "transactions" : "findTx",
        "clientTx" : "findClientTx",
        "ledgers" : "accountLedgers",
        "chart" : "accountChart",
        "ledgerTx" : "ledgerTx",
        "claims" : "claims",
        "bankTx" : "bankTx",
        "capital" : "capital",
        "expenses" : "expenses"
      }
    });

    var API = {
      raiseQuoteInvoice: function(){
        //System.contentRegion.show();
        showController.raiseQuoteInvoice();
        //System.execute("set:active:header", "Menu");
      },

      raiseGeneralInvoice: function(){
        //System.contentRegion.show();
        showController.raiseGeneralInvoice();
        //System.execute("set:active:header", "Menu");
      },

      receivePayment: function(a){
        //System.contentRegion.show();
        showController.receivePayment(a);
        //System.execute("set:active:header", "Menu");
      },

      findTx: function(){
        //System.contentRegion.show();
        showController.findTx();
        //System.execute("set:active:header", "Menu");
      },

      findClientTx: function(){
        //System.contentRegion.show();
        showController.findClientTx();
        //System.execute("set:active:header", "Menu");
      },

      accountLedgers: function(){
        //System.contentRegion.show();
        showController.accountLedgers();
        //System.execute("set:active:header", "Menu");
      },

      accountChart: function(){
        //System.contentRegion.show();
        showController.accountChart();
        //System.execute("set:active:header", "Menu");
      },

      ledgerTx: function(){
        //System.contentRegion.show();
        showController.ledgerTX();
        //System.execute("set:active:header", "Menu");
      },

      claims: function(){
        //System.contentRegion.show();
        showController.claims();
        //System.execute("set:active:header", "Menu");
      },

      bankTx: function(){
        //System.contentRegion.show();
        showController.bankTx();
        //System.execute("set:active:header", "Menu");
      },

      capital: function(){
        //System.contentRegion.show();
        showController.capital();
        //System.execute("set:active:header", "Menu");
      },

      expenses: function(){
        //System.contentRegion.show();
        showController.expenses();
        //System.execute("set:active:header", "Menu");
      }
    };

    System.on("raise:invoice", function(){
      System.navigate("invoicing");
      API.addProject();
    });

    System.addInitializer(function(){
      new FinanceApp.Router({
        controller: API
      });
    });
  });

  return System.FinanceApp;
});

