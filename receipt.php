<?php 
session_start();
require_once 'include/config.php';
require_once DATA_DIR . 'error_handler.php';
ErrorHandler::SetHandler();
require_once DATA_DIR . 'database_handler.php';
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
  <title>Momentum Receipts</title>

  <!-- ========== Css Files ========== -->
  <link href="css/root.css" rel="stylesheet">
  <script src="assets/js/plugins/formatmoney.js"></script>

  </head>
  <body>
<!-- START CONTENT -->
<div class="content" style="padding:0;width:370px;">

 <!-- //////////////////////////////////////////////////////////////////////////// --> 
<!-- START CONTAINER -->
<div class="">

  <!-- Start Invoice -->
  <div class="invoice row">
    <div class="receiptname">RECEIPT</div>
    <div class="logo">
      <img alt="logo" src="img/geoland.png"><br>
      <b>P.O BOX</b> <?php  echo $Add ?> <b>Tel:</b> <?php  echo $tel ?><br/>
      <b>Site:</b> <?php  echo $web ?> <b>Email:</b> <?php  echo $email ?>
    </div>

    <div class="line row row3" style="border-bottom:none">
      <div class="col-md-6 col-xs-6 padding-0 text-left" style="">
        <h4>DATE &amp; TIME</h4>
        <h2><?php echo $voucher->date; ?></h2>
      </div>
      <div class="col-md-6 col-xs-6 padding-0 text-right">
        <h4>RECEIPT NO</h4>
        <h2><?php echo $voucher->id; ?></h2>
      </div>
    </div>

    <div class="line row row3">
      <div class="col-md-6 col-xs-6 padding-0 text-left">
        <h4>RECEIVED FROM</h4>
        <h2><?php echo $voucher->party->name; ?></h2>
      </div>
      
    </div>

    <table class="table receipt">
      <thead class="title">
        <tr>
          <td>PAYMENT FOR</td>
          <td class="text-right">TOTAL</td>
        </tr>
      </thead>

      <tbody>
        <tr style="border-bottom: 50px solid rgba(221, 221, 221,0);">
          <td style=""><?php  echo $voucher->description; ?></td>

          <td class="text-right">Ksh. <script>document.writeln(( <?php  echo $voucher->amount ?>).formatMoney(2, '.', ','));</script></td>
        </tr>
       
        
        <tr style="">
          <td class="text-left" colspan="2">TOTAL<h4 class="total">Ksh. <script>document.writeln(( <?php  echo $voucher->amount ?>).formatMoney(2, '.', ','));</script></h4></td>
        </tr>
        <tr>
          <td colspan="2" class="text-left" style="font-size:11px;text-transform:capitalize">AMOUNT IN WORDS:<br><script>document.writeln(toWords(<?php echo $voucher->amount ?>));</script>Kenya shillings only</td>
        </tr>
        <tr>
          <td colspan="2" class="text-left" style="font-size:11px;text-transform:capitalize">ACCOUNT BALANCE: <?php if ($voucher->party->balance->amount < 0) { 
              echo '(Ksh. '.$voucher->party->balance->amount.')';
            }else { echo 'Ksh. '.$voucher->party->balance->amount; }?></td>
        </tr>
      </tbody>
      
    </table>

    <div class="signature">
       <p>Received By</p>
      <p><b><?php echo $voucher->user ?></b></p>
    </div>
<div class="row footer">
  <div class="col-md-12 text-left">
  Copyright &copy; <?php  echo date('Y') ?> Geoland Surveys
  </div>
  <div class="col-md-12 text-left">
    Momentum EIS by <a href="#">QET Systems Ltd</a> [www.qet.co.ke]
  </div> 
</div>
    
  </div>
  <!-- End Invoice -->

  </div>

</div>
<!-- END CONTAINER -->
 <!-- //////////////////////////////////////////////////////////////////////////// --> 
<script type="text/javascript">
  window.print();
  //window.onfocus=function(){ window.close();}
</script>

<!-- //////////////////////////////////////////////////////////////////////////// --> 






</body>

<!-- Mirrored from egemem.com/theme/kode/v1.1/invoice.html by HTTrack Website Copier/3.x [XR&CO'2013], Thu, 30 Jul 2015 15:49:09 GMT -->
</html>