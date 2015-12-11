<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Payroll Manager</h1>
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
                  <div class="form-group col-sm-3">
                    <label class="col-sm-3 control-label form-label">Month:</label>
                    <div class="col-sm-9">
                      <div class="control-group">
                        <div class="controls">
                          <div class="input-prepend input-group">
                            <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" id="month-picker" class="form-control" name="month"/>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-sm-4">
                    <div class="" role="btn-group" aria-label="...">
                      <a href="#" class="btn btn-light ppreview" alt="preview payroll"><i class="fa fa-eye"></i> Preview</a>
                    </div>
                  </div>
                  
                  <div class="form-group col-sm-5" style="right:-80px">
                    <div class="" role="group" aria-label="..." style="float:right">
                      <a href="#" class="btn btn-light pcommit" alt="commit payroll"><i class="fa fa-send"></i> Commit<br> Payroll</a>
                      <a href="#" class="btn btn-light ppayslips" alt="preview payslips"><i class="fa fa-tags"></i> View<br> Payslips</a>
                      <!--a href="#" class="btn btn-light" alt="payroll settings"><i class="fa fa-cogs"></i>Settings</a-->
                    </div>
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
            Payroll Summary <span class="label label-info">MONTH: <b id="prmonth"></b> -> STATUS: <b id="prstat"></b></span>
          </div>

          <div class="panel-body table-responsive">

                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <td>P/F No.</td>
                      <td>Name</td>
                      <td>Role</td>
                      <td>Basic Salary</td>                      
                      <td>Additions</td>
                      <td>Deductions</td>
                      <td>Net Pay</td>
                      <td>Payslip</td>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr style="font-weight:bold">
                      <td>P/F No.</td>
                      <td>Name</td>
                      <td>Role</td>
                      <td>Basic Salary</td>                      
                      <td>Additions</td>
                      <td>Deductions</td>
                      <td>Net Pay</td>
                      <td>Payslip</td>
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

