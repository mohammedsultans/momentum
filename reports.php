<?php
  	require_once 'include/config.php';
  	require_once DOMAIN_DIR . 'LSOfficeDomain.php';
	
	class Company
	{
		public $name;
		public $phone;
		public $address;
		public $web;
		public $email;
		public $logo;

		function __construct()
		{
			try {
				$sql = 'SELECT * FROM company';
				$row =  DatabaseHandler::GetRow($sql);
				$this->name = $row['CompanyName'];
				$this->phone = $row['Tel'];
				$this->address = $row['Address'];
				$this->web = $row['Website'];
				$this->email = $row['Email'];
				$this->logo = $row['Logo'];
			} catch (Exception $e) {
				
			}
		}
	}

	class Reports
	{
		public $company;
		public function __construct()
		{			
			$this->company = new Company();

			switch ($_GET['id']) {
				case 1:
					$this->header('Client Statement');
					$this->body('Client Statement');					
					break;

				case 2:
					$this->header('Supplier Statement');
					$this->body('Supplier Statement');					
					break;
				
				default:
					# code...
					break;
			}

			$this->footer();
		}
		/* REPORTS APPLICATION INTERFACE */
		public function header($title)
		{			
			echo 
			'<!DOCTYPE html>
			<html lang="en">
			  
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <meta name="viewport" content="width=device-width, initial-scale=1">
			  <title>Momentum - '.$title.'</title>
			  <link href="css/root.css" rel="stylesheet">
			  <script src="assets/js/plugins/formatmoney.js"></script>
			</head>
			<body>
			 <div class="content" style="padding:0;width:760px;">

			 <div class="">

			  <div class="invoice row">
			    <div class="invoicename">'.$title.'</div>
			    <div class="logo">
			      <img src="img/geoland.png" alt="logo"><br>
			      <b>P.O BOX</b> '.$this->company->address.' <b>Tel:</b> '.$this->company->phone.'<br/>
			      <b>Site:</b> '.$this->company->web.' <b>Email:</b> '.$this->company->email.'
			    </div>';
		}

		public function body($title)
		{
			switch ($title) {
				case 'Client Statement':
					Body::ClientStatement();
					break;

				case 'Supplier Statement':
					Body::SupplierStatement();
					break;
				
				default:
					# code...
					break;
			}
		}

		public function footer()
		{
			echo '
				<div class="invfoot">
			      <div class="signature">
			        <p>Report Pulled By: <b>'.SessionManager::GetUsername().'</b></p>
			      </div>
			      <div class="row" style="line-height:13px;font-size:10px;border-top: 2px solid #e4e4e4;padding-top:5px">
			        <div class="col-md-4 text-left">Copyright © '.date('Y').' '.$this->company->name.'</div>
			        <div class="col-md-8 text-right">Momentum ERP by <br><a href="#">QET Systems Ltd</a> [www.qet.co.ke]
			      </div> 
			    </div>			    
			   </div>
			  </div>
			</div>
			</div>
			<script type="text/javascript">
			  window.print();
			  //window.onfocus=function(){ window.close();}
			</script>
			</body>
			</html>';
		}
		
	}

	class Body
	{
		
		public static function ClientStatement()
		{
			$statement = TransactionVouchers::ClientStatement($_GET['cid'], $_GET['dates'], $_GET['all']);
			$client = Client::GetClient($_GET['cid']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>'.$client->name.'</h4>';
			if ($_GET['dates'] != '' && $_GET['dates'] ) {
				echo '<h5 style="margin-top:-10px">Period: '.$_GET['dates'].'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>DATE</td>
			          <td>TYPE</td>
			          <td>DR</td>
			          <td>CR</td>
			          <td>DESCRIPTION</td>
					  <td>BALANCE</td>
			        </tr>
			      </thead>
			      <tbody>';

			$cr = 0.00; $dr = 0.00;

			foreach ($statement as $item) {
			    echo '<tr>
			      <td style="width:90px">'.$item['when_booked'].'</td>
			      <td style="width: 100px">'.$item['type'].'</td>';

			    if ($item['effect'] == 'cr') {
			    	$cr += $item['amount'];
			    	echo '<td style="width: 100px"></td>
			      	<td style="width: 100px"><script>document.writeln(('.$item['amount'].').formatMoney(2, \'.\', \',\'));</script></td>';
			    }else{
			    	$dr += $item['amount'];
			    	echo '<td style="width: 100px"><script>document.writeln(('.$item['amount'].').formatMoney(2, \'.\', \',\'));</script></td>
			      	<td style="width: 100px"></td>';
			    }

			    echo '<td style="max-width: 220px;">'.$item['description'].'</td>
			      <td class="text-right" style="padding: 0 5px;"><script>document.writeln(('.$item['balance'].').formatMoney(2, \'.\', \',\'));</script></td>
			    </tr>';
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
				    <p style="margin: 5px 0 0 5px">Total Debits: <b>Ksh. <script>document.writeln(('.$dr.').formatMoney(2, \'.\', \',\'));</script></b></p>
				    <p style="margin: 5px 0 0 5px">Total Credits: <b>Ksh. <script>document.writeln(('.$cr.').formatMoney(2, \'.\', \',\'));</script></b></p>			    
				    <p style="margin: 5px 0 0 5px">Balance: <b>Ksh. <script>document.writeln(('.($dr - $cr).').formatMoney(2, \'.\', \',\'));</script></b></p>
				</div>';
		}

		public static function SupplierStatement()
		{
			$statement = TransactionVouchers::SupplierStatement($_GET['sid'], $_GET['dates'], $_GET['all']);
			$supplier = Supplier::GetSupplier($_GET['sid']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>'.$supplier->name.'</h4>';
			if ($_GET['dates'] != '' && $_GET['dates'] ) {
				echo '<h5 style="margin-top:-10px">Period: '.$_GET['dates'].'</h5>';
			}			  
			
			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>DATE</td>
			          <td>TYPE</td>
			          <td>DR</td>
			          <td>CR</td>
			          <td>DESCRIPTION</td>
					  <td>BALANCE</td>
			        </tr>
			      </thead>
			      <tbody>';

			$cr = 0.00; $dr = 0.00;

			foreach ($statement as $item) {
			    echo '<tr>
			      <td style="width:90px">'.$item['when_booked'].'</td>
			      <td style="width: 100px">'.$item['type'].'</td>';

			    if ($item['effect'] == 'cr') {
			    	$cr += $item['amount'];
			    	echo '<td style="width: 100px"></td>
			      	<td style="width: 100px"><script>document.writeln(('.$item['amount'].').formatMoney(2, \'.\', \',\'));</script></td>';
			    }else{
			    	$dr += $item['amount'];
			    	echo '<td style="width: 100px"><script>document.writeln(('.$item['amount'].').formatMoney(2, \'.\', \',\'));</script></td>
			      	<td style="width: 100px"></td>';
			    }

			    echo '<td style="max-width: 220px;">'.$item['description'].'</td>
			      <td class="text-right" style="padding: 0 5px;"><script>document.writeln(('.$item['balance'].').formatMoney(2, \'.\', \',\'));</script></td>
			    </tr>';
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo
			    <p style="margin: 5px 0 0 5px">Total Credits: <b>Ksh. <script>document.writeln(('.$cr.').formatMoney(2, \'.\', \',\'));</script></b></p>				    
			    <p style="margin: 5px 0 0 5px">Total Debits: <b>Ksh. <script>document.writeln(('.$dr.').formatMoney(2, \'.\', \',\'));</script></b></p>
				<p style="margin: 5px 0 0 5px">Balance: <b>Ksh. <script>document.writeln(('.($cr - $dr).').formatMoney(2, \'.\', \',\'));</script></b></p>
				</div>';
		}
	}

	new Reports();
?>