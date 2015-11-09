<?php
	error_reporting(0);
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
				case 100:
					$this->header('Profit & Loss Statement');
					Body::PLStatement();	
					break;

				case 101:
					$this->header('Trial Balance');
					Body::TrialBalance();	
					break;

				case 102:
					$this->header('Balance Sheet');
					Body::BalanceSheet();		
					break;

				case 103:
					$this->header('Statement of Cash Flows');
					Body::CashFlows();		
					break;

				case 110:
					$this->header("Today's Transactions");
					Body::TodaysTransactions();				
					break;

				case 111:
					$this->header('Ledger Statements');
					Body::LedgerStatements();				
					break;

				case 112:
					$this->header('Debtors List');
					Body::DebtorsList();				
					break;

				case 113:
					$this->header('Creditors List');
					Body::CreditorsList();				
					break;

				case 120:
					$this->header("Today's Sales");
					Body::TodaysSales();	
					break;

				case 121:
					$this->header('Sales Report');
					Body::SalesReport();	
					break;

				case 122:
					$this->header('Sales By Cashier');
					Body::SalesByCashier();		
					break;

				case 123:
					$this->header('Sales By Item');
					Body::SalesByItem();		
					break;

				case 124:
					$this->header('Sales By Client');
					Body::SalesByClient();		
					break;

				case 130:
					$this->header("Today's Expenses");
					Body::TodaysExpenses();	
					break;

				case 131:
					$this->header('Expenses Report');
					Body::ExpensesReport();	
					break;

				case 132:
					$this->header('Expenses By Cashier');
					Body::ExpensesByCashier();		
					break;

				case 133:
					$this->header('Expenses By Description');
					Body::ExpensesByDescription();		
					break;

				case 134:
					$this->header('Expenses By Supplier');
					Body::ExpensesBySupplier();		
					break;

				case 135:
					$this->header('Claims Per Employee');
					Body::ClaimsPerEmployee();		
					break;

				case 200:
					$this->header('Client Register');
					Body::ClientRegister();	
					break;

				case 201:
					$this->header('Client Quotations');
					Body::ClientQuotations();	
					break;

				case 202:
					$this->header('Client Statement');
					Body::ClientStatement();		
					break;

				case 203:
					$this->header('All Sales Invoices');
					Body::AllClientInvoices();		
					break;

				case 204:
					$this->header('All Quotations');
					Body::AllClientQuotations();		
					break;

				case 300:
					$this->header('Supplier Register');
					Body::SupplierRegister();				
					break;

				case 301:
					$this->header('Supplier Orders');
					Body::SupplierOrders();				
					break;

				case 302:
					$this->header('Supplier Statement');
					Body::SupplierStatement();				
					break;

				case 303:
					$this->header('All Purchase Invoices');
					Body::PurchaseInvoices();				
					break;

				case 304:
					$this->header('All Purchase Orders');
					Body::PurchaseOrders();				
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

			  <div class="invoice row reporting">
			    <div class="invoicename">'.$title.'</div>
			    <div class="logo">
			      <img src="img/geoland.png" alt="logo"><br>
			      <b>P.O BOX</b> '.$this->company->address.' <b>Tel:</b> '.$this->company->phone.'<br/>
			      <b>Site:</b> '.$this->company->web.' <b>Email:</b> '.$this->company->email.'
			    </div>';
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
			  //window.print();
			  //window.onfocus=function(){ window.close();}
			</script>
			</body>
			</html>';
		}
		
	}

	class Body
	{
		
		public static function ClientRegister()
		{
			$collection = Client::GetAllClients();
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>ALL CLIENTS</h4>';

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>NAME</td>
			          <td>TELEPHONE</td>
					  <td>BALANCE</td>
			        </tr>
			      </thead>
			      <tbody>';

			$total = 0.00;

			foreach ($collection as $model) {
			    echo '<tr>
			      <td>'.$model->name.'</td>
			      <td>'.$model->telephone.'</td>
			      <td style="text-align:right;padding-right:7px"><script>document.writeln(('.$model->balance->amount.').formatMoney(2, \'.\', \',\'));</script></td>
			      </tr>';
			    $total += $model->balance->amount;
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
				    <p style="margin: 5px 0 0 5px">Total Debt: <b>Ksh. <script>document.writeln(('.($total).').formatMoney(2, \'.\', \',\'));</script></b></p>
				</div>';
		}

		public static function ClientQuotations()
		{
			$statement = TransactionVouchers::ClientQuotations($_GET['sid'], $_GET['period'], $_GET['all']);
			$client = Client::GetClient($_GET['sid']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>'.$client->name.' QUOTATIONS</h4>';
			if ($_GET['period'] != '' && $_GET['period'] ) {
				echo '<h5 style="margin-top:-10px">Period: '.$_GET['period'].'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>DATE</td>
			          <td>QUOTE ID</td>
			          <td>PURPOSE</td>
			          <td>STATUS</td>
					  <td>TOTAL</td>
			        </tr>
			      </thead>
			      <tbody>';

			$total = 0.00;
			$invoiced = 0.00;

			foreach ($statement as $item) {
			    echo '<tr>
			      <td>'.$item['date'].'</td>
			      <td>'.$item['id'].'</td>';

			    if ($item['project_id'] != null && $item['project_id'] != "") {
			    	$project = Project::GetProject($item['project_id']);
			    	echo '<td>'.$project->name.'</td>';
			    }else{
			    	echo '<td></td>';
			    }

			    if ($item['status'] == 1) {
			    	echo '<td style="color:#232836">CREATED</td>';
			    }else{
			    	echo '<td style="color:#27c97b">INVOICED</td>';
			    	$invoiced += $item['total'];
			    }

			    echo '<td class="text-right" style="padding: 0 5px;"><script>document.writeln(('.$item['total'].').formatMoney(2, \'.\', \',\'));</script></td>
			    </tr>';

			    $total += $item['total'];
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
				    <p style="margin: 5px 0 0 5px">Total Quoted: <b>Ksh. <script>document.writeln(('.($total).').formatMoney(2, \'.\', \',\'));</script></b></p>
				    <p style="margin: 5px 0 0 5px">Total Invoiced: <b>Ksh. <script>document.writeln(('.($invoiced).').formatMoney(2, \'.\', \',\'));</script></b></p>
				</div>';
		}

		public static function ClientStatement()
		{
			$statement = TransactionVouchers::ClientStatement($_GET['sid'], $_GET['period'], $_GET['all']);
			$client = Client::GetClient($_GET['sid']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>'.$client->name.' STATEMENT</h4>';
			if ($_GET['period'] != '' && $_GET['period'] ) {
				echo '<h5 style="margin-top:-10px">Period: '.$_GET['period'].'</h5>';
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

		public static function AllClientQuotations()
		{
			$collection = Quotation::GetAllQuotations($_GET['period'], $_GET['all']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>ALL QUOTATIONS</h4>';
			if ($_GET['period'] != '' && $_GET['period'] ) {
				echo '<h5 style="margin-top:-10px">Period: '.$_GET['period'].'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>DATE</td>
			          <td>QUOTE ID</td>
			          <td>CLIENT NAME</td>
			          <td>PURPOSE</td>
			          <td>INVOICED</td>
					  <td>TOTAL</td>
			        </tr>
			      </thead>
			      <tbody>';

			$total = 0.00;
			$invoiced = 0.00;
			$itms = 0;

			foreach ($collection as $item) {
			    echo '<tr>
			      <td>'.$item->date.'</td>
			      <td>'.$item->id.'</td>
			      <td>'.$item->client->name.'</td>';

			    if ($item->projectId != null && $item->projectId != "" && $item->projectId != 0) {
			    	$project = Project::GetProject($item->projectId);
			    	echo '<td>'.$project->name.'</td>';
			    }else{
			    	echo '<td></td>';
			    }

			    if ($item->status == 1) {
			    	echo '<td style="color:#232836">CREATED</td>';
			    }else{
			    	echo '<td style="color:#27c97b">INVOICED</td>';
			    	$invoiced += $item->total;
			    }

			    echo '<td class="text-right" style="padding: 0 5px;"><script>document.writeln(('.$item->total.').formatMoney(2, \'.\', \',\'));</script></td>
			    </tr>';

			    $total += $item->total;
			    ++$itms;
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
			    	<p style="margin: 5px 0 0 5px">Total Quotes: <b>'.$itms.'</b></p>
					<p style="margin: 5px 0 0 5px">Total Quoted: <b>Ksh. <script>document.writeln(('.($total).').formatMoney(2, \'.\', \',\'));</script></b></p>
					<p style="margin: 5px 0 0 5px">Total Invoiced: <b>Ksh. <script>document.writeln(('.($invoiced).').formatMoney(2, \'.\', \',\'));</script></b></p>
				</div>';
		}

		public static function AllClientInvoices()
		{
			$collection = SalesInvoice::GetAllInvoices($_GET['period'], $_GET['all']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>ALL INVOICES</h4>';
			if ($_GET['period'] != '' && $_GET['period'] ) {
				echo '<h5 style="margin-top:-10px">Period: '.$_GET['period'].'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>DATE</td>
			          <td>INV NO</td>
			          <td>CLIENT NAME</td>
			          <td>PURPOSE</td>
					  <td>TOTAL</td>
			        </tr>
			      </thead>
			      <tbody>';

			$total = 0.00;
			$itms = 0;

			foreach ($collection as $item) {
				$client = Client::GetClient($item['client_id']);
			    echo '<tr>
			      <td>'.$item['datetime'].'</td>
			      <td>'.$item['id'].'</td>
			      <td>'.$client->name.'</td>
				  <td>'.$item['description'].'</td>';

			    echo '<td class="text-right" style="padding: 0 5px;"><script>document.writeln(('.$item['total'].').formatMoney(2, \'.\', \',\'));</script></td>
			    </tr>';

			    $total += $item['total'];
			    ++$itms;
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
			    	<p style="margin: 5px 0 0 5px">Total Invoices: <b>'.$itms.'</b></p>
					<p style="margin: 5px 0 0 5px">Total Invoiced: <b>Ksh. <script>document.writeln(('.($total).').formatMoney(2, \'.\', \',\'));</script></b></p>
				</div>';
		}

		public static function SupplierRegister()
		{
			$collection = Supplier::GetAllSuppliers();
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>ALL SUPPLIERS</h4>';

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>COMPANY</td>
			          <td>CONTACT</td>
			          <td>TELEPHONE</td>
					  <td>BALANCE</td>
			        </tr>
			      </thead>
			      <tbody>';

			$total = 0.00;

			foreach ($collection as $model) {
			    echo '<tr>
			      <td>'.$model->name.'</td>
			      <td>'.$model->person.'</td>
			      <td>'.$model->telephone.'</td>
			      <td style="text-align:right;padding-right:7px"><script>document.writeln(('.$model->balance->amount.').formatMoney(2, \'.\', \',\'));</script></td>
			      </tr>';
			    $total += $model->balance->amount;
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
				    <p style="margin: 5px 0 0 5px">Total Credit: <b>Ksh. <script>document.writeln(('.($total).').formatMoney(2, \'.\', \',\'));</script></b></p>
				</div>';
		}

		public static function SupplierOrders()
		{
			$statement = TransactionVouchers::SupplierOrders($_GET['sid'], $_GET['period'], $_GET['all']);
			$party = Supplier::GetSupplier($_GET['sid']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>'.$party->name.' ORDERS</h4>';
			if ($_GET['period'] != '' && $_GET['period'] ) {
				echo '<h5 style="margin-top:-10px">Period: '.$_GET['period'].'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>DATE</td>
			          <td>ORDER ID</td>
			          <td>PURPOSE</td>
			          <td>STATUS</td>
					  <td>TOTAL</td>
			        </tr>
			      </thead>
			      <tbody>';

			$total = 0.00;
			$purchased = 0.00;

			foreach ($statement as $item) {
			    echo '<tr>
			      <td>'.$item['date'].'</td>
			      <td>'.$item['id'].'</td>';

			    if ($item['project_id'] != null && $item['project_id'] != "") {
			    	$project = Project::GetProject($item['project_id']);
			    	echo '<td>'.$project->name.'</td>';
			    }else{
			    	echo '<td></td>';
			    }

			    if ($item['status'] == 1) {
			    	echo '<td style="color:#232836">CREATED</td>';
			    }else{
			    	echo '<td style="color:#27c97b">PURCHASED</td>';
			    	$purchased += $item['total'];
			    }

			    echo '<td class="text-right" style="padding: 0 5px;"><script>document.writeln(('.$item['total'].').formatMoney(2, \'.\', \',\'));</script></td>
			    </tr>';

			    $total += $item['total'];
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
				    <p style="margin: 5px 0 0 5px">Total Ordered: <b>Ksh. <script>document.writeln(('.($total).').formatMoney(2, \'.\', \',\'));</script></b></p>
				    <p style="margin: 5px 0 0 5px">Total Purchased: <b>Ksh. <script>document.writeln(('.($purchased).').formatMoney(2, \'.\', \',\'));</script></b></p>
				</div>';
		}

		public static function SupplierStatement()
		{
			$statement = TransactionVouchers::SupplierStatement($_GET['sid'], $_GET['period'], $_GET['all']);
			$supplier = Supplier::GetSupplier($_GET['sid']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>'.$supplier->name.'</h4>';
			if ($_GET['period'] != '' && $_GET['period'] ) {
				echo '<h5 style="margin-top:-10px">Period: '.$_GET['period'].'</h5>';
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

		public static function PurchaseOrders()
		{
			$collection = PurchaseOrder::GetAllOrders($_GET['period'], $_GET['all']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>ALL PURCHASE ORDERS</h4>';
			if ($_GET['period'] != '' && $_GET['period'] ) {
				echo '<h5 style="margin-top:-10px">Period: '.$_GET['period'].'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>DATE</td>
			          <td>ORDER ID</td>
			          <td>COMPANY</td>
			          <td>PURPOSE</td>
			          <td>STATUS</td>
					  <td>TOTAL</td>
			        </tr>
			      </thead>
			      <tbody>';

			$total = 0.00;
			$invoiced = 0.00;
			$itms = 0;

			foreach ($collection as $item) {
			    echo '<tr>
			      <td>'.$item->date.'</td>
			      <td>'.$item->id.'</td>
			      <td>'.$item->party->name.'</td>';

			    if ($item->projectId != null && $item->projectId != "" && $item->projectId != 0) {
			    	$project = Project::GetProject($item->projectId);
			    	echo '<td>'.$project->name.'</td>';
			    }else{
			    	echo '<td></td>';
			    }

			    if ($item->status == 1) {
			    	echo '<td style="color:#232836">CREATED</td>';
			    }else{
			    	echo '<td style="color:#27c97b">ORDERED</td>';
			    	$invoiced += $item->total;
			    }

			    echo '<td class="text-right" style="padding: 0 5px;"><script>document.writeln(('.$item->total.').formatMoney(2, \'.\', \',\'));</script></td>
			    </tr>';

			    $total += $item->total;
			    ++$itms;
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
			    	<p style="margin: 5px 0 0 5px">Total Quotes: <b>'.$itms.'</b></p>
					<p style="margin: 5px 0 0 5px">Total Quoted: <b>Ksh. <script>document.writeln(('.($total).').formatMoney(2, \'.\', \',\'));</script></b></p>
					<p style="margin: 5px 0 0 5px">Total Invoiced: <b>Ksh. <script>document.writeln(('.($invoiced).').formatMoney(2, \'.\', \',\'));</script></b></p>
				</div>';
		}

		public static function PurchaseInvoices()
		{
			$collection = PurchaseInvoice::GetAllInvoices($_GET['period'], $_GET['all']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>ALL PURCHASE INVOICES</h4>';
			if ($_GET['period'] != '' && $_GET['period'] ) {
				echo '<h5 style="margin-top:-10px">Period: '.$_GET['period'].'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>DATE</td>
			          <td>INV NO</td>
			          <td>COMPANY</td>
			          <td>PURPOSE</td>
					  <td>TOTAL</td>
			        </tr>
			      </thead>
			      <tbody>';

			$total = 0.00;
			$itms = 0;

			foreach ($collection as $item) {
				$party = Supplier::GetSupplier($item['party_id']);
			    echo '<tr>
			      <td>'.$item['date'].'</td>
			      <td>'.$item['id'].'</td>
			      <td>'.$party->name.'</td>
				  <td>'.$item['description'].'</td>';

			    echo '<td class="text-right" style="padding: 0 5px;"><script>document.writeln(('.$item['total'].').formatMoney(2, \'.\', \',\'));</script></td>
			    </tr>';

			    $total += $item['total'];
			    ++$itms;
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
			    	<p style="margin: 5px 0 0 5px">Total Invoices: <b>'.$itms.'</b></p>
					<p style="margin: 5px 0 0 5px">Total Invoiced: <b>Ksh. <script>document.writeln(('.($total).').formatMoney(2, \'.\', \',\'));</script></b></p>
				</div>';
		}
	}

	new Reports();
?>