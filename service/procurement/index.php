<?php
  	require_once '../../include/config.php';
  	require_once DOMAIN_DIR . 'LSOfficeDomain.php';
  	//require_once DOMAIN_DIR . 'Product.php';
	
	class ProcurementApp
	{
		public function __construct()
		{
			if(isset($_POST['operation'])){
				$operation = $_POST['operation'];
				if($operation == 'addSupplier'){
					if(isset($_POST['name']) && isset($_POST['tel'])){
						$name = $_POST['name'];
						$person = $_POST['person'];
						$mobile = $_POST['tel'];
						$email = $_POST['email'];
						$address = $_POST['address'];
						$bal = $_POST['bal'];
						$this->createSupplier($name, $person, $mobile, $email, $address, $bal);
					}else{
						echo 0;
					}
						
				}elseif($operation == 'editSupplier'){
					if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['tel'])){
						$supplierid = $_POST['id'];
						$name = $_POST['name'];
						$person = $_POST['person'];
						$mobile = $_POST['tel'];
						$email = $_POST['email'];
						$address = $_POST['address'];
						$this->updateSupplier($supplierid, $name, $person, $mobile, $email, $address);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'deleteSupplier'){
					if(isset($_POST['id'])){
						$supplierid = $_POST['id'];
						$this->deleteSupplier($supplierid);
					}else{
						echo 0;
					}
				}elseif($operation == 'genPurchOrder'){
					if(isset($_POST['supplier']) && isset($_POST['date']) && isset($_POST['items'])){
						$supplierid = $_POST['supplier'];					
						$date = $_POST['date'];
						if (isset($_POST['items'])) {
							$items = $_POST['items'];
						}else{
							$items = [];
						}
						$this->generatePurchaseOrder($supplierid, $date, $items);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'postGenPurchase'){
					if(isset($_POST['supplier']) && isset($_POST['invno']) && isset($_POST['date']) && isset($_POST['items'])){
						$supplierid = $_POST['supplier'];
						$invno = $_POST['invno'];						
						$date = $_POST['date'];
						$items = $_POST['items'];
						$this->postGenPurchase($supplierid, $invno, $date, $items);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'postOrderPurchase'){
					if(isset($_POST['supplier']) && isset($_POST['invno']) && isset($_POST['date']) && isset($_POST['items'])){
						$supplierid = $_POST['supplier'];
						$invno = $_POST['invno'];						
						$date = $_POST['date'];
						$items = $_POST['items'];
						$this->postOrderPurchase($supplierid, $invno, $date, $items);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'makePaymentGRN'){
					if(isset($_POST['supplier']) && isset($_POST['account']) && isset($_POST['mode']) && isset($_POST['amount']) && isset($_POST['payments']) && isset($_POST['voucher'])){
						if ($_POST['account'] != 101) {
							if (FinancialTransaction::VoucherInUse($_POST['voucher'])) {
						      	echo 0;
						      	exit;
						    }
						}
						$supplierid = $_POST['supplier'];
						$amount = $_POST['amount'];
						$account = $_POST['account'];
						$mode = $_POST['mode'];
						$voucher =  $_POST['voucher'];
						$payments = $_POST['payments'];
						$this->makePaymentGRN($supplierid, $amount, $account, $mode, $voucher, $payments);
					}else{
						echo 0;
					}
						
				}elseif($operation == 'makePayment'){
					if(isset($_POST['context']) && isset($_POST['scope']) && isset($_POST['supplier']) && isset($_POST['account']) && isset($_POST['mode']) && isset($_POST['amount']) && isset($_POST['descr']) && isset($_POST['voucher'])){
						if ($_POST['account'] != 101) {
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
						$scope = $_POST['scope'];
						if ($scope == 'office') {
							$scope = 0;
						}else{
							$scope == intval($scope);
						}
						$supplierid = $_POST['supplier'];
						$amount = $_POST['amount'];
						$account = $_POST['account'];
						$mode = $_POST['mode'];
						$voucher =  $_POST['voucher'];
						$descr = $_POST['descr'];
						$this->makePayment($party, $scope, $supplierid, $amount, $account, $mode, $voucher, $descr);
					}else{
						echo 0;
					}
						
				}elseif($operation == 'findSupplierEntries'){
					if(isset($_POST['supplier']) && isset($_POST['category'])){
						$supplier = $_POST['supplier'];
						$category = $_POST['category'];
						$dates = $_POST['dates'];
						$all = $_POST['vall'];
						$this->findSupplierEntries($supplier, $category, $dates, $all);
					}else{
						echo 0;
					}				
				}elseif($operation == 'logout'){
					$this->logout();
				}elseif($operation == 'checkauth'){
					$this->check_auth();
				}else{ 
					echo 0;
				}
			}elseif(isset($_GET['pending'])){
				$this->getPending();
			}elseif(isset($_GET['suppliers'])){
				$this->getSuppliers();
			}elseif(isset($_GET['porders']) && isset($_GET['supplierid'])){
				$this->getPurchaseOrders($_GET['supplierid']);
			}elseif(isset($_GET['porder']) && isset($_GET['orderid'])){
				$this->getPurchaseOrder($_GET['orderid']);
			}elseif(isset($_GET['unclearedinvoices']) && isset($_GET['supplier'])){
				$this->getUnclearedInvoices($_GET['supplier']);
			}elseif(isset($_GET['supplier']) && isset($_GET['supplierid'])){
				$this->getSupplier($_GET['supplierid']);
			}else{
				echo 0; 
			}
		}
		/* PROCUREMENT APPLICATION INTERFACE */
		public function createSupplier($name, $person, $mobile, $email, $address, $bal)
		{
			if (Supplier::Create($name, $person, $mobile, $email, $address, $bal)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function updateSupplier($supplierid, $name, $person, $mobile, $email, $address)
		{
			if (Supplier::Update($supplierid, $name, $person, $mobile, $email, $address)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function deleteSupplier($supplierid)
		{
			if ($this->validateAdmin()) {
				if (Supplier::Delete($supplierid)) {
					echo 1;
				}else{
					echo 0;
				}
			}else{
				echo 0;
			}
		}

		public function getSuppliers()
		{
			if ($this->validateAdmin()) {
				echo json_encode(Supplier::GetAllSuppliers());
			}else{
				echo 0;
			}
		}

		public function getSupplier($id)
		{
			if ($this->validateAdmin()) {
				echo json_encode(Supplier::GetSupplier($id));
			}else{
				echo 0;
			}
		}

		public function generatePurchaseOrder($supplierid, $date, $items)
		{
			$supplier = Supplier::GetSupplier($supplierid);

			$order = PurchaseOrder::CreateOrder($supplier, $date);

			foreach ($items as $item) {
				$ql = PurchaseOrderLine::Create($order->id, $item['item'], $item['qty'], $item['price']);
		        $order->addToOrder($ql);
			}

			$voucher = $order->generate();

			if ($voucher) {
				echo json_encode($voucher);
			}else{
				echo 0;
			}
		}

		public function postGenPurchase($supplierid, $invno, $date, $items)
		{
			$invoice = PurchaseTX::RaiseGeneralPurchase($supplierid, $invno, $date, $items);			

			$voucher = $invoice->post();

			if ($voucher) {
				echo json_encode($voucher);
			}else{
				echo 0;
			}
		}

		public function postOrderPurchase($supplierid, $invno, $date, $quotes)
		{
			$invoice = PurchaseTX::RaiseOrderPurchase($supplierid, $invno, $date, $quotes);

			$voucher = $invoice->post();

			if ($voucher) {
				echo json_encode($voucher);
			}else{
				echo 0;
			}
		}

		public function makePaymentGRN($supplierid, $amount, $account, $mode, $voucher, $payments)
		{
			$payment = GRNPaymentTX::MakePayment($supplierid, $amount, $account, $mode, $voucher, $payments);
			$voucher = $payment->submit();
			if ($voucher) {
				echo json_encode($voucher);
			}else{
				echo 0;
			}
		}

		public function makePayment($party, $scope, $supplierid, $amount, $account, $mode, $voucher, $description)
		{
			$payment = PaymentTX::MakePayment($party, $scope, $supplierid, $amount, $account, $mode, $voucher, $description);
			$voucher = $payment->submit();
			$payment->expVoucher->authorize($payment->transactionId);
			if ($voucher) {
				echo json_encode($voucher);
			}else{
				echo 0;
			}
		}

		public function findSupplierEntries($supplierid, $category, $dates, $all)
		{
			if ($this->validateAdmin()) {
				echo json_encode(TransactionVouchers::GetSupplierTransactions($supplierid, $category, $dates, $all));
			}else{
				echo 0;
			}
		}

		public function getUnclearedInvoices($supplierid)
		{
			if ($this->validateAdmin()) {
				echo json_encode(PurchaseInvoice::GetUnclearedInvoices($supplierid));
			}else{
				echo 0;
			}
		}

		public function getPurchaseOrders($supplierid)
		{
			if ($this->validateAdmin()) {
				echo json_encode(PurchaseOrder::GetSupplierOrders($supplierid));
			}else{
				echo 0;
			}
		}

		public function getPurchaseOrder($orderid)
		{
			if ($this->validateAdmin()) {
				echo json_encode(PurchaseOrder::GetOrder($orderid));
			}else{
				echo 0;
			}
		}

		//HELPER FUNCTIONS

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
	//echo $request_method;
	$data = null;

	switch ($request_method) {
	    case 'post':
	    case 'put':
	        $data = json_decode(file_get_contents('php://input'));
	    break;
	}*/

	$response = new ProcurementApp();
	//$response->init();
	//echo json_encode($response->mJournals);
?>