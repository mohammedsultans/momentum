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
				case 98:
					$this->header('Repair Balance Errors');
					FinancialReports::RepairTXBalanceErrors();	
					break;
				case 99:
					$this->header('Transaction Audit');
					//FinancialReports::TXAudit();	
					break;
				case 100:
					$this->header('Profit & Loss Statement');
					FinancialReports::PLStatement();	
					break;

				case 101:
					$this->header('Trial Balance');
					FinancialReports::TrialBalance();	
					break;

				case 102:
					$this->header('Balance Sheet');
					FinancialReports::BalanceSheet();		
					break;

				case 103:
					$this->header('Statement of Cash Flows');
					FinancialReports::CashFlows();		
					break;

				case 110:
					$this->header("Transactions report");
					FinancialReports::TransactionsReport();				
					break;

				case 111:
					$this->header('Ledger Statements');
					FinancialReports::LedgerStatement();				
					break;

				case 112:
					$this->header('Cash Book');
					FinancialReports::CashBook();				
					break;

				case 113:
					$this->header('Day Book');
					FinancialReports::DayBook();				
					break;

				case 114:
					$this->header('Debtors List');
					Body::DebtorsList();				
					break;

				case 115:
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

				case 400:
					$this->header('Employee Register');
					HRReports::EmployeeRegister();	
					break;

				case 401:
					$this->header('Employee Statement');
					HRReports::EmployeeStatement();
					break;

				case 410:
					$this->header('Employee Advances');
					HRReports::EmployeeAdvances();	
					break;

				case 411:
					$this->header('Employee Allowances');
					HRReports::EmployeeAllowances();
					break;

				case 412:
					$this->header('Employee Overtime');
					HRReports::EmployeeOvertime();
					break;

				case 413:
					$this->header('Payroll Summary');
					HRReports::PayrollSummary();
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

	class FinancialReports
	{
		private static function signate($amount)
		{
			if ($amount < 0) {
				echo '(<script>document.writeln(('.(-1*$amount).').formatMoney(2, \'.\', \',\'));</script>)';
			}else{
				echo '<script>document.writeln(('.$amount.').formatMoney(2, \'.\', \',\'));</script>';
			}			
		}

		public static function RepairTXBalanceErrors()
		{
			$collection = Auditor::ResetTransactionBalances();
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>TRANSACTION RERUN REPORT</h4>';

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>LEDGER ID</td>
			          <td>LEDGER</td>
			          <td>TYPE</td>
			          <td>BALANCE</td>
			        </tr>
			      </thead>
			      <tbody>';

			$assets = 0.00;
			$liability = 0.00;
			$capital = 0.00;
			$profit = 0.00;

			foreach ($collection as $model) {
				
				switch ($model->type) {
					case 'Asset':
						$assets += $model->amount;
						echo '<tr><td>'.$model->id.'</td><td>'.$model->name.'</td><td>'.$model->type.'</td><td>'.$model->amount.'</td></tr>';
						break;

					case 'Expense':
						$profit -= $model->amount;
						echo '<tr><td>'.$model->id.'</td><td>'.$model->name.'</td><td>'.$model->type.'</td><td>'.$model->amount.'</td></tr>';
						break;

					case 'Liability':
						$liability += $model->amount;
						echo '<tr><td>'.$model->id.'</td><td>'.$model->name.'</td><td>'.$model->type.'</td><td>'.$model->amount.'</td></tr>';
						break;

					case 'Revenue':
						$profit += $model->amount;
						echo '<tr><td>'.$model->id.'</td><td>'.$model->name.'</td><td>'.$model->type.'</td><td>'.$model->amount.'</td></tr>';
						break;

					case 'Equity':
						$capital += $model->amount;
						echo '<tr><td>'.$model->id.'</td><td>'.$model->name.'</td><td>'.$model->type.'</td><td>'.$model->amount.'</td></tr>';
						break;
					
					default:
						break;
				}
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
				    <p style="margin: 5px 0 0 5px">Total Assets: <b>Ksh. <script>document.writeln(('.($assets).').formatMoney(2, \'.\', \',\'));</script></b></p>
					<p style="margin: 5px 0 0 5px">Total Liability: <b>Ksh. <script>document.writeln(('.($liability).').formatMoney(2, \'.\', \',\'));</script></b></p>
					<p style="margin: 5px 0 0 5px">Total Capital: <b>Ksh. <script>document.writeln(('.($capital).').formatMoney(2, \'.\', \',\'));</script></b></p>
					<p style="margin: 5px 0 0 5px">Total Profit: <b>Ksh. <script>document.writeln(('.($profit).').formatMoney(2, \'.\', \',\'));</script></b></p>
				</div>';					
		}

		public static function TXAudit()
		{
			$collection = Auditor::AuditTransactions();
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>AUDIT STATEMENT</h4>';

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>TRANSACTION</td>
			          <td>ENTRIES</td>
			          <td>AMOUNT</td>
					  <td>DEFICIT</td>
			        </tr>
			      </thead>
			      <tbody>';

			$cr = 0.00;
			$dr = 0.00;

			foreach ($collection as $model) {
				echo '<tr><td>'.$model['id'].'</td><td>'.$model['entr'].'</td><td>'.$model['amount'].'</td><td>'.$model['defic'].'</td></tr>';
			}

			$diff = $cr - $dr;
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
				    <p style="margin: 5px 0 0 5px">Total Credits: <b>Ksh. <script>document.writeln(('.($cr).').formatMoney(2, \'.\', \',\'));</script></b></p>
					<p style="margin: 5px 0 0 5px">Total Debits: <b>Ksh. <script>document.writeln(('.($dr).').formatMoney(2, \'.\', \',\'));</script></b></p>';
					if ($diff >= 0) {
						echo '<p style="margin: 5px 0 0 5px">Net Profit/(Loss): <b>Ksh. <script>document.writeln(('.($diff).').formatMoney(2, \'.\', \',\'));</script></b></p>';
					}else{
						echo '<p style="margin: 5px 0 0 5px">Net Profit/(Loss): <b>(Ksh. <script>document.writeln(('.($diff * -1).').formatMoney(2, \'.\', \',\'));</script>)</b></p>';
					}
					echo '</div>';					
		}

		public static function PLStatement()
		{
			$collection = FinancialStatements::GetPLStatement($_GET['day'], $_GET['period']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>PROFIT AND LOSS STATEMENT</h4>';

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>LEDGER</td>
			          <td>DEBIT</td>
					  <td>CREDIT</td>
			        </tr>
			      </thead>
			      <tbody>';

			$cr = 0.00;
			$dr = 0.00;

			foreach ($collection as $model) {
			    echo '<tr>
			      <td>'.$model->name.'</td>';
			    if ($model->type == 'Expense') {
			    	echo '<td><script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script></td></td><td></tr>';
			    	$dr += $model->amount;
			    }else{
			    	echo '</td><td><td><script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			    	$cr += $model->amount;
			    }
			      //<td style="text-align:right;padding-right:7px"><script>document.writeln(('.$model->balance->amount.').formatMoney(2, \'.\', \',\'));</script></td>';
			    
			}

			$diff = $cr - $dr;
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
				    <p style="margin: 5px 0 0 5px">Total Credits: <b>Ksh. <script>document.writeln(('.($cr).').formatMoney(2, \'.\', \',\'));</script></b></p>
					<p style="margin: 5px 0 0 5px">Total Debits: <b>Ksh. <script>document.writeln(('.($dr).').formatMoney(2, \'.\', \',\'));</script></b></p>';
					if ($diff >= 0) {
						echo '<p style="margin: 5px 0 0 5px">Net Profit/(Loss): <b>Ksh. <script>document.writeln(('.($diff).').formatMoney(2, \'.\', \',\'));</script></b></p>';
					}else{
						echo '<p style="margin: 5px 0 0 5px">Net Profit/(Loss): <b>(Ksh. <script>document.writeln(('.($diff * -1).').formatMoney(2, \'.\', \',\'));</script>)</b></p>';
					}
					echo '</div>';					
		}

		public static function TrialBalance()
		{
			$collection = FinancialStatements::GetTrialBalance($_GET['day']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>TRIAL BALANCE</h4>';
			if ($_GET['day'] != '' && $_GET['day'] ) {
				echo '<h5 style="margin-top:-10px">As at: '.$_GET['day'].'</h5>';
			}else{
				echo '<h5 style="margin-top:-10px">As at: '.date('d/m/Y').'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>LEDGER</td>
			          <td>DEBIT</td>
					  <td>CREDIT</td>
			        </tr>
			      </thead>
			      <tbody>';

			$cr = 0.00;
			$dr = 0.00;

			foreach ($collection as $model) {
			    echo '<tr>
			      <td>'.$model->name.'</td>';
			    if ($model->type == 'Expense' || $model->type == 'Asset') {
			    	echo '<td><script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script></td><td></td></tr>';
			    	$dr += $model->amount;
			    }else{
			    	echo '<td></td><td><script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			    	$cr += $model->amount;
			    }
			      //<td style="text-align:right;padding-right:7px"><script>document.writeln(('.$model->balance->amount.').formatMoney(2, \'.\', \',\'));</script></td>';
			    
			}

			$diff = $cr - $dr;
			        
			echo '</tbody>
					<tfoot>
						<tr>
				          <td>TOTAL</td>
				          <td><b>Ksh. <script>document.writeln(('.($dr).').formatMoney(2, \'.\', \',\'));</script></td>
						  <td><b>Ksh. <script>document.writeln(('.($cr).').formatMoney(2, \'.\', \',\'));</script></td>
				        </tr>
					</tfoot>
			    </table>
			    <div class="logo">
				    <p style="margin: 5px 0 0 5px">Total Credits: <b>Ksh. <script>document.writeln(('.($cr).').formatMoney(2, \'.\', \',\'));</script></b></p>
					<p style="margin: 5px 0 0 5px">Total Debits: <b>Ksh. <script>document.writeln(('.($dr).').formatMoney(2, \'.\', \',\'));</script></b></p>';
					if ($diff >= 0) {
						echo '<p style="margin: 5px 0 0 5px">Variance: <b>Ksh. <script>document.writeln(('.($diff).').formatMoney(2, \'.\', \',\'));</script></b></p>';
					}else{
						echo '<p style="margin: 5px 0 0 5px">Variance: <b>(Ksh. <script>document.writeln(('.($diff * -1).').formatMoney(2, \'.\', \',\'));</script>)</b></p>';
					}
					echo '</div>';					
		}

		public static function BalanceSheet()
		{
			$collection = FinancialStatements::GetTrialBalance($_GET['day']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>BALANCE SHEET</h4>';
			if ($_GET['day'] != '' && $_GET['day'] ) {
				echo '<h5 style="margin-top:-10px">As at: '.$_GET['day'].'</h5>';
			}else{
				$_GET['day'] = date('d/m/Y');
				echo '<h5 style="margin-top:-10px">As at: '.date('d/m/Y').'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>Category</td>
			          <td>KSh.</td>
					  <td>Ksh.</td>
			        </tr>
			      </thead>
			      <tbody>';

			$fassets = 0.00;$cassets = 0.00;$cliab = 0.00;$ltliab = 0.00;$capital = 0.00;$profit=0.00;
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Fixed Assets</td></tr>';
			foreach ($collection as $model) {
			    if ($model->type == 'Asset' && $model->group == 'Fixed Asset') {
			    	echo '<tr><td style="text-align:left;">'.$model->name.'</td><td></td><td><script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			    	$fassets += $model->amount;
			    }

			    if ($model->type == 'Revenue') {
			    	$profit += $model->amount;
			    }elseif ($model->type == 'Expense') {
			    	$profit -= $model->amount;
			    }
			}
			echo '<tr><td></td><td></td><td style="font-weight:bold;border-top:2px solid #333"><script>document.writeln(('.$fassets.').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			

			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Current Assets</td></tr>';
			foreach ($collection as $model) {
			    if ($model->type == 'Asset' && $model->group == 'Current Asset') {
			    	echo '<tr><td style="text-align:left;">'.$model->name.'</td><td><script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script></td><td></td></tr>';
			    	$cassets += $model->amount;
			    }
			}
			echo '<tr><td></td><td style="font-weight:bold;border-top:2px solid #333"><script>document.writeln(('.$cassets.').formatMoney(2, \'.\', \',\'));</script></td><td></td></tr>';
			
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Less Current Liabilities</td></tr>';
			foreach ($collection as $model) {
			    if ($model->type == 'Liability' && $model->group == 'Current Liability') {
			    	echo '<tr><td style="text-align:left;">'.$model->name.'</td><td>(<script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script>)</td><td></td></tr>';
			    	$cliab += $model->amount;
			    }
			}
			echo '<tr><td></td><td style="font-weight:bold;border-top:2px solid #333">(<script>document.writeln(('.$cliab.').formatMoney(2, \'.\', \',\'));</script>)</td><td></td></tr>';
			
			$cbal = $cassets - $cliab;
			if ($cbal < 0) {
				echo '<tr><td style="font-weight:bold;text-align:left;text-transform:uppercase;font-style:italic">Net current assets (working capital)</td><td></td><td style="font-weight:bold;">(<script>document.writeln(('.($cbal).').formatMoney(2, \'.\', \',\'));</script>)</td></tr>';
			}else{
				echo '<tr><td style="font-weight:bold;text-align:left;text-transform:uppercase;font-style:italic">Net current assets (working capital)</td><td></td><td style="font-weight:bold;"><script>document.writeln(('.($cbal).').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			}
			
			echo '<tr><td></td><td></td><td style="font-weight:bold;border-top:2px solid #333"><script>document.writeln(('.($cbal + $fassets).').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Less Long Term Liabilities</td></tr>';
			foreach ($collection as $model) {
			    if ($model->type == 'Liability' && $model->group == 'Long Term Liability') {
			    	echo '<tr><td style="text-align:left;">'.$model->name.'</td><td></td><td>(<script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script>)</td></tr>';
			    	$ltliab += $model->amount;
			    }
			}
			
			echo '<tr><td></td><td></td><td style="font-weight:bold;border:5px solid #51b7a3">KSh. <script>document.writeln(('.($cbal + $fassets - $ltliab).').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;"></td></tr>';

			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Equity</td></tr>';
			foreach ($collection as $model) {
			    if ($model->type == 'Equity' && $model->name != 'Drawings') {
			    	echo '<tr><td style="text-align:left;">'.$model->name.'</td><td></td><td><script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			    	$capital += $model->amount;
			    }elseif ($model->name == 'Drawings') {
			    	$drawings = $model;
			    }
			}
			if ($profit < 0) {
				echo '<tr><td style="text-align:left;text-transform:uppercase;font-style:italic">Less Net profit for the period ending - '.$_GET['day'].'</td><td></td><td>(<script>document.writeln(('.$profit.').formatMoney(2, \'.\', \',\'));</script>)</td></tr>';
			}else{
				echo '<tr><td style="text-align:left;text-transform:uppercase;font-style:italic">Add Net profit for the period ending - '.$_GET['day'].'</td><td></td><td><script>document.writeln(('.$profit.').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			}
			
			$xbal = $capital + $profit;
			echo '<tr><td></td><td></td><td style="font-weight:bold;border-top:2px solid #333"><script>document.writeln(('.$xbal.').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			
			echo '<tr><td style="text-align:left;">Less Drawings</td><td></td><td>(<script>document.writeln(('.$drawings->amount.').formatMoney(2, \'.\', \',\'));</script>)</td></tr>';
			
			echo '<tr><td></td><td></td><td style="font-weight:bold;border:5px solid #51b7a3">KSh. <script>document.writeln(('.($xbal - $drawings->amount).').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			     
			echo '</tbody>
			    </table>';					
		}

		public static function CashFlows()
		{
			$collection = FinancialStatements::GetCashFlows($_GET['day'], $_GET['period']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>STATEMENT OF CASH FLOWS</h4>';
			if ($_GET['day'] != '' && $_GET['day'] ) {
				echo '<h5 style="margin-top:-10px">As at: '.$_GET['day'].'</h5>';
			}elseif ($_GET['period'] != '' && $_GET['period'] ) {
				echo '<h5 style="margin-top:-10px">For the period: '.$_GET['period'].'</h5>';
			}else{
				$_GET['day'] = date('d/m/Y');
				echo '<h5 style="margin-top:-10px">As at: '.date('d/m/Y').'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>Category</td>
			          <td>KSh.</td>
					  <td>Ksh.</td>
			        </tr>
			      </thead>
			      <tbody>';

			$profit = 0.00;$operations = 0.00;$investing = 0.00;$financing = 0.00;
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Business Operations</td></tr>';
			
			foreach ($collection as $model) {
			    if ($model->type == 'Revenue') {
			    	$profit += $model->amount;
			    }elseif ($model->type == 'Expense') {
			    	$profit -= $model->amount;
			    }
			}

			$operations += $profit;

			echo '<tr><td style="text-align:left;">Net Profit/(Loss)</td><td>';self::signate($profit);echo '</td><td></td></tr>';

			foreach ($collection as $model) {
			    if ($model->type == 'Liability' && $model->group == 'Current Liability') {
			    	if ($model->amount < 0) {
			    		echo '<tr><td style="text-align:left;">Decrease in '.$model->name.'</td><td>';self::signate(($model->amount));echo '</td><td></td></tr>';
			    	}else{
			    		echo '<tr><td style="text-align:left;">Increase in '.$model->name.'</td><td>';self::signate(($model->amount));echo '</td><td></td></tr>';			    	}
			    	$operations += $model->amount;
			    }
			}

			foreach ($collection as $model) {
			    if ($model->type == 'Asset' && $model->group == 'Current Asset') {
			    	if ($model->amount < 0) {
			    		echo '<tr><td style="text-align:left;">Decrease in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}else{
			    		echo '<tr><td style="text-align:left;">Increase in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}
			    	$operations -= $model->amount;
			    }
			}

			echo '<tr><td style="font-weight:bold;text-align:left;font-style:italic">Business Operations Inflow/(Outflow)</td><td></td><td style="font-weight:bold;border-top:2px solid #333;border-bottom:2px solid #333">';self::signate($operations).'</td></tr>';
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase"></td></tr>';
			
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Investing Activities</td></tr>';

			foreach ($collection as $model) {
			    if ($model->type == 'Asset' && $model->group == 'Fixed Asset') {
			    	if ($model->amount < 0) {
			    		echo '<tr><td style="text-align:left;">Decrease in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}else{
			    		echo '<tr><td style="text-align:left;">Increase in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}
			    	$investing -= $model->amount;			    	
			    }
			}

			echo '<tr><td style="font-weight:bold;text-align:left;font-style:italic">Investing Activities Inflow/(Outflow)</td><td></td><td style="font-weight:bold;border-top:2px solid #333;border-bottom:2px solid #333">';self::signate($investing).'</td></tr>';
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase"></td></tr>';
			
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Financing Activities</td></tr>';

			foreach ($collection as $model) {
			    if ($model->type == 'Liability' && $model->group == 'Long Term Liability') {
			    	if ($model->amount < 0) {
			    		echo '<tr><td style="text-align:left;">Decrease in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}else{
			    		echo '<tr><td style="text-align:left;">Increase in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}
			    	$financing -= $model->amount;
			    }
			}

			foreach ($collection as $model) {
			    if ($model->type == 'Equity' && $model->name != 'Drawings') {
			    	if ($model->amount < 0) {
			    		echo '<tr><td style="text-align:left;">Decrease in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}else{
			    		echo '<tr><td style="text-align:left;">Increase in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}
			    	$financing -= $model->amount;
			    	
			    }elseif ($model->name == 'Drawings') {
			    	$drawings = $model;
			    }
			}

			echo '<tr><td style="text-align:left;">'.$drawings->name.'</td><td>';self::signate(($drawings->amount));echo '</td><td></td></tr>';
			$financing += $model->amount;
			echo '<tr><td style="font-weight:bold;text-align:left;font-style:italic">Financing Activities Inflow/(Outflow)</td><td></td><td style="font-weight:bold;border-top:2px solid #333;border-bottom:2px solid #333">';self::signate(($financing*-1));echo '</td></tr>';
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase"></td></tr>';
			$inflow = $operations + $investing - $financing;
			if ($inflow < 0) {
				echo '<tr><td style="font-weight:bold;text-transform:uppercase">Net Cash Inflow/(Outflow)</td><td></td><td style="font-weight:bold;border:5px solid #51b7a3">(KSh. <script>document.writeln(('.($inflow).').formatMoney(2, \'.\', \',\'));</script>)</td></tr>';
			} else {
				echo '<tr><td style="font-weight:bold;text-transform:uppercase">Net Cash Inflow/(Outflow)</td><td></td><td style="font-weight:bold;border:5px solid #51b7a3">KSh. <script>document.writeln(('.($inflow).').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			}
			 
			echo '</tbody>
			    </table>';					
		}

		public static function TransactionsReport()
		{
			$statement = FinancialStatements::TransactionStatement('', $_GET['period'], $_GET['all']);

			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>TRANSACTIONS STATEMENT</h4>';
			if ($_GET['period'] != '' && $_GET['period'] ) {
				echo '<h5 style="margin-top:-10px">Period: '.$_GET['period'].'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>DATE</td>
			          <td>LEDGER</td>
			          <td>DR</td>
			          <td>CR</td>
			          <td>DESCRIPTION</td>
					  <td>LEDGER BALANCE</td>
			        </tr>
			      </thead>
			      <tbody>';

			$cr = 0.00; $dr = 0.00;

			foreach ($statement as $item) {
			    echo '<tr>
			      <td style="width:90px">'.$item['when_booked'].'</td>
			      <td style="width: 100px">'.$item['ledger_name'].'</td>';

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

		public static function LedgerStatement()
		{
			$statement = FinancialStatements::LedgerStatement($_GET['sid'], $_GET['period'], $_GET['all']);
            $ledger = Account::GetLedger($_GET['sid']);
            echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4 style="text-transform:uppercase">'.$ledger->ledgerName.' LEDGER STATEMENT</h4>';
			if ($_GET['period'] != '' && $_GET['period'] ) {
				echo '<h5 style="margin-top:-10px">Period: '.$_GET['period'].'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>DATE</td>
			          <td>TX ID</td>
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
			      <td style="width: 100px">'.$item['transaction_id'].'</td>';

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

		public static function CashBook()
		{
			$statement = FinancialStatements::LedgerStatement($_GET['sid'], $_GET['period'], $_GET['all']);
            $ledger = Account::GetLedger($_GET['sid']);
            echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4 style="text-transform:uppercase">'.$ledger->ledgerName.' LEDGER STATEMENT</h4>';
			if ($_GET['period'] != '' && $_GET['period'] ) {
				echo '<h5 style="margin-top:-10px">Period: '.$_GET['period'].'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>DATE</td>
			          <td>TX ID</td>
			          <td>LEDGER</td>
			          <td>DR - DISC</td>
			          <td>DR - CASH</td>
			          <td>DR - BANK</td>
			          <td>CR - DISC</td>
			          <td>CR - CASH</td>
			          <td>CR - BANK</td>
			        </tr>
			      </thead>
			      <tbody>';

			$cr = 0.00; $dr = 0.00;

			foreach ($statement as $item) {
			    echo '<tr>
			      <td style="width:90px">'.$item['when_booked'].'</td>
			      <td style="width: 100px">'.$item['ledger_name'].'</td>';

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
	}

	class Body
	{
		private static function signate($amount)
		{
			if ($amount < 0) {
				echo '(<script>document.writeln(('.(-1*$amount).').formatMoney(2, \'.\', \',\'));</script>)';
			}else{
				echo '<script>document.writeln(('.$amount.').formatMoney(2, \'.\', \',\'));</script>';
			}			
		}

		public static function RepairTXBalanceErrors()
		{
			$collection = Auditor::ResetTransactionBalances();
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>TRANSACTION RERUN REPORT</h4>';

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>LEDGER ID</td>
			          <td>LEDGER</td>
			          <td>TYPE</td>
			          <td>BALANCE</td>
			        </tr>
			      </thead>
			      <tbody>';

			$assets = 0.00;
			$liability = 0.00;
			$capital = 0.00;
			$profit = 0.00;

			foreach ($collection as $model) {
				
				switch ($model->type) {
					case 'Asset':
						$assets += $model->amount;
						echo '<tr><td>'.$model->id.'</td><td>'.$model->name.'</td><td>'.$model->type.'</td><td>'.$model->amount.'</td></tr>';
						break;

					case 'Expense':
						$profit -= $model->amount;
						echo '<tr><td>'.$model->id.'</td><td>'.$model->name.'</td><td>'.$model->type.'</td><td>'.$model->amount.'</td></tr>';
						break;

					case 'Liability':
						$liability += $model->amount;
						echo '<tr><td>'.$model->id.'</td><td>'.$model->name.'</td><td>'.$model->type.'</td><td>'.$model->amount.'</td></tr>';
						break;

					case 'Revenue':
						$profit += $model->amount;
						echo '<tr><td>'.$model->id.'</td><td>'.$model->name.'</td><td>'.$model->type.'</td><td>'.$model->amount.'</td></tr>';
						break;

					case 'Equity':
						$capital += $model->amount;
						echo '<tr><td>'.$model->id.'</td><td>'.$model->name.'</td><td>'.$model->type.'</td><td>'.$model->amount.'</td></tr>';
						break;
					
					default:
						break;
				}
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
				    <p style="margin: 5px 0 0 5px">Total Assets: <b>Ksh. <script>document.writeln(('.($assets).').formatMoney(2, \'.\', \',\'));</script></b></p>
					<p style="margin: 5px 0 0 5px">Total Liability: <b>Ksh. <script>document.writeln(('.($liability).').formatMoney(2, \'.\', \',\'));</script></b></p>
					<p style="margin: 5px 0 0 5px">Total Capital: <b>Ksh. <script>document.writeln(('.($capital).').formatMoney(2, \'.\', \',\'));</script></b></p>
					<p style="margin: 5px 0 0 5px">Total Profit: <b>Ksh. <script>document.writeln(('.($profit).').formatMoney(2, \'.\', \',\'));</script></b></p>
				</div>';					
		}

		public static function TXAudit()
		{
			$collection = Auditor::AuditTransactions();
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>AUDIT STATEMENT</h4>';

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>TRANSACTION</td>
			          <td>ENTRIES</td>
			          <td>AMOUNT</td>
					  <td>DEFICIT</td>
			        </tr>
			      </thead>
			      <tbody>';

			$cr = 0.00;
			$dr = 0.00;

			foreach ($collection as $model) {
				echo '<tr><td>'.$model['id'].'</td><td>'.$model['entr'].'</td><td>'.$model['amount'].'</td><td>'.$model['defic'].'</td></tr>';
			}

			$diff = $cr - $dr;
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
				    <p style="margin: 5px 0 0 5px">Total Credits: <b>Ksh. <script>document.writeln(('.($cr).').formatMoney(2, \'.\', \',\'));</script></b></p>
					<p style="margin: 5px 0 0 5px">Total Debits: <b>Ksh. <script>document.writeln(('.($dr).').formatMoney(2, \'.\', \',\'));</script></b></p>';
					if ($diff >= 0) {
						echo '<p style="margin: 5px 0 0 5px">Net Profit/(Loss): <b>Ksh. <script>document.writeln(('.($diff).').formatMoney(2, \'.\', \',\'));</script></b></p>';
					}else{
						echo '<p style="margin: 5px 0 0 5px">Net Profit/(Loss): <b>(Ksh. <script>document.writeln(('.($diff * -1).').formatMoney(2, \'.\', \',\'));</script>)</b></p>';
					}
					echo '</div>';					
		}

		public static function PLStatement()
		{
			$collection = FinancialStatements::GetPLStatement($_GET['day'], $_GET['period']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>PROFIT AND LOSS STATEMENT</h4>';

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>LEDGER</td>
			          <td>DEBIT</td>
					  <td>CREDIT</td>
			        </tr>
			      </thead>
			      <tbody>';

			$cr = 0.00;
			$dr = 0.00;

			foreach ($collection as $model) {
			    echo '<tr>
			      <td>'.$model->name.'</td>';
			    if ($model->type == 'Expense') {
			    	echo '<td><script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script></td></td><td></tr>';
			    	$dr += $model->amount;
			    }else{
			    	echo '</td><td><td><script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			    	$cr += $model->amount;
			    }
			      //<td style="text-align:right;padding-right:7px"><script>document.writeln(('.$model->balance->amount.').formatMoney(2, \'.\', \',\'));</script></td>';
			    
			}

			$diff = $cr - $dr;
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
				    <p style="margin: 5px 0 0 5px">Total Credits: <b>Ksh. <script>document.writeln(('.($cr).').formatMoney(2, \'.\', \',\'));</script></b></p>
					<p style="margin: 5px 0 0 5px">Total Debits: <b>Ksh. <script>document.writeln(('.($dr).').formatMoney(2, \'.\', \',\'));</script></b></p>';
					if ($diff >= 0) {
						echo '<p style="margin: 5px 0 0 5px">Net Profit/(Loss): <b>Ksh. <script>document.writeln(('.($diff).').formatMoney(2, \'.\', \',\'));</script></b></p>';
					}else{
						echo '<p style="margin: 5px 0 0 5px">Net Profit/(Loss): <b>(Ksh. <script>document.writeln(('.($diff * -1).').formatMoney(2, \'.\', \',\'));</script>)</b></p>';
					}
					echo '</div>';					
		}

		public static function TrialBalance()
		{
			$collection = FinancialStatements::GetTrialBalance($_GET['day']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>TRIAL BALANCE</h4>';
			if ($_GET['day'] != '' && $_GET['day'] ) {
				echo '<h5 style="margin-top:-10px">As at: '.$_GET['day'].'</h5>';
			}else{
				echo '<h5 style="margin-top:-10px">As at: '.date('d/m/Y').'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>LEDGER</td>
			          <td>DEBIT</td>
					  <td>CREDIT</td>
			        </tr>
			      </thead>
			      <tbody>';

			$cr = 0.00;
			$dr = 0.00;

			foreach ($collection as $model) {
			    echo '<tr>
			      <td>'.$model->name.'</td>';
			    if ($model->type == 'Expense' || $model->type == 'Asset') {
			    	echo '<td><script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script></td><td></td></tr>';
			    	$dr += $model->amount;
			    }else{
			    	echo '<td></td><td><script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			    	$cr += $model->amount;
			    }
			      //<td style="text-align:right;padding-right:7px"><script>document.writeln(('.$model->balance->amount.').formatMoney(2, \'.\', \',\'));</script></td>';
			    
			}

			$diff = $cr - $dr;
			        
			echo '</tbody>
					<tfoot>
						<tr>
				          <td>TOTAL</td>
				          <td><b>Ksh. <script>document.writeln(('.($dr).').formatMoney(2, \'.\', \',\'));</script></td>
						  <td><b>Ksh. <script>document.writeln(('.($cr).').formatMoney(2, \'.\', \',\'));</script></td>
				        </tr>
					</tfoot>
			    </table>
			    <div class="logo">
				    <p style="margin: 5px 0 0 5px">Total Credits: <b>Ksh. <script>document.writeln(('.($cr).').formatMoney(2, \'.\', \',\'));</script></b></p>
					<p style="margin: 5px 0 0 5px">Total Debits: <b>Ksh. <script>document.writeln(('.($dr).').formatMoney(2, \'.\', \',\'));</script></b></p>';
					if ($diff >= 0) {
						echo '<p style="margin: 5px 0 0 5px">Variance: <b>Ksh. <script>document.writeln(('.($diff).').formatMoney(2, \'.\', \',\'));</script></b></p>';
					}else{
						echo '<p style="margin: 5px 0 0 5px">Variance: <b>(Ksh. <script>document.writeln(('.($diff * -1).').formatMoney(2, \'.\', \',\'));</script>)</b></p>';
					}
					echo '</div>';					
		}

		public static function BalanceSheet()
		{
			$collection = FinancialStatements::GetTrialBalance($_GET['day']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>BALANCE SHEET</h4>';
			if ($_GET['day'] != '' && $_GET['day'] ) {
				echo '<h5 style="margin-top:-10px">As at: '.$_GET['day'].'</h5>';
			}else{
				$_GET['day'] = date('d/m/Y');
				echo '<h5 style="margin-top:-10px">As at: '.date('d/m/Y').'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>Category</td>
			          <td>KSh.</td>
					  <td>Ksh.</td>
			        </tr>
			      </thead>
			      <tbody>';

			$fassets = 0.00;$cassets = 0.00;$cliab = 0.00;$ltliab = 0.00;$capital = 0.00;$profit=0.00;
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Fixed Assets</td></tr>';
			foreach ($collection as $model) {
			    if ($model->type == 'Asset' && $model->group == 'Fixed Asset') {
			    	echo '<tr><td style="text-align:left;">'.$model->name.'</td><td></td><td><script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			    	$fassets += $model->amount;
			    }

			    if ($model->type == 'Revenue') {
			    	$profit += $model->amount;
			    }elseif ($model->type == 'Expense') {
			    	$profit -= $model->amount;
			    }
			}
			echo '<tr><td></td><td></td><td style="font-weight:bold;border-top:2px solid #333"><script>document.writeln(('.$fassets.').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			

			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Current Assets</td></tr>';
			foreach ($collection as $model) {
			    if ($model->type == 'Asset' && $model->group == 'Current Asset') {
			    	echo '<tr><td style="text-align:left;">'.$model->name.'</td><td><script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script></td><td></td></tr>';
			    	$cassets += $model->amount;
			    }
			}
			echo '<tr><td></td><td style="font-weight:bold;border-top:2px solid #333"><script>document.writeln(('.$cassets.').formatMoney(2, \'.\', \',\'));</script></td><td></td></tr>';
			
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Less Current Liabilities</td></tr>';
			foreach ($collection as $model) {
			    if ($model->type == 'Liability' && $model->group == 'Current Liability') {
			    	echo '<tr><td style="text-align:left;">'.$model->name.'</td><td>(<script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script>)</td><td></td></tr>';
			    	$cliab += $model->amount;
			    }
			}
			echo '<tr><td></td><td style="font-weight:bold;border-top:2px solid #333">(<script>document.writeln(('.$cliab.').formatMoney(2, \'.\', \',\'));</script>)</td><td></td></tr>';
			
			$cbal = $cassets - $cliab;
			if ($cbal < 0) {
				echo '<tr><td style="font-weight:bold;text-align:left;text-transform:uppercase;font-style:italic">Net current assets (working capital)</td><td></td><td style="font-weight:bold;">(<script>document.writeln(('.($cbal).').formatMoney(2, \'.\', \',\'));</script>)</td></tr>';
			}else{
				echo '<tr><td style="font-weight:bold;text-align:left;text-transform:uppercase;font-style:italic">Net current assets (working capital)</td><td></td><td style="font-weight:bold;"><script>document.writeln(('.($cbal).').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			}
			
			echo '<tr><td></td><td></td><td style="font-weight:bold;border-top:2px solid #333"><script>document.writeln(('.($cbal + $fassets).').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Less Long Term Liabilities</td></tr>';
			foreach ($collection as $model) {
			    if ($model->type == 'Liability' && $model->group == 'Long Term Liability') {
			    	echo '<tr><td style="text-align:left;">'.$model->name.'</td><td></td><td>(<script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script>)</td></tr>';
			    	$ltliab += $model->amount;
			    }
			}
			
			echo '<tr><td></td><td></td><td style="font-weight:bold;border:5px solid #51b7a3">KSh. <script>document.writeln(('.($cbal + $fassets - $ltliab).').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;"></td></tr>';

			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Equity</td></tr>';
			foreach ($collection as $model) {
			    if ($model->type == 'Equity' && $model->name != 'Drawings') {
			    	echo '<tr><td style="text-align:left;">'.$model->name.'</td><td></td><td><script>document.writeln(('.$model->amount.').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			    	$capital += $model->amount;
			    }elseif ($model->name == 'Drawings') {
			    	$drawings = $model;
			    }
			}
			if ($profit < 0) {
				echo '<tr><td style="text-align:left;text-transform:uppercase;font-style:italic">Less Net profit for the period ending - '.$_GET['day'].'</td><td></td><td>(<script>document.writeln(('.$profit.').formatMoney(2, \'.\', \',\'));</script>)</td></tr>';
			}else{
				echo '<tr><td style="text-align:left;text-transform:uppercase;font-style:italic">Add Net profit for the period ending - '.$_GET['day'].'</td><td></td><td><script>document.writeln(('.$profit.').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			}
			
			$xbal = $capital + $profit;
			echo '<tr><td></td><td></td><td style="font-weight:bold;border-top:2px solid #333"><script>document.writeln(('.$xbal.').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			
			echo '<tr><td style="text-align:left;">Less Drawings</td><td></td><td>(<script>document.writeln(('.$drawings->amount.').formatMoney(2, \'.\', \',\'));</script>)</td></tr>';
			
			echo '<tr><td></td><td></td><td style="font-weight:bold;border:5px solid #51b7a3">KSh. <script>document.writeln(('.($xbal - $drawings->amount).').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			     
			echo '</tbody>
			    </table>';					
		}

		public static function CashFlows()
		{
			$collection = FinancialStatements::GetCashFlows($_GET['day'], $_GET['period']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>STATEMENT OF CASH FLOWS</h4>';
			if ($_GET['day'] != '' && $_GET['day'] ) {
				echo '<h5 style="margin-top:-10px">As at: '.$_GET['day'].'</h5>';
			}elseif ($_GET['period'] != '' && $_GET['period'] ) {
				echo '<h5 style="margin-top:-10px">For the period: '.$_GET['period'].'</h5>';
			}else{
				$_GET['day'] = date('d/m/Y');
				echo '<h5 style="margin-top:-10px">As at: '.date('d/m/Y').'</h5>';
			}

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>Category</td>
			          <td>KSh.</td>
					  <td>Ksh.</td>
			        </tr>
			      </thead>
			      <tbody>';

			$profit = 0.00;$operations = 0.00;$investing = 0.00;$financing = 0.00;
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Business Operations</td></tr>';
			
			foreach ($collection as $model) {
			    if ($model->type == 'Revenue') {
			    	$profit += $model->amount;
			    }elseif ($model->type == 'Expense') {
			    	$profit -= $model->amount;
			    }
			}

			$operations += $profit;

			echo '<tr><td style="text-align:left;">Net Profit/(Loss)</td><td>';self::signate($profit);echo '</td><td></td></tr>';

			foreach ($collection as $model) {
			    if ($model->type == 'Liability' && $model->group == 'Current Liability') {
			    	if ($model->amount < 0) {
			    		echo '<tr><td style="text-align:left;">Decrease in '.$model->name.'</td><td>';self::signate(($model->amount));echo '</td><td></td></tr>';
			    	}else{
			    		echo '<tr><td style="text-align:left;">Increase in '.$model->name.'</td><td>';self::signate(($model->amount));echo '</td><td></td></tr>';			    	}
			    	$operations += $model->amount;
			    }
			}

			foreach ($collection as $model) {
			    if ($model->type == 'Asset' && $model->group == 'Current Asset') {
			    	if ($model->amount < 0) {
			    		echo '<tr><td style="text-align:left;">Decrease in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}else{
			    		echo '<tr><td style="text-align:left;">Increase in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}
			    	$operations -= $model->amount;
			    }
			}

			echo '<tr><td style="font-weight:bold;text-align:left;font-style:italic">Business Operations Inflow/(Outflow)</td><td></td><td style="font-weight:bold;border-top:2px solid #333;border-bottom:2px solid #333">';self::signate($operations).'</td></tr>';
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase"></td></tr>';
			
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Investing Activities</td></tr>';

			foreach ($collection as $model) {
			    if ($model->type == 'Asset' && $model->group == 'Fixed Asset') {
			    	if ($model->amount < 0) {
			    		echo '<tr><td style="text-align:left;">Decrease in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}else{
			    		echo '<tr><td style="text-align:left;">Increase in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}
			    	$investing -= $model->amount;			    	
			    }
			}

			echo '<tr><td style="font-weight:bold;text-align:left;font-style:italic">Investing Activities Inflow/(Outflow)</td><td></td><td style="font-weight:bold;border-top:2px solid #333;border-bottom:2px solid #333">';self::signate($investing).'</td></tr>';
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase"></td></tr>';
			
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase">Financing Activities</td></tr>';

			foreach ($collection as $model) {
			    if ($model->type == 'Liability' && $model->group == 'Long Term Liability') {
			    	if ($model->amount < 0) {
			    		echo '<tr><td style="text-align:left;">Decrease in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}else{
			    		echo '<tr><td style="text-align:left;">Increase in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}
			    	$financing -= $model->amount;
			    }
			}

			foreach ($collection as $model) {
			    if ($model->type == 'Equity' && $model->name != 'Drawings') {
			    	if ($model->amount < 0) {
			    		echo '<tr><td style="text-align:left;">Decrease in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}else{
			    		echo '<tr><td style="text-align:left;">Increase in '.$model->name.'</td><td>';self::signate(($model->amount * -1));echo '</td><td></td></tr>';
			    	}
			    	$financing -= $model->amount;
			    	
			    }elseif ($model->name == 'Drawings') {
			    	$drawings = $model;
			    }
			}

			echo '<tr><td style="text-align:left;">'.$drawings->name.'</td><td>';self::signate(($drawings->amount));echo '</td><td></td></tr>';
			$financing += $model->amount;
			echo '<tr><td style="font-weight:bold;text-align:left;font-style:italic">Financing Activities Inflow/(Outflow)</td><td></td><td style="font-weight:bold;border-top:2px solid #333;border-bottom:2px solid #333">';self::signate(($financing*-1));echo '</td></tr>';
			echo '<tr><td colspan="3" style="font-weight:bold;text-align:left;text-transform:uppercase"></td></tr>';
			$inflow = $operations + $investing - $financing;
			if ($inflow < 0) {
				echo '<tr><td style="font-weight:bold;text-transform:uppercase">Net Cash Inflow/(Outflow)</td><td></td><td style="font-weight:bold;border:5px solid #51b7a3">(KSh. <script>document.writeln(('.($inflow).').formatMoney(2, \'.\', \',\'));</script>)</td></tr>';
			} else {
				echo '<tr><td style="font-weight:bold;text-transform:uppercase">Net Cash Inflow/(Outflow)</td><td></td><td style="font-weight:bold;border:5px solid #51b7a3">KSh. <script>document.writeln(('.($inflow).').formatMoney(2, \'.\', \',\'));</script></td></tr>';
			}
			 
			echo '</tbody>
			    </table>';					
		}

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

			    echo '<td style="max-width: 220px;">'.$item['descr'].'</td>
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

			    $sql = 'SELECT * FROM purchase_orders WHERE id = '.$item['id'];
			      $res = DatabaseHandler::GetRow($sql);

			      $vc = PurchaseOrderVoucher::initialize($res);
					
					echo '<td>'.$vc->description.'</td>';

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

			    echo '<td style="max-width: 220px;">'.$item['descr'].'</td>
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
			      $sql = 'SELECT * FROM purchase_orders WHERE id = '.$item->id;
			      $res = DatabaseHandler::GetRow($sql);

			      $vc = PurchaseOrderVoucher::initialize($res);
					
					echo '<td>'.$vc->description.'</td>';

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
			          <td>GRN NO</td>
			          <td>INV NO</td>
			          <td>COMPANY</td>
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
			      <td>'.$item['invno'].'</td>
			      <td>'.$party->name.'</td>
				<td class="text-right" style="padding: 0 5px;"><script>document.writeln(('.$item['total'].').formatMoney(2, \'.\', \',\'));</script></td>
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

	class HRReports
	{
		private static function signate($amount)
		{
			if ($amount < 0) {
				echo '(<script>document.writeln(('.(-1*$amount).').formatMoney(2, \'.\', \',\'));</script>)';
			}else{
				echo '<script>document.writeln(('.$amount.').formatMoney(2, \'.\', \',\'));</script>';
			}			
		}

		public static function EmployeeRegister()
		{
			$collection = Employee::GetAllEmployees();
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>ALL EMPLOYEES</h4>';

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>NAME</td>
			          <td>TELEPHONE</td>
					  <td>GENDER</td>
			          <td>DEPARTMENT</td>
			          <td>POSITION</td>
					  <td>SALARY</td>
			        </tr>
			      </thead>
			      <tbody>';

			foreach ($collection as $model) {
			    echo '<tr>
			      <td>'.$model->name.'</td>
			      <td>'.$model->telephone.'</td>
			      <td>'.$model->gender.'</td>
			      <td>'.$model->department.'</td>
			      <td>'.$model->position.'</td>
			      <td><script>document.writeln(('.$model->salary->amount.').formatMoney(2, \'.\', \',\'));</script></td>
			      </tr>';
			}
			        
			echo '</tbody>
			    </table>';
		}

		public static function EmployeeStatement()
		{
			$statement = TransactionVouchers::EmployeeStatement($_GET['sid'], $_GET['period'], $_GET['all']);
			$employee = Employee::GetEmployee($_GET['sid']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>'.$employee->name.'</h4>';
			if ($_GET['period'] != '' && $_GET['period'] ) {
				echo '<h5 style="margin-top:-10px">Period: '.$_GET['period'].'</h5>';
			}			  
			
			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>DATE</td>
			          <td>TYPE</td>
			          <td>Month</td>
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
			      <td style="width:90px">'.$item['datetime'].'</td>
			      <td style="width: 100px">'.$item['type'].'</td>
			      <td style="width: 100px">'.$item['month'].'</td>';

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
			      <td class="text-right" style="padding: 0 5px;"><script>document.writeln(('.($cr - $dr).').formatMoney(2, \'.\', \',\'));</script></td>
			    </tr>';
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
			    <p style="margin: 5px 0 0 5px">Total Credits: <b>Ksh. <script>document.writeln(('.$cr.').formatMoney(2, \'.\', \',\'));</script></b></p>				    
			    <p style="margin: 5px 0 0 5px">Total Debits: <b>Ksh. <script>document.writeln(('.$dr.').formatMoney(2, \'.\', \',\'));</script></b></p>
				<p style="margin: 5px 0 0 5px">Balance: <b>Ksh. <script>document.writeln(('.($cr - $dr).').formatMoney(2, \'.\', \',\'));</script></b></p>
				</div>';
		}

		public static function EmployeeAllowances()
		{
			$statement = TransactionVouchers::PayrollCategoryReport($_GET['sid'], $_GET['month'], 'Allowance');
			$employee = Employee::GetEmployee($_GET['sid']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>ALLOWANCES: '.$employee->name.'</h4>
				  <h5 style="margin-top:-10px">Month: '.$_GET['month'].'</h5>';

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>DATE</td>
			          <td>AMOUNT</td>
			          <td>DESCRIPTION</td>
			        </tr>
			      </thead>
			      <tbody>';

			$tot = 0.00;

			foreach ($statement as $item) {
				$tot += $item['amount'];
			    echo '<tr>
			      <td style="width:90px">'.$item['datetime'].'</td>
			      <td style="width: 100px"><script>document.writeln(('.$item['amount'].').formatMoney(2, \'.\', \',\'));</script></td>
			      <td style="max-width: 220px;">'.$item['description'].'</td>
			    </tr>';
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
			    <p style="margin: 5px 0 0 5px">Total Allowances: <b>Ksh. <script>document.writeln(('.$tot.').formatMoney(2, \'.\', \',\'));</script></b></p>				    
			    </div>';
		}

		public static function EmployeeAdvances()
		{
			$statement = TransactionVouchers::PayrollCategoryReport($_GET['sid'], $_GET['month'], 'Salary Advance');
			$employee = Employee::GetEmployee($_GET['sid']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>SALARY ADVANCES: '.$employee->name.'</h4>
				  <h5 style="margin-top:-10px">Month: '.$_GET['month'].'</h5>';

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>DATE</td>
			          <td>AMOUNT</td>
			          <td>PAY MODE</td>
			          <td>REF No</td>
			          <td>DESCRIPTION</td>
			        </tr>
			      </thead>
			      <tbody>';

			$tot = 0.00;

			foreach ($statement as $item) {
				$tot += $item['amount'];
			    echo '<tr>
			      <td style="width:90px">'.$item['datetime'].'</td>
			      <td style="width: 100px"><script>document.writeln(('.$item['amount'].').formatMoney(2, \'.\', \',\'));</script></td>
			      <td style="width: 100px">'.$item['mode'].'</td>
			      <td style="width: 100px">'.$item['voucher_no'].'</td>
			      <td style="max-width: 220px;">'.$item['description'].'</td>
			    </tr>';
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
			    <p style="margin: 5px 0 0 5px">Total Advances: <b>Ksh. <script>document.writeln(('.$tot.').formatMoney(2, \'.\', \',\'));</script></b></p>				    
			    </div>';
		}

		public static function EmployeeOvertime()
		{
			$statement = TransactionVouchers::PayrollCategoryReport($_GET['sid'], $_GET['month'], 'Overtime');
			$employee = Employee::GetEmployee($_GET['sid']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>OVERTIME: '.$employee->name.'</h4>
				  <h5 style="margin-top:-10px">Month: '.$_GET['month'].'</h5>';

			echo '</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>DATE</td>
			          <td>HOURS</td>
			          <td>RATE</td>
			          <td>AMOUNT</td>
			          <td>DESCRIPTION</td>
			        </tr>
			      </thead>
			      <tbody>';

			$tot = 0.00;

			foreach ($statement as $item) {
				$tot += $item['amount'];
			    echo '<tr>
			      <td style="width:90px">'.$item['datetime'].'</td>
			      <td style="width: 100px">'.$item['qty'].'</td>
			      <td style="width: 100px"><script>document.writeln(('.$item['rate'].').formatMoney(2, \'.\', \',\'));</script></td>
			      <td style="width: 100px"><script>document.writeln(('.$item['amount'].').formatMoney(2, \'.\', \',\'));</script></td>
			      <td style="max-width: 220px;">'.$item['description'].'</td>
			    </tr>';
			}
			        
			echo '</tbody>
			    </table>
			    <div class="logo">
			    <p style="margin: 5px 0 0 5px">Total Advances: <b>Ksh. <script>document.writeln(('.$tot.').formatMoney(2, \'.\', \',\'));</script></b></p>				    
			    </div>';
		}

		//Payroll::PreviewPayroll($month)
		public static function PayrollSummary()
		{
			$payroll = Payroll::PreviewPayroll($_GET['month']);
			echo '
				<div class="logo">
				  <h5 style="margin-bottom:-15px;margin-top:0px;font-size:14px;">Date: '.date('d/m/Y').'</h5>
				  <h4>PAYROLL SUMMARY</h4>
				  <h5 style="margin-top:-10px">Month: '.$_GET['month'].'</h5>
				</div>

				<table class="table table-bordered table-striped" style="text-align:center;margin-left:0;margin-right:0;width:760px;font-size:12px;">
			      <thead class="title">
			        <tr>
			          <td>NAME</td>
			          <td>ROLE</td>
			          <td>BASIC SALARY</td>
			          <td>ADDITIONS</td>
			          <td>DEDUCTIONS</td>
			          <td>NET PAY</td>
			        </tr>
			      </thead>
			      <tbody>';

			$totsalary = 0.00; $totadd = 0.00;$totded = 0.00;$totnet = 0.00;

			foreach ($payroll->slips as $slip) {
			    echo '<tr>
			      <td style="width:90px">'.$slip->employee->name.'</td>
			      <td style="width: 100px">'.$slip->employee->position.' [Department: '.$slip->employee->department.']</td>
			      <td style="width: 100px"><script>document.writeln(('.$slip->salary.').formatMoney(2, \'.\', \',\'));</script></td>
				  <td style="width: 100px"><script>document.writeln(('.$slip->t_additions.').formatMoney(2, \'.\', \',\'));</script></td>
				  <td style="width: 100px"><script>document.writeln(('.$slip->t_deductions.').formatMoney(2, \'.\', \',\'));</script></td>
			      <td style="width: 100px"><script>document.writeln(('.$slip->netpay.').formatMoney(2, \'.\', \',\'));</script></td>
			    </tr>';
			    $totsalary += $slip->salary;
			    $totadd += $slip->t_additions;
			    $totded += $slip->t_deductions;
			    $totnet += $slip->netpay;

			}
			        
			echo '</tbody>
				  <tfoot>
			        <tr>
			          <td></td>
			          <td>TOTALS:</td>
			          <td><script>document.writeln(('.$totsalary.').formatMoney(2, \'.\', \',\'));</script></td>
			          <td><script>document.writeln(('.$totadd.').formatMoney(2, \'.\', \',\'));</script></td>
			          <td><script>document.writeln(('.$totded.').formatMoney(2, \'.\', \',\'));</script></td>
			          <td><script>document.writeln(('.$totnet.').formatMoney(2, \'.\', \',\'));</script></td>
			        </tr>
			      </tfoot>
			    </table>

			    <div class="logo">
			    <p style="margin: 5px 0 0 5px">Total Payable for '.$_GET['month'].': <b>Ksh. <script>document.writeln(('.$totnet.').formatMoney(2, \'.\', \',\'));</script></b></p>				    
			    </div>';
		}
	}

	new Reports();
?>