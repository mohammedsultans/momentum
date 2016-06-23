<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Credit Note</h1>
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
      <div class="col-md-12 col-lg-6">
        <div class="panel panel-default">
 
          <div class="panel-title">
            Client Information
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

          <div class="panel-body">
            <form class="form-horizontal" id="frmi1">
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Client<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" name="client" id="clients" data-live-search="true" >
                    <option data-icon="fa fa-user">Select Customer...</option>
                  </select>  
                </div>              
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Invoice<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" name="invoice" id="invoices" style="padding-left:5px" data-live-search="true" >
                    <option data-icon="fa fa-archive">Select Invoice</option>
                  </select>  
                </div>           
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Inv. Amount</label>
                <div class="col-sm-4">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="">Ksh.</i></div>
                    <input type="text" class="form-control" id="amount" name="amount" readonly>
                  </div>
                </div>
                <label class="col-sm-2 control-label form-label">Inv. Taxes</label>
                <div class="col-sm-4">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="">Ksh.</i></div>
                    <input type="text" class="form-control" id="taxes" name="tax" readonly>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Current Credit</label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="">Ksh.</i></div>
                    <input type="text" class="form-control" name="invcred" id="invcred" readonly>
                  </div>
                </div>
              </div>
                  
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Current Inv. Total</label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="">Ksh.</i></div>
                    <input type="text" class="form-control" name="total" id="total" readonly>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label form-label" style="font-weight:600">Credit Amount<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="">Ksh.</i></div>
                    <input type="text" class="form-control" name="credit" id="credit">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Reason <span class="color10">*</span></label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="descr" rows="5" id=""></textarea>
                </div>
              </div>
              <button type="submit" class="btn btn-warning idiscard float-r" style="margin-left:20px">Discard</button>
              <button type="submit" class="btn btn-default igenerate float-r">Post Credit Note</button>
            </form>

          </div>

        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

