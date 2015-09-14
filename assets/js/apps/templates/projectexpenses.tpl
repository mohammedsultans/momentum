<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Field Report</h1>
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
            Details
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

              <div class="panel-body">
                <form class="form-horizontal" id="frmr1">
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Client<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="client" id="clients" style="padding-left:5px" data-live-search="true" >
                        <option data-icon="fa fa-user">Select Customer...</option>
                        <option data-icon="fa fa-user">Alex Mbaka</option>
                        <option data-icon="fa fa-user">Prince Munene</option>
                        <option data-icon="fa fa-user">Chase Assurance</option>
                        <option data-icon="fa fa-user">Genghis Capital</option>
                        <option data-icon="fa fa-user">Light House Properties [Bal]</option>
                        <option data-icon="fa fa-user">Orchid Capital</option>
                        <option data-icon="fa fa-user">Rafiki Microfinance</option>
                        <option data-icon="fa fa-user">Tulip Healthcare</option>
                        <option data-icon="fa fa-user">Rivieres Finance</option>
                      </select>  
                    </div>              
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Project<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="project" id="projects" style="padding-left:5px" data-live-search="true" >
                        <option data-icon="fa fa-suitcase">Select project...</option>
                        <option data-icon="fa fa-suitcase">Chase Assurance</option>
                        <option data-icon="fa fa-suitcase">Genghis Capital</option>
                        <option data-icon="fa fa-suitcase">Light House Properties</option>
                        <option data-icon="fa fa-suitcase">Orchid Capital</option>
                        <option data-icon="fa fa-suitcase">Rafiki Microfinance</option>
                        <option data-icon="fa fa-suitcase">Tulip Healthcare</option>
                        <option data-icon="fa fa-suitcase">Rivieres Finance</option>
                      </select>  
                    </div>              
                  </div> 
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Task(s)<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="activity" id="activities" style="padding-left:5px" multiple>
                        <option data-icon="fa fa-lightbulb-o">General Boundary Survey</option>
                        <option data-icon="fa fa-lightbulb-o">Fixed Boundary Survey</option>
                        <option data-icon="fa fa-lightbulb-o">Topocadastral Survey</option>
                        <option data-icon="fa fa-lightbulb-o">Engineering Survey</option>
                        <option data-icon="fa fa-lightbulb-o">General Land Consultancy</option>
                        <option data-icon="fa fa-lightbulb-o">GIS Training</option>
                      </select>  
                    </div>              
                  </div> 
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Personell Involved<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="personell" id="employees" style="padding-left:5px" multiple data-live-search="true">
                        <option data-icon="fa fa-user">Alex Mbaka</option>
                        <option data-icon="fa fa-user">Prince Munene</option>
                        <option data-icon="fa fa-user">Stanley Mikicha</option>
                        <option data-icon="fa fa-user">Jonathan Lukuli</option>
                        <option data-icon="fa fa-user">Wycliffe Odhiambo</option>
                        <option data-icon="fa fa-user">Catherine Njeru</option>
                      </select>  
                    </div>              
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">After Action Report<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <textarea class="form-control" name="report" rows="5" id="textarea1"></textarea>
                    </div>
                  </div>
                </form>

              </div>

        </div>
      </div>
      <div class="col-xs-12 col-sm-6">
        <div class="panel panel-default">

          <div class="panel-title">
            Associated Expenses <span class="label label-primary">2</span>
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

          <div class="panel-body">
            <form class="form-horizontal" id="frmr2">
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Description<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <input type="text" name="description" class="form-control" id="">
                </div>
              </div>
              <div class="form-group">                
                <label class="col-sm-2 control-label form-label">Expense Category<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" name="category" style="padding-left:5px" data-live-search="true" >
                    <option data-icon="fa fa-money">Select One...</option>
                    <option data-icon="fa fa-money">Transportation Expenses</option>
                    <option data-icon="fa fa-money">Stationery Expenses</option>
                    <option data-icon="fa fa-money">Food & Refreshments Expenses</option>
                    <option data-icon="fa fa-money">Communication Expenses</option>
                    <option data-icon="fa fa-money">First Aid Expenses</option>
                    <option data-icon="fa fa-money">Miscellaneous Expenses</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Amount<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="">Ksh.</i></div>
                    <input type="text" class="form-control" id="amount" name="amount">
                    <div class="input-group-addon">.00</div>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-default rcharge">Charge To Project</button>
            </form>
            <div class="panel panel-widget col-xs-12">
              <div class="panel-body table-responsive">

                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <td>Description</td>
                      <td>Category</td>
                      <td>Amount</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>KBV 564R Fuel</td>
                      <td>Transportation Expenses</td>
                      <td>Ksh. 1,500</td>
                    </tr>
                    <tr>
                      <td>Lunch for 3</td>
                      <td>Food & Refreshments Expenses</td>
                      <td>Ksh. 750</td>
                    </tr>
                  </tbody>
                </table>
                <form class="form-horizontal" style="overflow:hidden;border-top:5px solid #ddd;padding-top:20px" id="frmr3">
                  <button type="submit" class="btn btn-default rsubmit">Submit Report</button>
                  <button type="submit" class="btn btn-warning rdiscard">Discard</button>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

