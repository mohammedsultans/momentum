<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Manage Project</h1>
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
            Project Details
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

              <div class="panel-body">
                <form class="form-horizontal" id="frmp1">
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Client<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="client" id="clients" style="padding-left:5px" data-live-search="true" >
                        <option data-icon="fa fa-user">Select Customer...</option>
                      </select>  
                    </div>              
                  </div> 
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Project<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="projectid" id="projects" style="padding-left:5px" data-live-search="true" >
                        <option data-icon="fa fa-archive">Select One...</option>
                      </select>  
                    </div>              
                  </div>   
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Project Name<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" name="project" class="form-control" id="prjname">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Project Status</label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="status" style="padding-left:5px" id="prjstat">
                        <option data-icon="fa fa-bullseye" value="0">Pending</option>
                        <option data-icon="fa fa-bullseye" value="1">On Going</option>
                        <option data-icon="fa fa-bullseye" value="2">Stalled</option>
                        <option data-icon="fa fa-bullseye" value="3">Completed</option>
                        <option data-icon="fa fa-bullseye" value="4">Suspended</option>
                      </select>  
                    </div>              
                  </div> 
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Location<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" name="location" class="form-control" id="prjloc">
                    </div>
                  </div>
                  <!--div class="form-group control-group">
                    <label class="col-sm-2 control-label form-label">Start Date</label>
                    <div class="controls col-sm-10">
                      <div class="input-prepend input-group">
                        <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" id="date-picker" name="date" class="form-control" value="03/18/2015"/> 
                      </div>
                    </div>
                  </div-->
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Description</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" name="descr" rows="3" id="prjdescr"></textarea>
                    </div>
                  </div>
                </form>

              </div>

        </div>
      </div>
      <div class="col-xs-12 col-sm-6">
        <div class="panel panel-default">

          <div class="panel-title">
            Project Deliverables <span class="label label-primary">2</span>
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>
          <div class="panel-body">
            <form class="form-horizontal" id="frmp2">
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Quotation No</label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" name="quote" id="quotes" style="padding-left:5px" data-live-search="true" >
                    <option data-icon="fa fa-calculator">Select One...</option>
                  </select>  
                </div>              
              </div>   
              <button type="submit" class="btn btn-default iquote float-r" style="margin-bottom:20px">Import Quotation</button>
            </form>
          </div>

          <div class="panel-body table-responsive">
            <table class="table table-hover table-striped">
              <thead>
                <tr>
                  <td>Activity</td>
                  <td>Qty</td>
                  <td>Status</td>
                </tr>
              </thead>
              <tbody class="acts">
              </tbody>
            </table>

            <form class="form-horizontal" style="overflow:hidden;border-top:5px solid #ddd;padding-top:20px">
              <button type="submit" class="btn btn-default psave">Update Project</button>
              <button type="submit" class="btn btn-warning pdiscard">Delete</button>              
            </form>
          </div>

        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

