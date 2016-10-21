<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Pay Salaries</h1>
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
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>
          <div class="panel-body">
            <form class="form-horizontal" id="frm1">
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Employee<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" name="employee" id="employees" style="padding-left:5px" data-live-search="true">
                    <option data-icon="fa fa-user">Select employee...</option>
                  </select>  
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Pay Slip<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" name="slip" id="slips" style="padding-left:5px" data-live-search="true">
                    <option data-icon="fa fa-clock-o">Select Slip...</option>
                  </select>  
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Amount Due<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="">Ksh.</i></div>
                    <input type="text" class="form-control" name="due" id="amount" disabled="disable">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Paying<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="">Ksh.</i></div>
                    <input type="text" class="form-control" name="amount" id="paying">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Account<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" name="ledger" id="banks" style="padding-left:5px" data-live-search="true">
                    <option data-icon="fa fa-suitcase">Select ledger...</option>
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
              <button type="submit" class="btn btn-default esubmit">Submit</button>
              <button type="submit" class="btn btn-warning ediscard">Discard</button>
            </form>

          </div>

        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

