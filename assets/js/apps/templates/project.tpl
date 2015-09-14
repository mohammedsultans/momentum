<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">New Project</h1>
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
                        <option data-icon="fa fa-user">Alex Mbaka</option>
                        <option data-icon="fa fa-user">Prince Munene</option>
                        <option data-icon="fa fa-user">Chase Assurance</option>
                        <option data-icon="fa fa-user">Genghis Capital</option>
                        <option data-icon="fa fa-user">Light House Properties</option>
                        <option data-icon="fa fa-user">Orchid Capital</option>
                        <option data-icon="fa fa-user">Rafiki Microfinance</option>
                        <option data-icon="fa fa-user">Tulip Healthcare</option>
                        <option data-icon="fa fa-user">Rivieres Finance</option>
                      </select>  
                    </div>              
                  </div> 
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Project Name<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" name="project" class="form-control" id="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Location<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" name="location" class="form-control" id="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Description</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" name="descr" rows="3" id="textarea1"></textarea>
                    </div>
                  </div>
                  
                </form>

              </div>

        </div>
      </div>
      <div class="col-xs-12 col-sm-6">
        <div class="panel panel-default">

          <div class="panel-title">
            Activities <span class="label label-primary">?</span>
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
                    <option data-icon="fa fa-calculator">Q1235625</option>
                    <option data-icon="fa fa-calculator">Q1236257</option>
                    <option data-icon="fa fa-calculator">Q1230023</option>
                    <option data-icon="fa fa-calculator">Q1242152</option>
                  </select>  
                </div>              
              </div>   
              <button type="submit" class="btn btn-default iquote" style="margin-bottom:15px">Import Quotation</button>
            </form>
          </div>
          
          <div class="panel-body table-responsive">
            <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <td>Service</td>
                      <td>Task</td>
                      <td>Qty</td>
                    </tr>
                  </thead>
                  <tbody class="acts">
                    <tr>
                    <td>Topocadastral Survey</td>
                      <td>The Task goes here</td>
                      <td>3</td>
                    </tr>
                    <tr>
                      <td>Topocadastral Survey</td>
                      <td>The Task goes here</td>
                      <td>1</td>
                    </tr>
                    <tr>
                      <td>Engineering Survey</td>
                      <td>The Task goes here</td>
                      <td>1</td>
                    </tr>
                    <tr>
                      <td>General Land Consultancy</td>
                      <td>The Task goes here</td>
                      <td>2</td>
                    </tr>
                  </tbody>
            </table>
          
            
            <form class="form-horizontal" style="overflow:hidden;border-top:5px solid #ddd;padding-top:20px">
              <button type="submit" class="btn btn-default psave">Create Project</button>
              <button type="submit" class="btn btn-warning pdiscard">Discard</button>              
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

