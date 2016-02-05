<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Client Documents Registry<span id="clientname"></span></h1>
    <!-- Start Page Header Right Div -->
    <div class="right">
      <div class="btn-group" role="group" aria-label="...">
        <a href="#docOrigination" class="btn btn-light" style="font-weight:600"><i class="fa fa-edit"></i>Add New</a>
        <a href="#start" class="btn btn-light"><i class="fa fa-remove"></i></a>
      </div>
    </div>
    <!-- End Page Header Right Div -->
  </div>
  <!-- End Page Header -->

 <!-- //////////////////////////////////////////////////////////////////////////// --> 
  <!-- START CONTAINER -->
  <div class="container-padding">
    <div class="row">
      <div class="col-xs-12 col-sm-12">
        <div class="panel panel-default">

              <div class="panel-body">
                <form class="form-horizontal">
                  <div class="form-group col-sm-5">
                    <label class="col-sm-2 control-label form-label">Client:</label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="client" id="clients" style="padding-left:5px" data-live-search="true">
                        <option data-icon="fa fa-user" value="0">Select Client ... </option>
                      </select>  
                    </div>              
                  </div>
                  <!--div class="form-group col-sm-1">
                    <button type="submit" class="btn btn-default fsearch"><i class="fa fa-search"></i></button>
                  </div-->
                </form>

              </div>

        </div>
      </div>
    </div>
    <!-- Start Row -->
    <div class="row">
      <!-- Start Panel -->
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-body table-responsive">
              <table id="example1" class="table display">
                  <thead>
                      <tr>
                          <th>Name</th>
                          <th>Type</th>
                          <th>Serial No</th>
                          <th>Parcel No</th>
                          <th>Status</th>
                          <th>Last Updated</th>
                          <th>Options</th>
                      </tr>
                  </thead>
               
                  <tfoot>
                      <tr>
                          <th>Name</th>
                          <th>Type</th>
                          <th>Serial No</th>
                          <th>Parcel No</th>
                          <th>Status</th>
                          <th>Last Updated</th>
                          <th>Options</th>
                      </tr>
                  </tfoot>
               
                  <tbody>
                  </tbody>
              </table>


          </div>

        </div>
      </div>
      <!-- End Panel -->
    </div>
  </div>
    <!-- End Row -->
</div>

