<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">New Lead</h1>
    <!-- Start Page Header Right Div -->
    <div class="right">
      <div class="btn-group" role="group" aria-label="...">
        <a href="#" class="btn btn-light" style="font-weight:600"><i class="fa fa-mobile"></i> Phone Contact</a>
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
            <input type="text" class="form-control" placeholder="Prospects Name" value="<%= number %>">
            <i class="fa fa-user"></i>
          </div>
          <div class="group">
            <input type="text" class="form-control" placeholder="Phone Number" value="<%= phone %>">
            <i class="fa fa-phone"></i>
          </div>
          <div class="group">
            <input type="text" class="form-control" placeholder="E-mail">
            <i class="fa fa-envelope-o"></i>
          </div>
          <div class="input-group group" style="display:block">
            <select class="selectpicker form-control xf" style="padding-left:5px" data-live-search="true" >
              <option data-icon="fa fa-institution">Select Company...</option>
              <option data-icon="fa fa-institution">Chase Bank</option>
              <option data-icon="fa fa-institution">Chase Iman</option>
              <option data-icon="fa fa-institution">Chase Assurance</option>
              <option data-icon="fa fa-institution">Genghis Capital</option>
              <option data-icon="fa fa-institution">Light House Properties</option>
              <option data-icon="fa fa-institution">Orchid Capital</option>
              <option data-icon="fa fa-institution">Rafiki Microfinance</option>
              <option data-icon="fa fa-institution">Tulip Healthcare</option>
              <option data-icon="fa fa-institution">Rivieres Finance</option>
            </select>
            
          </div>          
          <div class="input-group group" style="display:block">
            <select class="selectpicker form-control xf" style="padding-left:5px" data-live-search="true" placeholder="select branch">
              <option data-icon="fa fa-map-marker">Select Branch...</option>
              <option data-icon="fa fa-map-marker">Moi Avenue Branch</option>
              <option data-icon="fa fa-map-marker">Haile Selasie Branch</option>
              <option data-icon="fa fa-map-marker">Westlands Branch</option>
              <option data-icon="fa fa-map-marker">Thika Branch</option>
              <option data-icon="fa fa-map-marker">TRM Branch</option>
              <option data-icon="fa fa-map-marker">Nyali Branch</option>
              <option data-icon="fa fa-map-marker">Mombasa Island Branch</option>
            </select>            
          </div>

          <div class="input-group group" style="display:block">
            <select class="selectpicker form-control xf" style="padding-left:5px" data-live-search="true" placeholder="select product category">
              <option data-icon="fa fa-support">Select Product...</option>
              <option data-icon="fa fa-support">Fixed Deposit Accounts</option>
              <option data-icon="fa fa-support">Current Accounts</option>
              <option data-icon="fa fa-support">Loans</option>
              <option data-icon="fa fa-support">Savings Accounts</option>
              <option data-icon="fa fa-support">Foreign Exchange</option>
              <option data-icon="fa fa-support">Securities Investment</option>
              <option data-icon="fa fa-support">Internet Banking</option>
            </select>            
          </div>
          
          <div class="input-group group" style="display:block">
            <textarea class="form-control" rows="3" placeholder="Additional info ..." style="height:unset;padding-left:10px;margin-bottom:20px"></textarea>  
            <i class="fa fa-envelope-o"></i>
          </div>
          <button type="submit" class="btn btn-default btn-block">ADD LEAD</button>
        </div>
      </form>
    </div>
  </div>
</div>