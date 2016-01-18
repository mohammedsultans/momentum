<?php 

require_once 'include/config.php';
require_once DATA_DIR . 'error_handler.php';
ErrorHandler::SetHandler();
require_once DATA_DIR . 'database_handler.php';
$username = "System User";
/*date_default_timezone_set('America/Los_Angeles'); 
if(isset($_SESSION['valid_user'])){
$username=$_SESSION['valid_user'];
$result =mysql_query("select * from users where name='".$username."'");
$row=mysql_fetch_array($result);
$usertype=stripslashes($row['position']);
$userid=stripslashes($row['userid']);
include('functions.php'); 
}
else{echo"<script>window.location.href = \"index.php\";</script>";}

?>

<?php*/
//$id=$_POST['id'];
$sql = 'SELECT * FROM company';
$row =  DatabaseHandler::GetRow($sql);
$comname=$row['CompanyName'];
$tel=$row['Tel'];
$Add=$row['Address'];
$web=$row['Website'];
$email=$row['Email'];
$logo=$row['Logo'];

$voucher = json_decode($_POST['voucher']);

?><!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from egemem.com/theme/kode/v1.1/invoice.html by HTTrack Website Copier/3.x [XR&CO'2013], Thu, 30 Jul 2015 15:49:08 GMT -->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Kode is a Premium Bootstrap Admin Template, It's responsive, clean coded and mobile friendly">
  <meta name="keywords" content="bootstrap, admin, dashboard, flat admin template, responsive," />
  <title>Momentum - Sales Invoice</title>

  <!-- ========== Css Files ========== -->
  <link href="css/root.css" rel="stylesheet">
  <script src="assets/js/plugins/formatmoney.js"></script>

  </head>
  <body>
<!-- START CONTENT -->
<div class="content" style="padding:0;width:760px;">

 <!-- //////////////////////////////////////////////////////////////////////////// --> 
<!-- START CONTAINER -->
<div class="">

  <!-- Start Invoice -->
  <div class="invoice row">
    <div class="invoicename">SALES INVOICE</div>
    <div class="logo">
      <img src="img/geoland.png" alt="logo"><br>
      <b>P.O BOX</b> <?php  echo $Add ?> <b>Tel:</b> <?php  echo $tel ?><br/>
      <b>Site:</b> <?php  echo $web ?> <b>Email:</b> <?php  echo $email ?>
    </div>

    <div class="line row row2" style="border-bottom:none;">
      <div class="col-md-6 col-xs-6 padding-0 text-left">
        <h4>DATE & TIME</h4>
        <h2><?php echo $voucher->date; ?></h2>
      </div>
      <div class="col-md-6 col-xs-6 padding-0 text-right">
        <h4>INVOICE NO</h4>
        <h2><?php echo $voucher->id; ?></h2>
      </div>
    </div>

    <div class="line row row2">
      <div class="col-md-6 col-xs-6 padding-0 text-left">
        <h4>PREPARED FOR</h4>
        <h2><?php echo $voucher->party->name; ?></h2>
      </div>
      <div class="col-md-6 col-xs-6 padding-0 text-right">
        <h4>SCOPE</h4>
        <h2><?php echo $voucher->scope; ?></h2>
      </div>
    </div>

    <table class="table">
      <thead class="title">
        <tr>
          <td>SERVICE DESCRIPTION</td>
          <td>QTY</td>
          <td>UNIT PRICE</td>
          <td>TAX</td>
          <td class="text-right">SUB-TOTAL</td>
        </tr>
      </thead>
      <tbody>
      <?php 

        foreach ($voucher->extras->advices as $advice) {
          foreach ($advice->lineItems as $item) {
            ?> 
            <tr>
              <td><?php echo $item->itemName; ?> <p><?php echo $item->itemDesc; ?></p></td>
              <td><?php echo $item->quantity; ?></td>
              <td><script>document.writeln((<?php echo $item->unitPrice; ?>).formatMoney(2, '.', ','));</script></td>
              <td><?php echo $item->tax; ?>%</td>
              <td class="text-right">Ksh. <script>document.writeln((<?php echo ($item->unitPrice * $item->quantity * floatval(100.00 + floatval($item->tax))/100); ?>).formatMoney(2, '.', ','));</script></td>
            </tr>
            <?php
          }
        }

      ?>
        
      </tbody>
      <tfoot>
        <tr>
          <td class="text-left" style="font-size:12px;">
            <b>VATABLE AMOUNT:</b> Ksh. <script>document.writeln((<?php echo $voucher->extras->amount; ?>).formatMoney(2, '.', ','));</script><br>
            <b>TOTAL VAT:</b> Ksh. <script>document.writeln((<?php echo $voucher->extras->tax; ?>).formatMoney(2, '.', ','));</script><br>
            <?php if (floatval($voucher->extras->discount) != 0.00){ 
              echo '<b>DISCOUNT:</b>'.$voucher->extras->discount.'%';
            }else {  }?>
          </td>
          <td></td>
          <td></td>
          <td></td>          
          <td class="text-right">GRAND TOTAL<h4 class="total">Ksh. <script>document.writeln((<?php echo $voucher->extras->total; ?>).formatMoney(2, '.', ','));</script></h4></td>
        </tr>
      </tfoot>
      
    </table>
    <p>PRICES INCLUSIVE OF VAT WHERE APPLICABLE</p>
      <p style="font-size:11px;text-transform:capitalize">ACCOUNT BALANCE: <?php if ($voucher->party->balance->amount < 0) { 
        echo "(Ksh. <script>document.writeln((".$voucher->party->balance->amount.").formatMoney(2, '.', ','));</script>)";
        }else { echo "Ksh. <script>document.writeln((".$voucher->party->balance->amount.").formatMoney(2, '.', ','));</script>"; }?>
      </p>
    
    <div class="invfoot">
      <div class="signature">
        <p>Invoice Prepared By:</p>
        <p><b><?php echo $voucher->user ?></b></p>
      </div>
      <div class="row" style="line-height:13px;font-size:10px;border-top: 2px solid #e4e4e4;padding-top:5px">
        <div class="col-md-4 text-left">Copyright © <?php  echo date('Y')." ".$comname ?></div>
        <div class="col-md-8 text-right">Momentum ERP by <br><a href="#">QET Systems Ltd</a> [www.qet.co.ke]
      </div> 
    </div>
    
  </div>
  <!-- End Invoice -->

  </div>

</div>
<!-- END CONTAINER -->
 <!-- //////////////////////////////////////////////////////////////////////////// --> 
<!-- End Footer -->


</div>
<!-- End Content -->

<!-- //////////////////////////////////////////////////////////////////////////// --> 



<script type="text/javascript">
  window.print();
  //window.onfocus=function(){ window.close();}
</script>


</body>

<!-- Mirrored from egemem.com/theme/kode/v1.1/invoice.html by HTTrack Website Copier/3.x [XR&CO'2013], Thu, 30 Jul 2015 15:49:09 GMT -->
</html>