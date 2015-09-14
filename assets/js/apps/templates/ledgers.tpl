<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Ledgers Panel</h1>
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
      <div class="col-xs-12 col-sm-5">
        <div class="panel panel-default">

          <div class="panel-title">
            New Ledger Details
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

              <div class="panel-body">
                <form class="form-horizontal" id="frmr1">
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">A/C Name<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" name="name" class="form-control" id="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Type<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="type">
                        <option data-icon="fa fa-play-circle-o">Select type...</option>
                        <option data-icon="fa fa-play-circle-o" value="Asset">Asset</option>
                        <option data-icon="fa fa-play-circle-o" value="Liability">Liability</option>
                        <option data-icon="fa fa-play-circle-o" value="Equity">Equity</option>
                        <option data-icon="fa fa-play-circle-o" value="Revenue">Revenue</option>
                        <option data-icon="fa fa-play-circle-o" value="Expense">Expense</option>
                      </select>  
                    </div>              
                  </div> 
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Group<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="group">
                        <option data-icon="fa fa-play-circle-o">Select one...</option>
                        <option data-icon="fa fa-list-alt" value="n">N/A</option>
                        <option data-icon="fa fa-play-circle-o" value="Fixed Asset">Fixed Asset</option>
                        <option data-icon="fa fa-play-circle-o" value="Current Asset">Current Asset</option>
                        <option data-icon="fa fa-play-circle-o" value="Long Term Liability">Long Term Liability</option>
                        <option data-icon="fa fa-play-circle-o" value="Current Liability">Current Liability</option>
                      </select>  
                    </div>              
                  </div> 
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Category<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="category">
                        <option data-icon="fa fa-play-circle-o">Select one...</option>
                        <option data-icon="fa fa-play-circle-o" value="Bank">Bank</option>
                        <option data-icon="fa fa-play-circle-o" value="Other">Other</option>
                      </select>  
                    </div>              
                  </div> 
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Sub-A/C Of<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="subaccount" id="subacc" data-live-search="true">
                        <option data-icon="fa fa-list-alt">N/A</option>
                        <option data-icon="fa fa-list-alt">Prince Munene</option>
                        <option data-icon="fa fa-list-alt">Stanley Mikicha</option>
                        <option data-icon="fa fa-list-alt">Jonathan Lukuli</option>
                        <option data-icon="fa fa-list-alt">Wycliffe Odhiambo</option>
                        <option data-icon="fa fa-list-alt">Catherine Njeru</option>
                      </select>  
                    </div>              
                  </div>
                  <button type="submit" class="btn btn-default lsave">Create Ledger</button>
                </form>

              </div>

        </div>
      </div>
      <div class="col-xs-12 col-sm-7">
        <div class="panel panel-default">

          <div class="panel-title">
            Accounting Ledgers  <span class="label label-primary">?</span>
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

          <div class="panel-body table-responsive">

                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <td>A/C Name</td>
                      <td>Type</td>
                      <td>Balance</td>
                      <td>Del</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>KBV 564R Fuel</td>
                      <td>Transportation Expenses</td>
                      <td>Ksh. 1,500</td>
                      <td>Del</td>
                    </tr>
                    <tr>
                      <td>Lunch for 3</td>
                      <td>Food & Refreshments Expenses</td>
                      <td>Ksh. 750</td>
                      <td>Del</td>
                    </tr>
                  </tbody>
                </table>
          </div>

        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

