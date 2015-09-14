<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Employee Information Management</h1>
    <!-- Start Page Header Right Div -->
    <div class="right">
      <div class="btn-group" role="group" aria-label="...">
        <a href="#employees" class="btn btn-light" style="font-weight:600"><i class="fa fa-users"></i>Employees Register</a>
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
                <form class="form-horizontal">
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Search</label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" id="employees" name="employees" data-live-search="true" >
                        <option data-icon="fa fa-user">Select Employee...</option>
                        <option data-icon="fa fa-user">Alex Mbaka</option>
                        <option data-icon="fa fa-user">Prince Munene</option>
                      </select>  
                    </div>              
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Username<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" name="uname" class="form-control" id="">
                      <i class="fa fa-user"></i>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Password<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <input type="password" name="pass" class="form-control" id="">
                      <i class="fa fa-key"></i>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Role<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="gender" id="roles">
                        <option data-icon="fa fa-user">Select Gender...</option>
                        <option data-icon="fa fa-male" value="Male">Male</option>
                        <option data-icon="fa fa-female" value="Female">Female</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Department<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" name="department" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Position<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" name="position" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Salary<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <div class="input-group-addon"><i class="">Ksh.</i></div>
                          <input type="text" class="form-control" name="salary" id="">
                        <div class="input-group-addon">.00</div>
                      </div>
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
            Modify User Records
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

          <div class="panel-body" id='eform'>
            <form class="form-horizontal">
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Search</label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" id="employees2" name="employees2" data-live-search="true" >
                    <option data-icon="fa fa-user">Select Employee...</option>
                    <option data-icon="fa fa-user">Alex Mbaka</option>
                    <option data-icon="fa fa-user">Prince Munene</option>
                  </select>  
                </div>              
              </div>    
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Name</label>
                <div class="col-sm-10">
                  <input type="text" name="name2" class="form-control" id="ename">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Mobile</label>
                <div class="col-sm-10">
                  <input type="text" name="tel2" class="form-control" id="etel">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">E-Mail</label>
                <div class="col-sm-10">
                  <input type="text" name="email2" class="form-control" id="eemail">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Address</label>
                <div class="col-sm-10">
                  <textarea class="form-control" name="address2" rows="3" id="eadd"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Gender<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" id="egender" name="gender2">
                    <option data-icon="fa fa-user">Select Gender...</option>
                    <option data-icon="fa fa-male" value="Male">Male</option>
                    <option data-icon="fa fa-female" value="Female">Female</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Department<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <input type="text" name="department2" class="form-control" id="edept">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Position<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <input type="text" name="position2" class="form-control" id="epos">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Salary<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="">Ksh.</i></div>
                      <input type="text" class="form-control" name="salary2" id="esalary">
                    <div class="input-group-addon">.00</div>
                  </div>
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

