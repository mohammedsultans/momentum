<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Ledger Transactions</h1>
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
            Debit Entry
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

              <div class="panel-body">
                <form class="form-horizontal" id="frm1">
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Debit Ledger<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="debit" id="drledger" data-live-search="true">
                        <option data-icon="fa fa-play-circle-o">Select ledger...</option>
                        <option data-icon="fa fa-play-circle-o" value="Asset">Asset</option>
                        <option data-icon="fa fa-play-circle-o" value="Liability">Liability</option>
                        <option data-icon="fa fa-play-circle-o" value="Equity">Equity</option>
                        <option data-icon="fa fa-play-circle-o" value="Revenue">Revenue</option>
                        <option data-icon="fa fa-play-circle-o" value="Expense">Expense</option>
                      </select>  
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Amount<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <div class="input-group-addon"><i class="">Ksh.</i></div>
                        <input type="text" class="form-control" name="dramount" autocomplete="off">
                      </div>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary dadd">Add</button>
                </form>

              </div>

        </div>

        <div class="panel panel-default">

          <div class="panel-title">
            Credit Entry
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

              <div class="panel-body">
                <form class="form-horizontal" id="frm2">
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Credit Ledger<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="credit" id="crledger" data-live-search="true">
                        <option data-icon="fa fa-play-circle-o">Select ledger...</option>
                        <option data-icon="fa fa-play-circle-o" value="Asset">Asset</option>
                        <option data-icon="fa fa-play-circle-o" value="Liability">Liability</option>
                        <option data-icon="fa fa-play-circle-o" value="Equity">Equity</option>
                        <option data-icon="fa fa-play-circle-o" value="Revenue">Revenue</option>
                        <option data-icon="fa fa-play-circle-o" value="Expense">Expense</option>
                      </select>  
                    </div>              
                  </div> 
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Amount<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <div class="input-group-addon"><i class="">Ksh.</i></div>
                        <input type="text" class="form-control" name="cramount" autocomplete="off">
                      </div>
                    </div>
                  </div>
                  
                  <button type="submit" class="btn btn-primary cadd">Add</button>
                </form>

              </div>

        </div>
      </div>
      <div class="col-xs-12 col-sm-6">
        <div class="panel panel-default">

          <div class="panel-title">
            Transaction Details   <span class="label label-primary">?</span>
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

          <div class="panel-body">
            <form class="form-horizontal" id="frm3">
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Description<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="descr" id="descr" rows="3"></textarea>
                </div>
              </div> 
              
            </form>
          </div>

          <div class="panel-body table-responsive">

                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <td>Ledger Name</td>
                      <td>Dr</td>
                      <td>Cr</td>
                      <td>DEL</td>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                        <th>Total</th>
                        <th id="drtot"></th>
                        <th id="crtot"></th>
                        <th id="txstatus"></th>
                    </tr>
                </tfoot>
                  <tbody id="ledgertx">
                    <tr>
                      <td>Chase Bank</td>
                      <td></td>
                      <td>12000</td>
                      <td>Del</td>
                    </tr>
                    <tr>
                      <td>Fixtures & Fittings</td>
                      <td>10000</td>
                      <td></td>
                      <td>Del</td>
                    </tr>
                    <tr>
                      <td>Stationery</td>
                      <td>2000</td>
                      <td></td>
                      <td>Del</td>
                    </tr>
                  </tbody>
                </table>
                 <form class="form-horizontal" style="overflow:hidden;border-top:5px solid #ddd;padding-top:20px" id="frm4">
                  <button type="submit" class="btn btn-default tpost" style="margin-bottom:15px">Post Transaction</button>
                </form>
          </div>

        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

