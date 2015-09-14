<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Receive Payment</h1>
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
            Payment Particulars
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

              <div class="panel-body">
                <form class="form-horizontal">  
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Client<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="client" id="clients" style="padding-left:5px" data-live-search="true" >
                        <option data-icon="fa fa-user">Select Customer...</option>
                        <option data-icon="fa fa-user">Alex Mbaka [Bal]</option>
                        <option data-icon="fa fa-user">Prince Munene [Bal]</option>
                        <option data-icon="fa fa-user">Chase Assurance [Bal]</option>
                        <option data-icon="fa fa-user">Genghis Capital [Bal]</option>
                        <option data-icon="fa fa-user">Light House Properties [Bal]</option>
                        <option data-icon="fa fa-user">Orchid Capital</option>
                        <option data-icon="fa fa-user">Rafiki Microfinance</option>
                        <option data-icon="fa fa-user">Tulip Healthcare</option>
                        <option data-icon="fa fa-user">Rivieres Finance</option>
                      </select>  
                    </div>              
                  </div>
                  
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Category<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <select class="selectpicker form-control" name="category" id="projects" style="padding-left:5px" data-live-search="true" >
                        <option data-icon="fa fa-suitcase">General Invoice</option>
                        <option data-icon="fa fa-suitcase">Project XXX-XXXXX</option>
                        <option data-icon="fa fa-suitcase">Project XXX-XXXXX</option>
                        <option data-icon="fa fa-suitcase">Project XXX-XXXXX</option>
                        <option data-icon="fa fa-suitcase">Project XXX-XXXXX</option>
                      </select>  
                    </div>           
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Payment Mode<span class="color10">*</span></label>
                    <div class="col-sm-4">
                      <select class="selectpicker form-control" name="mode" id="banks" style="padding-left:5px">
                        <option data-icon="fa fa-money">Select Mode...</option>
                        <option data-icon="fa fa-money">Cash</option>
                        <option data-icon="fa fa-money">Family Bank</option>
                        <option data-icon="fa fa-money">Equity Bank</option>
                        <option data-icon="fa fa-money">Chase Bank</option>
                        <option data-icon="fa fa-money">Standard Chartered Bank</option>
                        <option data-icon="fa fa-money">M-Pesa</option>
                      </select>  
                    </div> 
                    <label class="col-sm-2 control-label form-label">Voucher No</label>
                    <div class="col-sm-4">
                      <input type="text" name="voucher" class="form-control" id="">
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Amount<span class="color10">*</span></label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <div class="input-group-addon"><i class="">Ksh.</i></div>
                        <input type="text" class="form-control" name="amount" id="">
                        <div class="input-group-addon">.00</div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label form-label">Payment For</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" name="descr" rows="5" id=""></textarea>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-default psubmit">Submit</button>
                  <button type="submit" class="btn btn-warning pcancel">Cancel</button>
                </form>

              </div>

        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

