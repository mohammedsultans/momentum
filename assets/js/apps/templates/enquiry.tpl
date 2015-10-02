<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Enquiries</h1>
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
      <div class="col-xs-12 col-sm-6">
        <div class="panel panel-default">

          <div class="panel-title">
            Log Enquiry
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

              <div class="panel-body">
                <form class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Name</label>
                    <div class="col-sm-10">
                      <input type="text" name="name" class="form-control" id="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Mobile</label>
                    <div class="col-sm-10">
                      <input type="text" name="tel" class="form-control" id="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Service(s)</label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="service" id="services" style="padding-left:5px" data-live-search="true" multiple>
                      </select>  
                    </div>              
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Details</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" name="details" rows="3" id="textarea1"></textarea>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-default nsave">Post Enquiry</button>
                  <button type="submit" class="btn btn-warning ncancel">Cancel</button>
                </form>

              </div>

        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

