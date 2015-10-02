<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Banking Transactions</h1>
    <!-- Start Page Header Right Div -->
    <div class="right">
      <div class="btn-group" role="group" aria-label="...">
        <a href="#" class="btn btn-light"><i class="fa fa-remove"></i>Close</a>
      </div>
    </div>
    <!-- End Page Header Right Div -->
  </div>
  <!-- End Page Header -->

 <!-- //////////////////////////////////////////////////////////////////////////// --> 
  <!-- START CONTAINER -->
  <div class="container-padding">
    <!-- Start Row -->
    <div class="row">
      <div class="col-xs-12 col-sm-6">
        <div class="panel panel-default">

          <div class="panel-title">
          Transaction Particulars
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

              <div class="panel-body">
                <form class="form-horizontal" id="frm1">
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Cash In Hand Balance</label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <div class="input-group-addon"><i class="">Ksh.</i></div>
                        <input type="text" class="form-control" name="amount" id="cash" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Action<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="action" style="padding-left:5px">
                        <option data-icon="fa fa-suitcase">Select action...</option>
                        <option data-icon="fa fa-suitcase" value="CashDeposit">Cash Deposit</option>
                        <option data-icon="fa fa-suitcase" value="CashWithdrawal">Cash Withdrawal</option>
                        <!--option data-icon="fa fa-suitcase" value="ChequeDeposit">Cheque Deposit</option>
                        <option data-icon="fa fa-suitcase" value="ChequePayment">Cheque Payment</option>
                        <option data-icon="fa fa-suitcase" value="FundsTransfer">Funds Transfer</option-->
                      </select>  
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Bank Account<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="account" id="accounts" style="padding-left:5px">
                        <option data-icon="fa fa-suitcase">Select account...</option>
                      </select>  
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Amount<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <div class="input-group-addon"><i class="">Ksh.</i></div>
                        <input type="text" class="form-control" name="amount" id="amt">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Notes</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" name="descr" rows="3"></textarea>
                    </div>
                  </div> 
                  <button type="submit" class="btn btn-default bpost">Post</button>
                  <button type="submit" class="btn btn-warning bdiscard">Discard</button>
                </form>

              </div>

        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

