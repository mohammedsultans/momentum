<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Client Invoicing</h1>
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
            Billing Information
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

          <div class="panel-body">
            <form class="form-horizontal" id="frmi1">
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
                <label class="col-sm-2 control-label form-label">Purpose<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" name="purpose" id="projects" style="padding-left:5px" data-live-search="true" >
                    <option data-icon="fa fa-archive">Select One...</option>
                    <option data-icon="fa fa-archive">General Boundary Survey</option>
                    <option data-icon="fa fa-archive">Fixed Boundary Survey</option>
                    <option data-icon="fa fa-archive">Topocadastral Survey</option>
                    <option data-icon="fa fa-archive">Engineering Survey</option>
                    <option data-icon="fa fa-archive">General Land Consultancy</option>
                    <option data-icon="fa fa-archive">GIS Training</option>
                  </select>  
                </div>              
              </div>   
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Quotation No<span class="color10">*</span></label>
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
              <!--button type="submit" class="btn btn-default iadd">Add To Invoice</button-->
            </form>
          </div>
        </div>

        
      </div>
      <div class="col-xs-12 col-sm-6">
        <div class="panel panel-default">

          <div class="panel-title">
            Invoice Particulars <span class="label label-primary">4</span>
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

              <div class="panel-body table-responsive">

                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <td>Item</td>
                      <td>Rate</td>
                      <td>QTY</td>
                      <td>Sub-total</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Kode Gaming Laptop</td>
                      <td>Ksh. 450</td>
                      <td>3</td>
                      <td>Ksh. 1,350</td>
                    </tr>
                    <tr>
                      <td>New Season Jacket</td>
                      <td>Ksh. 450</td>
                      <td>1</td>
                      <td>Ksh. 450</td>
                    </tr>
                    <tr>
                      <td>IO Mouse</td>
                      <td>Ksh. 450</td>
                      <td>1</td>
                      <td>Ksh. 450</td>
                    </tr>
                    <tr>
                      <td>Doe Bike</td>
                      <td>Ksh. 450</td>
                      <td>2</td>
                      <td>Ksh. 90</td>
                    </tr>
                  </tbody>
                </table>

                <form class="form-horizontal" style="overflow:hidden;border-top:5px solid #ddd;padding-top:20px" id="frmi3">
                  <div class="form-group">
                    <label class="col-sm-3 control-label form-label" style="font-weight:600">AMOUNT</label>
                    <div class="col-sm-9">
                      <div class="input-group">
                        <div class="input-group-addon"><i class="">Ksh.</i></div>
                        <input type="text" class="form-control" id="amount" name="amount" readonly>
                        <div class="input-group-addon">.00</div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label form-label">Taxes</label>
                    <div class="col-sm-3">
                      <input type="text" name="tax" class="form-control" id="taxes" placeholder="Tax" value="" readonly>
                    </div>
                    <label class="col-sm-3 control-label form-label" style="font-weight:600">Discount</label>
                    <div class="col-sm-3">
                      <input type="text" name="discount" class="form-control" id="disc" placeholder="Discount %" value="">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-sm-3 control-label form-label" style="font-weight:600">TOTAL</label>
                    <div class="col-sm-9">
                      <div class="input-group">
                        <div class="input-group-addon"><i class="">Ksh.</i></div>
                        <input type="text" class="form-control" name="total" id="total" readonly>
                        <div class="input-group-addon">.00</div>
                      </div>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-warning idiscard float-r" style="margin-left:20px">Discard</button>
                  <button type="submit" class="btn btn-default ipost float-r">Post Invoice</button>
                  
                </form>
              </div>

        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

