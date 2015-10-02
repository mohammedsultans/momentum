<!-- START CONTENT -->
<div id="dashcont" class="">

 <!-- //////////////////////////////////////////////////////////////////////////// --> 
  <!-- START CONTAINER -->
  <div class="container-widget">
    <div class="row">
      <!-- Start Chart Daily -->
      <div class="col-md-12 col-lg-6">
        <div class=" panel-widget widget chart-with-stats clearfix" style="height:450px;">

          <div class="col-sm-12" style="height:450px;">
            <h4 class="title">REVENUE VS EXPENSES<small>Last update: 1 hour ago</small></h4>
            <div class="top-label"><h2 id="tdyrev">11.291</h2><h4>Total Revenues Today</h4></div>
            <div class="bigchart" id="todaysales"></div>
          </div>

          <div class="right" style="height:450px;">
            <h4 class="title">MARGINS<small>(Revenues - Expenses)</small></h4>
            <!-- start stats -->
            <ul class="widget-inline-list clearfix">
              <li class="col-12" id="thr"><span class="diff"><b class="color-up" style="font-size: 18px;font-weight:600"><i class="fa fa-caret-up"></i> 26%</b><br></span>over last 30 days<i class="chart sparkline-green"></i></li>
              <li class="col-12" id="svn"><span class="diff"><b class="color-down" style="font-size: 18px;font-weight:600"><i class="fa fa-caret-down"></i> 26%</b><br></span>over last 7 daysk<i class="chart sparkline-blue"></i></li>
              <li class="col-12" id="yst"><span class="diff"><b class="color-up" style="font-size: 18px;font-weight:600"><i class="fa fa-caret-up"></i> 26%</b><br></span>yesterday<i class="chart sparkline-red"></i></li>
            </ul>
            
          </div>


        </div>
      </div>
      <!-- End Chart Daily -->

      <div class="col-md-12 col-lg-6">
        <div class="panel panel-widget">
          <div class="panel-title">
            Projects Overview <span class="label label-info" id="prjtot">?</span>
            <ul class="panel-tools">
            </ul>
          </div>

          <div class="panel-search">
            <form>
              <input type="text" class="form-control" placeholder="Search...">
              <i class="fa fa-search icon"></i>
            </form>
          </div>


          <div class="panel-body table-responsive">

            <table class="table table-hover">
              <thead>
                <tr>
                  <td>Project</td>
                  <td>Client</td>
                  <td>Status</td>
                </tr>
              </thead>
              <tbody id="projects">
              </tbody>
            </table>

          </div>
        </div>
      </div>
      
       
      
    </div>
    <!-- Start Top Stats -->
    <div class="row">
      <!-- Start Orders -->
      <div class="col-md-12 col-lg-6">
        <div class="panel panel-widget">
          <div class="panel-title">
            LATEST INVOICES <span class="label label-warning" id="invtot">?</span>
            <ul class="panel-tools">
            </ul>
          </div>

          <div class="panel-search">
            <form>
              <input type="text" class="form-control" placeholder="Search...">
              <i class="fa fa-search icon"></i>
            </form>
          </div>


          <div class="panel-body table-responsive">

            <table class="table table-hover table-striped">
              <thead>
                <tr>
                  <td>Invoice No</td>
                  <td>Scope</td>
                  <td>Client</td>
                  <td>Date</td>
                  <td>Amount</td>
                </tr>
              </thead>
              <tbody id="invoices">
              </tbody>
            </table>

          </div>
        </div>
      </div>
      <!-- End Orders -->
      <!-- Start Inbox -->
      <div class="col-md-12 col-lg-3">
        <div class="panel panel-widget">
          <div class="panel-title">
            Pending Enquiries <span class="label label-danger" id="enqtot">4</span>
            <ul class="panel-tools">
            </ul>
          </div>
          <div class="panel-body">

          <ul class="mailbox-inbox" id="enquiries">
              <li>
                <a href="#" class="item clearfix">
                  <span class="from">Jonathan Doe</span>
                  Hello, m8 how is goin ?
                  <span class="date">22 May</span>
                </a>
              </li>
          </ul>

          </div>
        </div>
      </div>
      <!-- End Inbox -->
      <!-- Start Inbox -->
      <div class="col-md-12 col-lg-3">
        <div class="panel panel-widget">
          <div class="panel-title">
            Messages <span class="label label-danger">?</span>
            <ul class="panel-tools">
            </ul>
          </div>
          <div class="panel-body">

          <ul class="mailbox-inbox" id="messages">
              <li>
                <a href="#" class="item clearfix">
                  <img src="img/profileimg.png" alt="img" class="img">
                  <span class="from">Jonathan Doe</span>
                  Hello, m8 how is goin ?
                  <span class="date">22 May</span>
                </a>
              </li>
          </ul>

          </div>
        </div>
      </div>
      <!-- End Inbox -->
    </div>
  <!-- End Top Stats -->
  </div>

</div>