<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">New Lead</h1>
    <!-- Start Page Header Right Div -->
    <div class="right">
      <div class="btn-group" role="group" aria-label="...">
        <a href="#searchcontact" class="btn btn-light" style="font-weight:600"><i class="fa fa-mobile"></i> Phone Contact</a>
        <a href="#" class="btn btn-light"><i class="fa fa-remove"></i></a>
      </div>
    </div>
    <!-- End Page Header Right Div -->
  </div>
  <!-- End Page Header -->

 <!-- //////////////////////////////////////////////////////////////////////////// --> 
  <!-- START CONTAINER -->
  <div class="container-widget">
   <div class="col-md-12 login-form new-lead">
      <form action="#">
        <div class="form-area">
          <div class="group">
            <input type="text" class="form-control" placeholder="Prospects Name" name="name">
            <i class="fa fa-user"></i>
          </div>
          <div class="group">
            <input type="text" class="form-control" placeholder="Phone Number" name="phone">
            <i class="fa fa-phone"></i>
          </div>
          <div class="group">
            <input type="text" class="form-control" placeholder="E-mail" name="email">
            <i class="fa fa-envelope-o"></i>
          </div>
          <div class="input-group group" style="display:block">
            <select class="selectpicker form-control xf" style="padding-left:5px" data-live-search="true" name="company">
              <option data-icon="fa fa-institution">Select Company...</option>
            </select>
            
          </div>          
          <div class="input-group group" style="display:block">
            <select class="selectpicker form-control xf" style="padding-left:5px" data-live-search="true" placeholder="select branch" name="branch">
              <option data-icon="fa fa-map-marker">Select Branch...</option>
            </select>            
          </div>

          <div class="input-group group" style="display:block">
            <select class="selectpicker form-control xf" style="padding-left:5px" data-live-search="true" placeholder="select product category" name="product">
              <option data-icon="fa fa-support">Select Product...</option>
            </select>            
          </div>
          
          <div class="input-group group" style="display:block">
            <textarea class="form-control" rows="3" placeholder="Additional info ..." style="height:unset;padding-left:10px;margin-bottom:20px" name="notes"></textarea>  
            <i class="fa fa-envelope-o"></i>
          </div>
          <button type="submit" class="btn btn-default btn-block btnsub">ADD LEAD</button>
        </div>
      </form>
    </div>
  </div>
</div>