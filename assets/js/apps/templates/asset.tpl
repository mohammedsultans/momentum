<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Asset Origination</h1>
    <!-- Start Page Header Right Div -->
    <div class="right">
      <div class="btn-group" role="group" aria-label="...">
        <a href="#viewItems" class="btn btn-light" style="font-weight:600"><i class="fa fa-users"></i>Items Register</a>
        <a href="#start" class="btn btn-light"><i class="fa fa-remove"></i></a>
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
            Create Asset Item
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

              <div class="panel-body">
                <form class="form-horizontal" id="frms1">
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Name<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" name="name" class="form-control" id="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Category<span class="color10">*</span></label>
                    <div class="col-sm-4">
                      <select class="selectpicker form-control" name="category" id="ncat">
                        <option data-icon="fa fa-archive">Select Category...</option>
                      </select>
                    </div>
                    <label class="col-sm-2 control-label form-label">Asset Account<span class="color10">*</span></label>
                    <div class="col-sm-4">
                      <select class="selectpicker form-control" name="ledger" id="nledger" data-live-search="true">
                        <option data-icon="fa fa-archive">Select Account...</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Description</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-default nsave">Save</button>
                  <button type="submit" class="btn btn-warning ncancel">Cancel</button>
                </form>

              </div>

        </div>
      </div>
      <div class="col-xs-12 col-sm-6">
        <div class="panel panel-default">

          <div class="panel-title">
            Modify Asset Item
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

          <div class="panel-body">
            <form class="form-horizontal" id='frms2'>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Search</label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" id="items" name="id" style="padding-left:5px" data-live-search="true" >
                    <option data-icon="fa fa-archive">Select asset...</option>
                  </select>  
                </div>              
              </div>    
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Name<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <input type="text" name="name" class="form-control" id="ename">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Category<span class="color10">*</span></label>
                <div class="col-sm-4">
                  <select class="selectpicker form-control" name="category" id="ecat">
                    <option data-icon="fa fa-archive">Select Category...</option>
                  </select>
                </div>
                <label class="col-sm-2 control-label form-label">Asset Account<span class="color10">*</span></label>
                <div class="col-sm-4">
                  <select class="selectpicker form-control" name="ledger" id="eledger" data-live-search="true">
                    <option data-icon="fa fa-archive">Select Account...</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Description</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="description" rows="3" id="edesc"></textarea>
                </div>
              </div>
              <button type="submit" class="btn btn-default esave">Save</button>
              <button type="submit" class="btn btn-warning edelete">Delete</button>
            </form>

          </div>

        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

