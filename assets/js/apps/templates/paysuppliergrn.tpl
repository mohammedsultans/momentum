<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Pay Suppliers</h1>
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
      <div class="col-xs-12 col-sm-7">
        <div class="panel panel-default">
 
          <div class="panel-title">
            Supplier Information
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

          <div class="panel-body">
            <form class="form-horizontal" id="frmp1">
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Supplier<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" name="supplier" id="suppliers" data-live-search="true" >
                    <option data-icon="fa fa-user">Select Supplier...</option>
                  </select>  
                </div>              
              </div>
            </form>
          </div>

          <div class="panel-title">
           Unclearered Invoices
          </div>

          <div class="panel-body table-responsive">
              <table class="table table-hover table-striped">
                <thead>
                  <tr>
                    <td>Date</td>
                    <td>GRN No</td>
                    <td>Amount</td>
                    <td>Balance</td>
                    <td>Paying</td>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th></th>
                    <th>TOTALS:</th>
                    <th id="totamt">Ksh. 5,150</th>
                    <th id="totbal">Ksh. 3,000</th>
                    <th id="totpay">Ksh. 2,000</th>
                  </tr>
                </tfoot>
                <tbody id="paysup">
                  <tr>
                    <td>25/09/2015</td>
                    <td>INV362</td>
                    <td>Ksh. 1,350</td>
                    <td>Ksh. 1,000</td>
                    <td>
                      <form class="form-horizontal" style="margin:0">
                        <div class="form-group">
                          <div class="input-group">
                            <p class="viid" style="display: none;"></p>
                            <div class="input-group-addon">
                              <i class="">Ksh.</i>
                            </div>
                            <input type="text" class="form-control adjusted" name="adjusted" value="">
                          </div>
                        </div>
                      </form>
                    </td>
                  </tr>
                </tbody>
              </table>
          </div>

        </div>
      </div>
      <div class="col-xs-12 col-sm-5">
        <div class="panel panel-default">
          <div class="panel-title">
            Payment Details <span class="label label-primary">?</span>
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

          <div class="panel-body">
            <form class="form-horizontal" id="frmp3">
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Total Payment</label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="">Ksh.</i></div>
                    <input type="text" class="form-control" id="amount" name="amount" readonly>
                    <div class="input-group-addon">.00</div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Payment Account<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" name="account" id="banks">
                    <option data-icon="fa fa-money">Select Mode...</option>
                    <option data-icon="fa fa-money">Cash</option>
                    <option data-icon="fa fa-money">Family Bank</option>
                    <option data-icon="fa fa-money">Equity Bank</option>
                    <option data-icon="fa fa-money">Chase Bank</option>
                    <option data-icon="fa fa-money">Standard Chartered Bank</option>
                    <option data-icon="fa fa-money">M-Pesa</option>
                  </select>  
                </div>                
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Payment Mode<span class="color10">*</span></label>
                <div class="col-sm-4">
                  <select class="selectpicker form-control" name="mode" id="modes">
                    <option data-icon="fa fa-money" value="">Select Mode...</option>
                    <option data-icon="fa fa-money" value="Cash">Cash</option>
                    <option data-icon="fa fa-money" value="Cheque">Cheque</option>
                    <option data-icon="fa fa-money" value="Bank Transfer">Bank Transfer</option>
                  </select>  
                </div> 
                <label class="col-sm-2 control-label form-label">Voucher No</label>
                <div class="col-sm-4">
                  <input type="text" name="voucher" class="form-control" id="">
                </div>                    
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Description<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="descr" rows="3" id=""></textarea>
                </div>
              </div>
              <button type="submit" class="btn btn-warning idiscard float-r" style="margin-left:20px">Discard</button>
              <button type="submit" class="btn btn-default ipay float-r">Make Payment</button>
                  
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

