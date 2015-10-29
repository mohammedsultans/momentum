<!-- START CONTENT -->
<div id="leadscont" class="">

  <!-- Start Page Header -->
  <div class="page-header">
    <h1 class="title">Receive Goods & Services</h1>
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
      <div class="col-xs-12 col-sm-5">
        <div class="panel panel-default">
 
          <div class="panel-title">
            Supplier Information
            <ul class="panel-tools">
              <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
              <li><a class="icon expand-tool"><i class="fa fa-expand"></i></a></li>
              <li><a class="icon closed-tool"><i class="fa fa-times"></i></a></li>
            </ul>
          </div>

          <div class="panel-body">
            <form class="form-horizontal" id="frmi1">
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Supplier<span class="color10">*</span></label>
                <div class="col-sm-10">
                  <select class="selectpicker form-control" name="supplier" id="suppliers" data-live-search="true" >
                    <option data-icon="fa fa-user">Select Supplier...</option>
                  </select>  
                </div>              
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label">Invoice No<span class="color10">*</span></label>
                <div class="col-sm-4">
                  <input type="text" name="invno" class="form-control" id="">
                </div>
                <label class="col-sm-1 control-label form-label">Date<span class="color10">*</span></label>
                <div class="col-sm-5">
                  <div class="control-group">
                    <div class="controls">
                      <div class="input-prepend input-group">
                        <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" id="date-picker" class="form-control" name="date"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>

          <div class="panel-title">
            Received Item
          </div>
            <div class="panel-body">
              <form class="form-horizontal" id="frmi2">  
                <div class="form-group">
                  <label class="col-sm-2 control-label form-label">Item<span class="color10">*</span></label>
                  <div class="col-sm-10">
                    <input type="text" name="item" class="form-control" id="">
                  </div>              
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label form-label">Unit Price<span class="color10">*</span></label>
                  <div class="col-sm-6">
                    <div class="input-group">
                      <div class="input-group-addon"><i class="">Ksh.</i></div>
                      <input type="text" name="price" class="form-control" id="">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label form-label">Quantity<span class="color10">*</span></label>
                  <div class="col-sm-2">
                    <input type="text" name="qty" class="form-control" id="" value="1">
                  </div>
                  <label class="col-sm-2 control-label form-label">Tax</label>
                  <div class="col-sm-2">
                    <input type="text" name="tax" class="form-control" id="" placeholder="%">
                  </div>
                  <label class="col-sm-2 control-label form-label">Discount</label>
                  <div class="col-sm-2">
                    <input type="text" name="discount" class="form-control" id="" placeholder="%">
                  </div>
                </div>                
                <div class="form-group">
                  <label class="col-sm-2 control-label form-label">Ledger Category<span class="color10">*</span></label>
                  <div class="col-sm-10">
                    <select class="selectpicker form-control" name="ledger" id="ledgers" data-live-search="true" >
                      <option data-icon="fa fa-user">Select Ledger...</option>
                    </select>  
                  </div>              
                </div>
                <button type="submit" class="btn btn-default iadd">Add To GRN</button>
              </form>
            </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-7">
        <div class="panel panel-default">

          <div class="panel-title">
            GRN Particulars <span class="label label-primary">?</span>
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
                  <td>Unit Price</td>
                  <td>Qty</td>
                  <td>Tax %</td>
                  <td>Disc %</td>
                  <td>Sub-total</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Topocadastral Survey<br><span style="font-style:italic; font-size:11px">The Task goes here</span></td>
                  <td>450</td>
                  <td>3</td>
                  <td>5</td>
                  <td>5</td>
                  <td>Ksh. 1,350</td>
                </tr>
              </tbody>
            </table>
            <form class="form-horizontal" style="overflow:hidden;border-top:5px solid #ddd;padding-top:20px" id="frmi3">
              <div class="form-group">
                <label class="col-sm-2 control-label form-label" style="font-weight:600">Sub-Total</label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="">Ksh.</i></div>
                    <input type="text" class="form-control" id="amount" name="amount" readonly>
                    <div class="input-group-addon">.00</div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label" style="font-weight:600">Taxes</label>
                <div class="col-sm-4">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="">Ksh.</i></div>
                    <input type="text" name="tax" class="form-control" id="taxes" placeholder="Ksh" value="" readonly>
                  </div>                  
                </div>
                <label class="col-sm-2 control-label form-label" style="font-weight:600">Discount</label>
                <div class="col-sm-4">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="">Ksh.</i></div>
                    <input type="text" name="discount" class="form-control" id="disc" placeholder="Ksh" value="" readonly>
                  </div>                 
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label form-label" style="font-weight:600">TOTAL</label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="">Ksh.</i></div>
                    <input type="text" class="form-control" name="total" id="total" readonly>
                    <div class="input-group-addon">.00</div>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-warning idiscard float-r" style="margin-left:20px">Discard</button>
              <button type="submit" class="btn btn-default igenerate float-r">Post GRN</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- End Row -->
  </div>
</div>

