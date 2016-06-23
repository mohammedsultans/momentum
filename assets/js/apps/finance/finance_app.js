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
        "expenses" : "expenses",
        "crNote" : "crnote",
        "cashCrNote" : "cashcrnote"
      }
    });

    System.on("raise:invoice", function(){
      System.navigate("invoicing");
      showController.raiseGeneralInvoice();
    });

    System.addInitializer(function(){
      new FinanceApp.Router({
        controller: showController
      });
    });
  });

  return System.FinanceApp;
});

