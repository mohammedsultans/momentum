<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">System Users Management</h1>
    <!-- Start Page Header Right Div -->
    <div class="right">
      <div class="btn-group" role="group" aria-label="...">
        <a href="#dash" class="btn btn-light"><i class="fa fa-remove"></i></a>
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
            Create User
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

              <div class="panel-body">
                <form class="form-horizontal" id="frmu1">
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Employee</label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" id="employees" name="employee" data-live-search="true" >
                        <option data-icon="fa fa-user">Select Employee...</option>
                        <option data-icon="fa fa-user">Alex Mbaka</option>
                        <option data-icon="fa fa-user">Prince Munene</option>
                      </select>  
                    </div>              
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Username<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-user"></i></div>
                        <input type="text" class="form-control" name="uname" id="">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Password<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-key"></i></div>
                        <input type="text" class="form-control" name="pass" id="">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Role<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="role" id="roles">
                        <option data-icon="fa fa-briefcase">Select Role...</option>
                        <option data-icon="fa fa-briefcase" value="Male">Male</option>
                        <option data-icon="fa fa-briefcase" value="Female">Female</option>
                      </select>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-default ucreate">Submit</button>
                  <button type="submit" class="btn btn-warning ucancel">Cancel</button>
                </form>

              </div>

        </div>
      </div>
      <div class="col-xs-12 col-sm-6">
        <div class="panel panel-default">

          <div class="panel-title">
            Modify User Records
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

          <div class="panel-body" id='eform'>
            <form class="form-horizontal" id="frmu2">
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Users</label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" id="users" name="user" data-live-search="true" >
                    <option data-icon="fa fa-user">Select Employee...</option>
                    <option data-icon="fa fa-user">Alex Mbaka</option>
                    <option data-icon="fa fa-user">Prince Munene</option>
                  </select>  
                </div>              
              </div>    
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Username</label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-user"></i></div>
                    <input type="text" class="form-control" name="uname2" id="uname">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Role<span class="color10">*</span></label>
               <div class="col-sm-10">
                  <select class="selectpicker form-control" name="role2" id="uroles">
                    <option data-icon="fa fa-briefcase">Select Role...</option>
                    <option data-icon="fa fa-briefcase" value="Male">Male</option>
                    <option data-icon="fa fa-briefcase" value="Female">Female</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Access<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" id="uaccess" name="access">
                    <option data-icon="fa fa-check-circle-o" value="1">Authorized</option>
                    <option data-icon="fa fa-ban" value="0">Barred</option>
                  </select>
                </div>
              </div>
              <button type="submit" class="btn btn-default usave">Save</button>
              <button type="submit" class="btn btn-warning udel">Delete</button>
            </form>

          </div>

        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

