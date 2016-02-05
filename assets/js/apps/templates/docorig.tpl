<!-- START CONTENT -->
<div id="leadscont" class="">

  <style type="text/css">
    .upload-img {
        background: rgba(0, 0, 0, 0) url("documents/dragdrop.png") no-repeat scroll 50% center / 60% auto;
        border: 3px dashed #7a7c7f;
        color: #808080;
        height: 300px;
        width: calc(83.33% - 40px);
        float: right;
        right: 20px;
        margin: 15px auto;
    }
    .upload-img.hover {
        border: 3px dashed #f00;
    }
  </style>

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Lands Document Origination</h1>
    <!-- Start Page Header Right Div -->
    <div class="right">
      <div class="btn-group" role="group" aria-label="...">
        <a href="#docRegistry" class="btn btn-light" style="font-weight:600"><i class="fa fa-folder-o"></i>Lands Documents Registry</a>
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
            Create Document
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

              <div class="panel-body">
                <form class="form-horizontal" id="docform1">
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Client<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" id="clients" name="client" style="padding-left:5px" data-live-search="true" >
                        <option data-icon="fa fa-user">Select Client...</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Name<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" name="name" class="form-control" id="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Type<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" id="types" name="type" style="padding-left:5px" data-live-search="true" >
                        <option data-icon="fa fa-flag-checkered">Select Document Type...</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Serial No<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" name="serial" class="form-control" id="" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Parcel No<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" name="parcel" class="form-control" id="" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Details</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" name="details" rows="3"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Status<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" name="status" class="form-control" id="" value="">
                    </div>
                  </div>
                  <button type="submit" class="btn btn-default nsave">Save</button>
                </form>

              </div>

        </div>
      </div>
      <div class="col-xs-12 col-sm-6">
        <div class="panel panel-default">

          <div class="panel-title">
            Modify Document
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

          <div class="panel-body">
            <form class="form-horizontal scanneddoc" id="docform2">
                 
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Search</label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" id="documents" name="id" style="padding-left:5px" data-live-search="true" >
                    <option data-icon="fa fa-file-pdf-o">Select Document...</option>
                  </select>  
                </div>              
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Client<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" id="clients2" name="client" style="padding-left:5px" data-live-search="true" >
                    <option data-icon="fa fa-user">Select Client...</option>
                  </select>  
                </div>              
              </div>  
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Name<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <input type="text" name="name" class="form-control" id="dname">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Type<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" id="types2" name="type" style="padding-left:5px" data-live-search="true" >
                    <option data-icon="fa fa-flag-checkered">Select Document Type...</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Serial No<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <input type="text" name="serial" class="form-control" id="dsno" value="">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Parcel No<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <input type="text" name="parcel" class="form-control" id="dpno" value="">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Details</label>
                <div class="col-sm-10">
                  <textarea class="form-control" id="ddetails" name="details" rows="3"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Upload</label>
                <div class="col-sm-10 upload-img">
                  <input type="file" value="" name="scandoc" style="display:none" id="scandoc">
                  <span></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Status<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <input type="text" id="dstatus" class="form-control" name="status">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Last Updated</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="lastupdate" disabled="disabled">
                </div>
              </div>

              <button type="submit" class="btn btn-default esave">Update</button>
              <button type="submit" class="btn btn-warning edelete">Delete</button>
            </form>

          </div>

        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

