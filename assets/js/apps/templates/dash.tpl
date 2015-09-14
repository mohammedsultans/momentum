<!-- START CONTENT -->
<div id="dashcont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Dashboard</h1>
      <ol class="breadcrumb">
        <li class="active">Everything, at a glance</li>
    </ol>

    <!-- Start Page Header Right Div -->
    <div class="right">
      <div class="btn-group" role="group" aria-label="...">
        <a href="#" class="btn btn-light"><i class="fa fa-mobile"></i></a>
        <a href="#reports" class="btn btn-light" id="topstats"><i class="fa fa-line-chart"></i></a>
      </div>
    </div>
    <!-- End Page Header Right Div -->

  </div>
  <!-- End Page Header -->

 <!-- //////////////////////////////////////////////////////////////////////////// --> 
  <!-- START CONTAINER -->
  <div class="container-widget">
    <div class="row">
      <!-- Start Chart Daily -->
      <div class="col-md-12 col-lg-6">
        <div class=" panel-widget widget chart-with-stats clearfix" style="height:450px;">

          <div class="col-sm-12" style="height:450px;">
            <h4 class="title">SALES<small>Last update: 1 Hours ago</small></h4>
            <div class="top-label"><h2>11.291</h2><h4>Today Total</h4></div>
            <div class="bigchart" id="todaysales"></div>
          </div>
          <div class="right" style="height:450px;">
            <h4 class="title">OVERVIEW</h4>
            <!-- start stats -->
            <ul class="widget-inline-list clearfix">
              <li class="col-12"><span>962</span>This Week<i class="chart sparkline-green"></i></li>
              <li class="col-12"><span>367</span>This Month<i class="chart sparkline-blue"></i></li>
              <li class="col-12"><span>92</span>Projected<i class="chart sparkline-red"></i></li>
            </ul>
            <!-- end stats -->
          </div>


        </div>
      </div>
      <!-- End Chart Daily -->

      <div class="col-md-12 col-lg-6">
        <div class="panel panel-widget">
          <div class="panel-title">
            Projects Overview <span class="label label-info">?</span>
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
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
                  <td>ID</td>
                  <td>Project</td>
                  <td>Client</td>
                  <td>Status</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>965</td>
                  <td>Kode Dashboard Template</td>
                  <td>Kode Dashboard Template</td>
                  <td><span class="label label-info">Developing</span></td>
                </tr>
                <tr>
                  <td>620</td>
                  <td>EBI iOS Application</td>
                  <td>Kode Dashboard Template</td>
                  <td><span class="label label-info">Design</span></td>
                </tr>
                <tr>
                  <td>621</td>
                  <td>Kode Landing Page</td>
                  <td>Kode Dashboard Template</td>
                  <td><span class="label label-info">Testing</span></td>
                </tr>
                <tr>
                  <td>621</td>
                  <td>John Coffe Shop Logo</td>
                  <td>Kode Dashboard Template</td>
                  <td><span class="label label-info">Canceled</span></td>
                </tr>
                <tr>
                  <td>621</td>
                  <td>BKM Website Design</td>
                  <td>Kode Dashboard Template</td>
                  <td><span class="label label-info">Reply waiting</span></td>
                </tr>
                <tr>
                  <td>621</td>
                  <td>BKM Website Design</td>
                  <td>Kode Dashboard Template</td>
                  <td><span class="label label-info">Reply waiting</span></td>
                </tr>
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
            LAST INVOICES <span class="label label-warning">?</span>
            <ul class="panel-tools">
              <li><a class="icon search-tool"><i class="fa fa-search"></i></a></li>
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
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
                  <td class="text-center"><i class="fa fa-trash"></i></td>
                  <td>Invoice ID</td>
                  <td>Scope</td>
                  <td>Client</td>
                  <td>Date</td>
                  <td>Amount</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center"><div class="checkbox margin-t-0"><input id="checkbox1" type="checkbox"><label for="checkbox1"></label></div></td>
                  <td># <b>9652</b></td>
                  <td>Kode Gaming Laptop</td>
                  <td>John Doe</td>
                  <td>12/10/2015</td>
                  <td>Credit Card</td>
                </tr>
                <tr>
                  <td class="text-center"><div class="checkbox margin-t-0"><input id="checkbox2" type="checkbox"><label for="checkbox2"></label></div></td>
                  <td># <b>1963</b></td>
                  <td>New Season Jacket</td>
                  <td>Jane Doe</td>
                  <td>12/10/2015</td>
                  <td>Paypal</td>
                </tr>
                <tr>
                  <td class="text-center"><div class="checkbox margin-t-0"><input id="checkbox3" type="checkbox"><label for="checkbox3"></label></div></td>
                  <td># <b>9652</b></td>
                  <td>IO Mouse</td>
                  <td>Jonathan Doe</td>
                  <td>12/10/2015</td>
                  <td>Credit Card</td>
                </tr>
                <tr>
                  <td class="text-center"><div class="checkbox margin-t-0"><input id="checkbox4" type="checkbox"><label for="checkbox4"></label></div></td>
                  <td># <b>9651</b></td>
                  <td>Doe Bike</td>
                  <td>Jonathan Doe</td>
                  <td>12/10/2015</td>
                  <td>Credit Card</td>
                </tr>
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
            Pending Enquiries <span class="label label-danger">4</span>
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>
          <div class="panel-body">

          <ul class="mailbox-inbox">

              <li>
                <a href="#" class="item clearfix">
                  <span class="from">Jonathan Doe</span>
                  Hello, m8 how is goin ?
                  <span class="date">22 May</span>
                </a>
              </li>

              <li>
                <a href="#" class="item clearfix">
                  <span class="from">Egemem Ka</span>
                  Problems look mighty small...
                  <span class="date">22 May</span>
                </a>
              </li>

              <li>
                <a href="#" class="item clearfix">
                  <span class="from">James Throwing</span>
                  New job offer ?
                  <span class="date">22 May</span>
                </a>
              </li>

              <li>
                <a href="#" class="item clearfix">
                  <span class="from">Timmy Jefsin</span>
                  Tonight Party
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
            Messages <span class="label label-danger">9</span>
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>
          <div class="panel-body">

          <ul class="mailbox-inbox">

              <li>
                <a href="#" class="item clearfix">
                  <img src="img/profileimg.png" alt="img" class="img">
                  <span class="from">Jonathan Doe</span>
                  Hello, m8 how is goin ?
                  <span class="date">22 May</span>
                </a>
              </li>

              <li>
                <a href="#" class="item clearfix">
                  <img src="img/profileimg2.png" alt="img" class="img">
                  <span class="from">Egemem Ka</span>
                  Problems look mighty small...
                  <span class="date">22 May</span>
                </a>
              </li>

              <li>
                <a href="#" class="item clearfix">
                  <img src="img/profileimg3.png" alt="img" class="img">
                  <span class="from">James Throwing</span>
                  New job offer ?
                  <span class="date">22 May</span>
                </a>
              </li>

              <li>
                <a href="#" class="item clearfix">
                  <img src="img/profileimg4.png" alt="img" class="img">
                  <span class="from">Timmy Jefsin</span>
                  Tonight Party
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