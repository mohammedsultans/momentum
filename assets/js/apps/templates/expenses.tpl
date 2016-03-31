<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Expenses Management</h1>
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
                    <label class="col-sm-2 control-label form-label">Context<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="context" id="context" style="padding-left:5px" data-live-search="true">
                        <option data-icon="fa fa-archive">Select context...</option>
                      </select>  
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Bank Account<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="credit" id="banks" style="padding-left:5px" data-live-search="true">
                        <option data-icon="fa fa-suitcase">Select ledger...</option>
                      </select>  
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Expense Account<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="debit" id="expenses" style="padding-left:5px" data-live-search="true">
                        <option data-icon="fa fa-suitcase">Select ledger...</option>
                      </select>  
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Amount<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <div class="input-group-addon"><i class="">Ksh.</i></div>
                        <input type="text" class="form-control" name="amount" id="">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Ref. No<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="voucher" id="">
                    </div>
                  </div> 
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Description<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <textarea class="form-control" name="descr" rows="3" id="textarea1"></textarea>
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

