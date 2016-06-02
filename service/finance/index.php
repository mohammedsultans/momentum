<?php
// Manages the Journals list
  	require_once '../../include/config.php';
  	require_once DOMAIN_DIR . 'LSOfficeDomain.php';
  	//require_once DOMAIN_DIR . 'Product.php';
	
	class FinanceApp
	{
		// Constructor reads query string parameter
		public function __construct()
		{
			if(isset($_POST['operation'])){
				$operation = $_POST['operation'];
				if($operation == 'postQuoteInvoice'){
					if(isset($_POST['client']) && isset($_POST['purpose']) && isset($_POST['quotes'])){
						$clientid = $_POST['client'];
						$scope = $_POST['purpose'];
						$discount = $_POST['discount'];
						if (isset($_POST['quotes'])) {
							$quotes = $_POST['quotes'];
						}else{
							$quotes = [];
						}
						$this->postQuoteInvoice($clientid, $scope, $quotes, $discount);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'postGenInvoice'){
					if(isset($_POST['client']) && isset($_POST['scope']) && isset($_POST['items'])){
						$clientid = $_POST['client'];
						$scope = $_POST['scope'];
						$items = $_POST['items'];
						$discount = $_POST['discount'];
						$this->postGenInvoice($clientid, $scope, $items, $discount);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'issueCrNote'){
					if(isset($_POST['client']) && isset($_POST['invoice']) && isset($_POST['descr']) && isset($_POST['items'])){
						$clientid = $_POST['client'];
						$invoice = $_POST['invoice'];
						$items = $_POST['items'];
						$descr = $_POST['descr'];
						$this->issueCrNote($clientid, $invoice, $items, $descr);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'receivePayment'){
					if(isset($_POST['client']) && isset($_POST['mode']) && isset($_POST['amount']) && isset($_POST['category']) && isset($_POST['voucher'])){
						if ($_POST['mode'] != 101) {
							if (FinancialTransaction::VoucherInUse($_POST['voucher'])) {
						      	echo 0;
						      	exit;
						    }
						}
						$clientid = $_POST['client'];
						$account = $_POST['mode'];
						$category = $_POST['category'];
						$amount = $_POST['amount'];
						$descr = $_POST['descr'];
						$voucher =  $_POST['voucher'];
						$this->receivePayment($clientid, $category, $account, $amount, $voucher, $descr);
					}else{
						echo 0;
					}
						
				}elseif($operation == 'createLedger'){
					if(isset($_POST['name']) && isset($_POST['type']) && isset($_POST['group']) && isset($_POST['category'])){
						$name = $_POST['name'];
						$type = $_POST['type'];
						$group = $_POST['group'];
						$category = $_POST['category'];
						$subaccount = $_POST['subaccount'];
						$this->createLedger($name, $type, $group, $category, $subaccount);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'deleteLedger'){
					if(isset($_POST['lid'])){
						$this->deleteLedger($_POST['lid']);
					}else{
						echo 0;
					}
				}elseif($operation == 'postTransaction'){
					if(isset($_POST['entries']) && isset($_POST['descr']) && isset($_POST['amount'])){
						$entries = $_POST['entries'];
						$descr = $_POST['descr'];
						$amount = $_POST['amount'];
						$this->postTransaction($entries, $amount, $descr);
					}else{
						echo 0;
					}				
				}elseif($operation == 'findEntries'){
					if(isset($_POST['param']) && isset($_POST['value'])){
						$param = $_POST['param'];
						$value = $_POST['value'];
						$this->findEntries($param, $value);
					}else{
						echo 0;
					}				
				}elseif($operation == 'reverseTX'){
					if(isset($_POST['txid'])){
						$txid = $_POST['txid'];
						$this->reverseTX($txid);
					}else{
						echo 2;
					}				
				}elseif($operation == 'processClaim'){
					
					if(isset($_POST['account']) && isset($_POST['voucher']) && isset($_POST['items'])){
						if ($_POST['account'] != 101) {
							if (FinancialTransaction::VoucherInUse($_POST['voucher'])) {
						      	echo 0;
						      	exit;
						    }
						}

						$ledgerId = $_POST['account'];
						$voucherId = $_POST['voucher'];
						$items = $_POST['items'];
						$details = "Payment of expense claims. Voucher Id: ".$voucherId.", Project Id: ".$_POST['project'];
						$this->processClaim($ledgerId, $voucherId, $items, $details);
					}else{
						echo 0;
					}				
				}elseif($operation == 'postExpense'){
					if(isset($_POST['context']) && isset($_POST['credit']) && isset($_POST['debit']) && isset($_POST['amount']) && isset($_POST['voucher']) && isset($_POST['descr'])){
						if ($_POST['credit'] != 101) {
							if (FinancialTransaction::VoucherInUse($_POST['voucher'])) {
						      	echo 0;
						      	exit;
						    }
						}

						$party = $_POST['context'];
						if ($party == 'office') {
							$party = 0;
						}else{
							$party == intval($party);
						}
						$credit = $_POST['credit'];
						$debit = $_POST['debit'];
						$amount = $_POST['amount'];
						$voucher = $_POST['voucher'];
						$descr = $_POST['descr'];
						$this->postExpense($party, $credit, $debit, $amount, $voucher, $descr);
					}else{
						echo 0;
					}				
				}elseif($operation == 'injectCapital'){
					if(isset($_POST['credit']) && isset($_POST['debit']) && isset($_POST['amount']) && isset($_POST['descr'])){
						$credit = $_POST['credit'];
						$debit = $_POST['debit'];
						$amount = $_POST['amount'];
						$descr = $_POST['descr'];
						$this->injectCapital($credit, $debit, $amount, $descr);
					}else{
						echo 0;
					}				
				}elseif($operation == 'postC2BBankTx'){
					if(isset($_POST['action']) && isset($_POST['account']) && isset($_POST['voucher']) && isset($_POST['amount']) && isset($_POST['descr'])){
						if (FinancialTransaction::VoucherInUse($_POST['voucher'])) {
						    echo 0;
						    exit;
						}
						$this->postC2BBankTx($_POST['action'], $_POST['account'], $_POST['voucher'], $_POST['amount'], $_POST['descr']);
					}else{
						echo 0;
					}				
				}elseif($operation == 'postB2BBankTx'){
					if(isset($_POST['account1']) && isset($_POST['account2'])  && isset($_POST['voucher']) && isset($_POST['amount']) && isset($_POST['descr'])){
						if (FinancialTransaction::VoucherInUse($_POST['voucher'])) {
						    echo 0;
						    exit;
						}
						$this->postB2BBankTx($_POST['account1'], $_POST['account2'], $_POST['voucher'], $_POST['amount'], $_POST['descr']);
					}else{
						echo 0;
					}				
				}elseif($operation == 'findClientEntries'){
					if(isset($_POST['client']) && isset($_POST['category'])){
						$client = $_POST['client'];
						$category = $_POST['category'];
						$dates = $_POST['dates'];
						$all = $_POST['vall'];
						$this->findClientEntries($client, $category, $dates, $all);
					}else{
						echo 0;
					}				
				}elseif($operation == 'checkauth'){
					$this->check_auth();
				}else{ 
					echo 0;
				}
			}elseif(isset($_GET['banks'])){
				$this->getBanks();
			}elseif(isset($_GET['noncashbanks'])){
				$this->getNonCashBanks();
			}elseif(isset($_GET['allLedgers'])){
				$this->getLedgers();
			}elseif(isset($_GET['purchaseLedgers'])){
				$this->getPurchaseLedgers();
			}elseif(isset($_GET['ledgerName'])){
				$this->getLedgerByName($_GET['ledgerName']);
			}elseif(isset($_GET['ledgerType'])){
				$this->getLedgerType($_GET['ledgerType']);
			}elseif(isset($_GET['invoices']) && isset($_GET['clientid'])){
				$this->getClientInvoices($_GET['clientid']);
			}else{
				echo 0;
			}
		}
		/* Calls business tier method to read Journals list and create
		their links */
		public function getBanks()
		{
			if ($this->validateAdmin()) {
				echo json_encode(Ledger::GetBanks());
			}else{
				echo 0;
			}
		}

		public function getNonCashBanks()
		{
			if ($this->validateAdmin()) {
				echo json_encode(Ledger::GetNonCashBanks());
			}else{
				echo 0;
			}
		}

		public function getLedgers()
		{
			if ($this->validateAdmin()) {
				echo json_encode(Ledger::GetLedgers());
			}else{
				echo 0;
			}
		}

		public function getClientInvoices($cid)
		{
			if ($this->validateAdmin()) {
				echo json_encode(SalesInvoice::GetClientInvoices($cid));
			}else{
				echo 0;
			}
		}

		public function getPurchaseLedgers()
		{
			if ($this->validateAdmin()) {
				echo json_encode(Ledger::GetPurchaseLedgers());
			}else{
				echo 0;
			}
		}

		public function getLedgerByName($name)
		{
			if ($this->validateAdmin()) {
				echo json_encode(Ledger::GetLedgerByName($name));
			}else{
				echo 0;
			}
		}

		public function getLedgerType($type)
		{
			if ($this->validateAdmin()) {
				echo json_encode(Ledger::GetLedgerType($type));
			}else{
				echo 0;
			}
		}

		public function postQuoteInvoice($clientid, $scope, $quotes, $discount)
		{
			$invoice = SalesTX::RaiseQuotationInvoice($clientid, $scope, $quotes, $discount);

			$voucher = $invoice->post();

			if ($voucher) {
				echo json_encode($voucher);
			}else{
				echo 0;
			}
		}

		public function postGenInvoice($clientid, $scope, $items, $discount)
		{
			$invoice = SalesTX::RaiseGeneralInvoice($clientid, $scope, $items, $discount);			

			$voucher = $invoice->post();

			if ($voucher) {
				echo json_encode($voucher);
			}else{
				echo 0;
			}
		}

		public function issueCrNote($clientid, $invoice, $items, $descr)
		{
			$crnote = SalesTX::GenerateCreditNote($clientid, $invoice, $items, $descr);			

			$voucher = $crnote->post();

			if ($voucher) {
				echo json_encode($voucher);
			}else{
				echo 0;
			}
		}

		public function receivePayment($clientid, $category, $account, $amount, $voucher, $descr)
		{
			$receipt = ReceiptTX::ReceivePayment($clientid, $category, $account, $amount, $voucher, $descr);
			$voucher = $receipt->submit();
			if ($voucher) {
				echo json_encode($voucher);
			}else{
				echo 0;
			}
		}

		public function createLedger($name, $type, $group, $category, $subacc)
		{
			if ($this->validateAdmin() && Ledger::CreateLedger($name, $type, $group, $category, $subacc)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function deleteLedger($id)
		{
			if ($this->validateAdmin()) {
				if (Ledger::Delete($id)) {
					echo 1;
				}else{
					echo 0;
				}
			}else{
				echo 0;
			}
		}

		public function postTransaction($entries, $amount, $descr)
		{
			$tx = GeneralTransaction::PostTransaction($entries, $amount, $descr);

			if ($tx->post()) {
				echo 1;
			}else{
				echo 0;
			}
		}		

		public function findEntries($param, $value)
		{
			if ($this->validateAdmin()) {
				echo json_encode(AccountEntry::FindEntries($param, $value));
			}else{
				echo 0;
			}
		}

		public function reverseTX($txid)
		{
			if ($this->validateAdmin()) {
				if (Transaction::Reverse($txid)) {
					echo 1;
				}
			}else{
				echo 0;
			}
		}

		public function processClaim($ledgerId, $voucherId, $items, $details)
		{
			if ($this->validateAdmin()) {
				$adj = ExpenseItem::Adjust($items);

				if ($adj) {
					$vouch = ExpenseVoucher::GetVoucher(intval($voucherId));
					$tx = GeneralTransaction::PostClaim($ledgerId, $vouch->total, $vouch->items, $details);
					$claimslip = $tx->postprojectclaim($vouch);
					
					if ($claimslip) {						
						$vouch->authorize($tx->transactionId);
						echo json_encode($claimslip);
					}else{
						echo 0;
					}
				}else{
					echo 0;
				}
			}else{
				echo 0;
			}
		}

		public function postExpense($party, $credit, $debit, $amount, $voucher, $descr)
		{
			$tx = GeneralTransaction::PostExpense($party, $credit, $debit, $amount, $voucher, $descr);

			if ($tx->post()) {
				$tx->expVoucher->authorize($tx->transactionId);
				echo 1;
			}else{
				echo 0;
			}
		}	

		public function injectCapital($credit, $debit, $amount, $descr)
		{
			$tx = GeneralTransaction::InjectCapital($credit, $debit, $amount, $descr);

			if ($tx->post()) {
				echo 1;
			}else{
				echo 0;
			}
		}	

		public function postC2BBankTx($action, $account, $voucher, $amount, $descr)
		{
			$tx = GeneralTransaction::postC2BBankTx($action, $account, $voucher, $amount, $descr);

			if ($tx->post()) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function postB2BBankTx($account1, $account2, $voucher, $amount, $descr)
		{
			$tx = GeneralTransaction::postB2BBankTx($account1, $account2, $voucher, $amount, $descr);

			if ($tx->post()) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function findClientEntries($client, $category, $dates, $all)
		{
			if ($this->validateAdmin()) {
				echo json_encode(TransactionVouchers::GetClientTransactions($client, $category, $dates, $all));
			}else{
				echo 0;
			}
		}

		//Helper Functions

		private function validateAdmin()
		{
			if (isset ($_SESSION['admin_logged']) && $_COOKIE['admin_logged'] == true) {
				return true;
			}else{
				//return false;
				//Development override
				return true;
			}
		}
		
		private function getUserIdentifier()
		{
			session_start();
			if (isset($_SESSION['oauth_id'])){
	      		return $_SESSION['oauth_id'];
				//echo 1; 
	  		}elseif (isset($_COOKIE['cookie_key'])){
	  			return $_COOKIE['cookie_key'];
	      		//echo $_COOKIE['session_key'].'23';
				//echo 1; 
	  		}elseif (isset($_SESSION['session_key'])){				
	      		return $_SESSION['session_key'];
	      		//echo $_SESSION['session_key'].'46';
				//echo 1; 
	  		}else{
	  			echo 0;
	  		}
					 
		}
		
	}

	/*$request_method = strtolower($_SERVER['REQUEST_METHOD']);

	switch ($request_method) {
	    case 'post':
	    case 'put':
	        $data = json_decode(file_get_contents('php://input'));
	    break;
	}*/

	$response = new FinanceApp();
?>