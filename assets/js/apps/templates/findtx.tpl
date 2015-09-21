<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Find Transaction Entries</h1>
    <!-- Start Page Header Right Div -->
    <div class="right">
      <div class="btn-group" role="group" aria-label="...">
        <a href="#start" class="btn btn-light"><i class="fa fa-remove"></i>Close</a>
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
      <div class="col-xs-12 col-sm-12">
        <div class="panel panel-default">

              <div class="panel-body">
                <form class="form-horizontal">
                  <div class="form-group col-sm-5">
                    <label class="col-sm-2 control-label form-label">Search By:</label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="param" id="param" style="padding-left:5px">
                        <option data-icon="fa fa-flag" value="1">Transaction No</option>
                        <option data-icon="fa fa-flag" value="2">Description</option>
                        <option data-icon="fa fa-flag" value="3">Date</option>
                        <option data-icon="fa fa-flag" value="4">Date Range</option>
                        <option data-icon="fa fa-flag" value="5">Ledger</option>
                        <option data-icon="fa fa-flag" value="6">Amount</option>
                      </select>  
                    </div>              
                  </div>
                  <div class="form-group col-sm-5">
                    <label class="col-sm-2 control-label form-label">Search Term:</label>
                    <div class="col-sm-10" id="filter">
                      <input type="text" name="value" class="form-control" id="">
                    </div>
                  </div>
                  <div class="form-group col-sm-2">
                    <button type="submit" class="btn btn-default fsearch"><i class="fa fa-search"></i>Search</button>
                  </div>
                </form>

              </div>

        </div>
      </div>
    </div>
    <!-- End Row -->
    <!-- Start Row -->
    <div class="row">
      <div class="col-xs-12 col-sm-12">
        <div class="panel panel-default">

          <div class="panel-title">
            Transaction Entries
          </div>

          <div class="panel-body table-responsive">

                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <td>TX ID</td>
                      <td>Date & Time</td>
                      <td>Ledger</td>
                      <td>Effect</td>
                      <td>Amount</td>
                      <td>Description</td>
                      <td>Reverse</td>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr style="font-weight:bold">
                      <td>TX ID</td>
                      <td>DATE & TIME</td>
                      <td>LEDGER</td>
                      <td>EFFECT</td>
                      <td>AMOUNT</td>
                      <td>DESCRIPTION</td>
                      <td>REVERSE</td>
                    </tr>
                  </tfoot>
                  <tbody id="results">
                    <tr>
                      <td>1101</td>
                      <td>31/08/2015</td>
                      <td>Transportation Expenses</td>
                      <td>Petty Cash</td>
                      <td>Ksh. 7500</td>
                      <td>Fuel from Nairobi to Mombasa - KCA 987P</td>
                      <td><a class="btn btn-warning treverse" href="#"><i class="fa fa-undo" style="margin: 0px;"></i></a></td>
                    </tr>
                    <tr>
                      <td>1101</td>
                      <td>31/08/2015</td>
                      <td>Transportation Expenses</td>
                      <td>Petty Cash</td>
                      <td>Ksh. 7500</td>
                      <td>Fuel from Nairobi to Mombasa - KCA 987P</td>
                      <td><a class="btn btn-warning treverse" href="#"><i class="fa fa-undo"></i></a></td>
                    </tr>
                    <tr>
                      <td>1101</td>
                      <td>31/08/2015</td>
                      <td>Transportation Expenses</td>
                      <td>Petty Cash</td>
                      <td>Ksh. 7500</td>
                      <td>Fuel from Nairobi to Mombasa - KCA 987P</td>
                      <td><a class="btn btn-warning treverse" href="#"><i class="fa fa-undo"></i></a></td>
                    </tr>
                    <tr>
                      <td>1101</td>
                      <td>31/08/2015</td>
                      <td>Transportation Expenses</td>
                      <td>Petty Cash</td>
                      <td>Ksh. 7500</td>
                      <td>Fuel from Nairobi to Mombasa - KCA 987P</td>
                      <td><a class="btn btn-warning treverse" href="#"><i class="fa fa-undo"></i></a></td>
                    </tr>
                    <tr>
                      <td>1101</td>
                      <td>31/08/2015</td>
                      <td>Transportation Expenses</td>
                      <td>Petty Cash</td>
                      <td>Ksh. 7500</td>
                      <td>Fuel from Nairobi to Mombasa - KCA 987P</td>
                      <td><a class="btn btn-warning treverse" href="#"><i class="fa fa-undo"></i></a></td>
                    </tr>
                    <tr>
                      <td>1101</td>
                      <td>31/08/2015</td>
                      <td>Transportation Expenses</td>
                      <td>Petty Cash</td>
                      <td>Ksh. 7500</td>
                      <td>Fuel from Nairobi to Mombasa - KCA 987P</td>
                      <td><a class="btn btn-warning treverse" href="#"><i class="fa fa-undo"></i></a></td>
                    </tr>
                    <tr>
                      <td>1101</td>
                      <td>31/08/2015</td>
                      <td>Transportation Expenses</td>
                      <td>Petty Cash</td>
                      <td>Ksh. 7500</td>
                      <td>Fuel from Nairobi to Mombasa - KCA 987P</td>
                      <td><a class="btn btn-warning treverse" href="#"><i class="fa fa-undo"></i></a></td>
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

