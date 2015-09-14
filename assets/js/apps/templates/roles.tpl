<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">User Roles - Access Rights Manager</h1>
    <!-- Start Page Header Right Div -->
    <div class="right">
      <div class="btn-group" role="group" aria-label="...">
        <a href="#" class="btn btn-light"><i class="fa fa-remove"></i>Close</a>
      </div>
    </div>
    <!-- End Page Header Right Div -->
  </div>
  <!-- End Page Header -->

  <!-- START CONTAINER -->
  <div class="container-padding">
    <!-- Start Row -->
    <div class="row">
      <div class="col-xs-12 col-sm-5">
        <div class="panel panel-default">
 
          <div class="panel-title">
            Create Role
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

          <div class="panel-body">
            <form class="form-horizontal" id="frmq1">
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Role</label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-briefcase"></i></div>
                    <input type="text" class="form-control" name="role" id="">
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-default rcreate">Create</button>
            </form>
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-title">
            Edit Role
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

          <div class="panel-body">
            <form class="form-horizontal" id="frmq2">  
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Role</label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" name="role2" id="roles" style="padding-left:5px" data-live-search="true" >
                    <option data-icon="fa fa-briefcase">Select One...</option>
                    <option data-icon="fa fa-briefcase">System Admin</option>
                  </select>  
                </div>              
              </div>
              <button type="submit" class="btn btn-default rsave">Save</button>
              <button type="submit" class="btn btn-warning rdel">Delete</button>
            </form>

          </div>

        </div>
      </div>
      <div class="col-xs-12 col-sm-7">
        <div class="panel panel-default">

          <div class="panel-title">
            User Access Interfaces
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
                  <td>Interface</td>
                  <td>Access</td>
                </tr>
              </thead>
              <tbody>
                <tr class="success">
                  <td colspan="2" class="text-left" style="font-size:12px;text-transform:uppercase;font-weight:bolder;padding:5px 15px;">Customer Relations Module</td>
                </tr>
                <tr>
                  <td>Log Enquiry</td>
                  <td><div class="checkbox checkbox-primary" style="margin:0"><input id="checkbox102" type="checkbox" checked><label for="checkbox102"></label></div></td>
                </tr>
                <tr>
                  <td>Pending Enquiries</td>
                  <td><div class="checkbox checkbox-primary" style="margin:0"><input id="checkbox103" type="checkbox" checked><label for="checkbox103"></label></div></td>
                </tr>
                <tr>
                  <td>Add Client</td>
                  <td><div class="checkbox checkbox-primary" style="margin:0"><input id="checkbox104" type="checkbox" checked><label for="checkbox104"></label></div></td>
                </tr>
              </tbody>
              <tbody>
                <tr class="success">
                  <td colspan="2" class="text-left" style="font-size:11px;text-transform:uppercase;font-weight:bolder;padding:5px 15px;">Operations Module</td>
                </tr>
                <tr>
                  <td>Create Quotation</td>
                  <td><div class="checkbox checkbox-primary" style="margin:0"><input id="checkbox105" type="checkbox" checked><label for="checkbox105"></label></div></td>
                </tr>
                <tr>
                  <td>File Work Report</td>
                  <td><div class="checkbox checkbox-primary" style="margin:0"><input id="checkbox106" type="checkbox" checked><label for="checkbox106"></label></div></td>
                </tr>
              </tbody>
              <tbody>
                <tr class="success">
                  <td colspan="2" class="text-left" style="font-size:11px;text-transform:uppercase;font-weight:bolder;padding:5px 15px;">Financial Accounting Module</td>
                </tr>
                <tr>
                  <td>General Land Consultancy</td>
                  <td><div class="checkbox checkbox-primary" style="margin:0"><input id="checkbox107" type="checkbox" checked><label for="checkbox107"></label></div></td>
                </tr>
                <tr>
                  <td>General Land Consultancy</td>
                  <td><div class="checkbox checkbox-primary" style="margin:0"><input id="checkbox108" type="checkbox" checked><label for="checkbox108"></label></div></td>
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

