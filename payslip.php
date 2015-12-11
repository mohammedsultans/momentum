<?php 

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
$add=$row['Address'];
$web=$row['Website'];
$email=$row['Email'];
$logo=$row['Logo'];

$slip = json_decode($_POST['payslip']);

?><!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from egemem.com/theme/kode/v1.1/invoice.html by HTTrack Website Copier/3.x [XR&CO'2013], Thu, 30 Jul 2015 15:49:08 GMT -->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Kode is a Premium Bootstrap Admin Template, It's responsive, clean coded and mobile friendly">
  <meta name="keywords" content="bootstrap, admin, dashboard, flat admin template, responsive," />
  <title>Momentum - Payslips</title>

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
    <div class="line row row3" style="border-bottom:none">
      <div class="col-md-12 padding-0 text-left" style="">
        <h2><?php echo $comname; ?></h2>
        <h4><?php echo 'Address: P.O Box '.$add ?></h4>
        <h4><?php echo 'Telephone: '.$tel; ?></h4>
      </div>
    </div>

    <div class="line row row3" style="border-bottom:none">
      <div class="col-md-8 col-xs-8 padding-0 text-left" style="">
        <h4><?php echo 'Name: '.$slip->employee->name; ?></h4>
        <h4><?php echo 'Role: '.$slip->employee->position.' ('.$slip->employee->department.')'; ?></h4>
      </div>
      <div class="col-md-4 col-xs-4 padding-0 text-right">
        <h4><?php echo 'P/F No: '.intval($slip->employee->id); ?></h4>
        <h4><?php echo 'Date: '.$slip->date; ?></h4>
      </div>
    </div>

    <table class="table receipt">
      <thead class="title">
        <tr style="text-align:center">
          <td>Description</td>
          <td>DR</td>
          <td>CR</td>
        </tr>
      </thead>

      <tbody>
        <tr style="text-align:center">
          <td style="text-tranform: capitalize;">Basic Salary</td>
          <td><script>document.writeln((0).formatMoney(2, '.', ','));</script></td>
          <td><script>document.writeln((<?php  echo $slip->salary ?>).formatMoney(2, '.', ','));</script></td>
        </tr>
        <?php 
          foreach ($slip->additions as $add) {?>
            <tr style="text-align:center">
              <td style="text-tranform: capitalize;"><?php  echo $add->type;?></td>
              <td><script>document.writeln((0).formatMoney(2, '.', ','));</script></td>
              <td><script>document.writeln((<?php  echo $add->amount ?>).formatMoney(2, '.', ','));</script></td>
            </tr>
          <?php }
        ?>

        <?php 
          foreach ($slip->deductions as $deduct) {?>
            <tr style="text-align:center">
              <td style="text-tranform: capitalize;"><?php  echo $deduct->type;?></td>
              <td><script>document.writeln((<?php  echo $deduct->amount ?>).formatMoney(2, '.', ','));</script></td>
              <td><script>document.writeln((0).formatMoney(2, '.', ','));</script></td>
            </tr>
          <?php }
        ?>
       
       
        
        <tr style="text-align:center">
          <td><h6>TOTALS:</h6></td>
          <td style="border-left:1px solid #ddd"><h6>Ksh. <script>document.writeln(( <?php  echo $slip->t_deductions ?>).formatMoney(2, '.', ','));</script></h6></td>
          <td style="border-left:1px solid #ddd"><h6>Ksh. <script>document.writeln(( <?php  echo ($slip->t_additions + $slip->salary) ?>).formatMoney(2, '.', ','));</script></h6></td>
        </tr>
        <tr style="font-weight:bold;border:5px solid #ddd">
          <td colspan="3" style="text-align:center"><h6>NET PAY: Ksh. <script>document.writeln(( <?php  echo $slip->netpay ?>).formatMoney(2, '.', ','));</script></h6></td>
          </tr>
        <tr>
          <td colspan="3" class="text-left" style="font-size:11px;text-transform:capitalize">AMOUNT IN WORDS: <script>document.writeln(toWords(<?php echo $slip->netpay ?>));</script>Kenya shillings only</td>
        </tr>
      </tbody>
      
    </table>

    <div class="signature">
       <p>PAYSLIP -> MONTH: <b><?php echo $slip->month; ?></b></p>
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