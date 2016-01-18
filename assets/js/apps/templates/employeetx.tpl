<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Employee Transactions</h1>
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
                    <label class="col-sm-2 control-label form-label">Employee:</label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="employee" id="employees" data-live-search="true">
                      </select>  
                    </div>              
                  </div>
                  <div class="form-group col-sm-4">
                    <label class="col-sm-2 control-label form-label">Start & End Dates:</label>
                    <div class="col-sm-10" id="filter">
                      <div class="control-group">
                        <div class="controls">
                          <div class="input-prepend input-group">
                            <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" id="date-range-picker" class="form-control" name="dates"/>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-sm-1">
                    <div class="checkbox checkbox-primary" style="margin:0">
                      <input id="vall" name="vall" type="checkbox" checked><label for="vall">View All</label>
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
                      <td>Tx No.</td>
                      <td>Date & Time</td>
                      <td>Type</td>  
                      <td>Effect</td>                      
                      <td>Amount</td>
                      <td>Description</td>
                      <td>Tx By</td>
                      <td>Print</td>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr style="font-weight:bold">
                      <td>Tx No.</td>
                      <td>Date & Time</td>
                      <td>Type</td>
                      <td>Effect</td>  
                      <td>Amount</td>
                      <td>Description</td>
                      <td>Tx By</td>
                      <td>Print</td>
                    </tr>
                  </tfoot>
                  <tbody id="results">
                  </tbody>
                </table>
          </div>

        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

