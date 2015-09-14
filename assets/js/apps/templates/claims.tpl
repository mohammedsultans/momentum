<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Project Expenses Claims</h1>
    <!-- Start Page Header Right Div -->
    <div class="right">
      <div class="btn-group" role="group" aria-label="...">
        <a href="#dash" class="btn btn-light"><i class="fa fa-remove"></i>Close</a>
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
      <div class="col-xs-12 col-sm-4">
        <div class="panel panel-default">

          <div class="panel-title">
            Particulars
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

              <div class="panel-body">
                <form class="form-horizontal" id="frmr1">
                <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Payment Account<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="account" id="accounts" style="padding-left:5px">
                        
                      </select>  
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Project<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="project" id="projects" style="padding-left:5px" data-live-search="true" >
                        
                      </select>  
                    </div>              
                  </div> 
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Expense Voucher<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="voucher" id="vouchers" style="padding-left:5px">
                        
                      </select>  
                    </div>              
                  </div>
                </form>

              </div>

        </div>
      </div>
      <div class="col-xs-12 col-sm-8">
        <div class="panel panel-default">

          <div class="panel-title">
            Expense Items <span style="font-style:italic;text-transform:lowercase">(all claimants to present receipts)</span>
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
                      <td>Claimant</td>
                      <td>Description</td>
                      <td>Category</td>
                      <td>Claimed Amt</td>
                      <td>Adjusted Amt</td>
                    </tr>
                  </thead>
                  <tbody>
                    
                  </tbody>
                </table>
                <form class="form-horizontal" style="overflow:hidden;border-top:5px solid #ddd;padding-top:20px" id="frmr3">
                  <button type="submit" class="btn btn-default bauth">Authorize</button>
                  <button type="submit" class="btn btn-warning brevoke">Revoke</button>
                </form>
              </div>
        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

