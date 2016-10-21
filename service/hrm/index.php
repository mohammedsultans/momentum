<?php
// Manages the Journals list
  	require_once '../../include/config.php';
  	require_once DOMAIN_DIR . 'LSOfficeDomain.php';
  	//require_once DOMAIN_DIR . 'Product.php';
	
	class HRMApp
	{
		// Constructor reads query string parameter
		public function __construct()
		{
			if(isset($_POST['operation'])){
				$operation = $_POST['operation'];
				if($operation == 'addEmployee'){
					if(isset($_POST['name']) && isset($_POST['tel']) && isset($_POST['department']) && isset($_POST['position']) && isset($_POST['salary'])){
						$name = $_POST['name'];
						$mobile = $_POST['tel'];
						$email = $_POST['email'];
						$address = $_POST['address'];
						$gender = $_POST['gender'];
						$department = $_POST['department'];
						$position = $_POST['position'];
						$salary = $_POST['salary'];
						$this->createEmployee($name, $mobile, $email, $address, $gender, $department, $position, $salary);
					}else{
						echo 0;
					}						
				}elseif($operation == 'editEmployee'){
					if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['tel']) && isset($_POST['department']) && isset($_POST['position']) && isset($_POST['salary'])){
						$employeeid = $_POST['id'];
						$name = $_POST['name'];
						$mobile = $_POST['tel'];
						$email = $_POST['email'];
						$address = $_POST['address'];						
						$gender = $_POST['gender'];
						$department = $_POST['department'];
						$position = $_POST['position'];
						$salary = $_POST['salary'];
						$this->updateEmployee($employeeid, $name, $mobile, $email, $address, $gender, $department, $position, $salary);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'deleteEmployee'){
					if(isset($_POST['id'])){
						$employeeid = $_POST['id'];
						$this->deleteEmployee($employeeid);
					}else{
						echo 0;
					}
				}elseif($operation == 'postAllowance'){
					if(isset($_POST['id']) && isset($_POST['date']) && isset($_POST['ledger']) && isset($_POST['amount']) && isset($_POST['descr'])){
						$empid = $_POST['id'];
						$date = $_POST['date'];
						$ledger = $_POST['ledger'];
						$amount = $_POST['amount'];
						$descr = $_POST['descr'];
						$this->postAllowance($empid, $date, $ledger, $amount, $descr);
					}else{
						echo 0;
					}				
				}elseif($operation == 'postOvertime'){
					if(isset($_POST['id']) && isset($_POST['date']) && isset($_POST['rate']) && isset($_POST['hours']) && isset($_POST['descr'])){
						$empid = $_POST['id'];
						$date = $_POST['date'];
						$rate = $_POST['rate'];
						$hours = $_POST['hours'];
						$descr = $_POST['descr'];
						$this->postOvertime($empid, $date, $rate, $hours, $descr);
					}else{
						echo 0;
					}				
				}elseif($operation == 'payAdvance'){
					if(isset($_POST['id']) && isset($_POST['date']) && isset($_POST['amount']) && isset($_POST['ledger']) && isset($_POST['mode']) && isset($_POST['voucher']) && isset($_POST['descr'])){
						$empid = $_POST['id'];
						$date = $_POST['date'];
						$amount = $_POST['amount'];
						$descr = $_POST['descr'];
						$ledger = $_POST['ledger'];
						$mode = $_POST['mode'];
						$voucher = $_POST['voucher'];
						$this->payAdvance($empid, $date, $amount, $ledger, $mode, $voucher, $descr);
					}else{
						echo 0;
					}	
				}elseif($operation == 'previewPayroll'){
					if(isset($_POST['month'])){
						$month = $_POST['month'];
						$this->previewPayroll($month);
					}else{
						echo 0;
					}				
				}elseif($operation == 'commitPayroll'){
					if(isset($_POST['month'])){
						$month = $_POST['month'];
						$this->commitPayroll($month);
					}else{
						echo 0;
					}				
				}elseif($operation == 'paySalary'){
					if(isset($_POST['employee']) && isset($_POST['slip']) && isset($_POST['ledger']) && isset($_POST['mode']) && isset($_POST['amount']) && isset($_POST['voucher'])){
						if ($_POST['ledger'] != 101) {
							if (FinancialTransaction::VoucherInUse($_POST['voucher'])) {
						      	echo 0;
						      	exit;
						    }
						}
						$empid = $_POST['employee'];
						$slip = $_POST['slip'];
						$ledger = $_POST['ledger'];
						$mode = $_POST['mode'];
						$voucher = $_POST['voucher'];
						$amount = $_POST['amount'];
						$this->paySalary($empid, $slip, $ledger, $mode, $voucher, $amount);
					}else{
						echo 0;
					}				
				}elseif($operation == 'findEmployeeEntries'){
					if(isset($_POST['employee'])){
						$employee = $_POST['employee'];
						$dates = $_POST['dates'];
						$all = $_POST['vall'];
						$this->findEmployeeEntries($employee, $dates, $all);
					}else{
						echo 0;
					}				
				}else{ 
					echo 0;
				}
			}elseif(isset($_GET['employees'])){
				$this->getEmployees();
			}elseif(isset($_GET['employee']) && isset($_GET['empid'])){
				$this->getEmployee($_GET['empid']);
			}elseif(isset($_GET['unclearedslips']) && isset($_GET['empid'])){
				$this->getUnclearedPayslips($_GET['empid']);
			}elseif(isset($_GET['exemployees'])){
				$this->getExEmployees();
			}else{
				echo 0;
			}
		}
		/* Calls business tier method to read Journals list and create
		their links */

		public function createEmployee($name, $mobile, $email, $address, $gender, $department, $position, $salary)
		{
			if (Employee::Create($name, $mobile, $email, $address, $gender, $department, $position, $salary)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function updateEmployee($employeeid, $name, $mobile, $email, $address, $gender, $department, $position, $salary)
		{
			if (Employee::Update($employeeid, $name, $mobile, $email, $address, $gender, $department, $position, $salary)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function deleteEmployee($employeeid)
		{
			if ($this->validateAdmin()) {
				if (Employee::Delete($employeeid)) {
					echo 1;
				}else{
					echo 0;
				}
			}else{
				echo 0;
			}
		}

		public function getEmployees()
		{
			if ($this->validateAdmin()) {
				echo json_encode(Employee::GetAllEmployees());
			}else{
				echo 0;
			}
		}

		public function getExEmployees()
		{
			if ($this->validateAdmin()) {
				echo json_encode(Employee::GetExEmployees());
			}else{
				echo 0;
			}
		}

		public function getEmployee($id)
		{
			if ($this->validateAdmin()) {
				echo json_encode(Employee::GetEmployee($id));
			}else{
				echo 0;
			}
		}

		public function postAllowance($empid, $date, $ledger, $amount, $descr)
		{
			$tx = Payroll::PostAllowance($empid, $date, $ledger, $amount, $descr);			

			$voucher = $tx->post();

			if ($voucher) {
				echo json_encode($voucher);
			}else{
				echo 0;
			}
		}

		public function postOvertime($empid, $date, $rate, $hours, $descr)
		{
			$tx = Payroll::PostOvertime($empid, $date, $rate, $hours, $descr);			

			$voucher = $tx->post();

			if ($voucher) {
				echo json_encode($voucher);
			}else{
				echo 0;
			}
		}

		public function payAdvance($empid, $date, $amount, $ledger, $mode, $voucher, $descr)
		{
			$tx = Payroll::GiveAdvance($empid, $date, $amount, $ledger, $mode, $voucher, $descr);			

			$voucher = $tx->post();

			if ($voucher) {
				echo json_encode($voucher);
			}else{
				echo 0;
			}
		}

		public function previewPayroll($month)
		{
			if ($this->validateAdmin()) {
				echo json_encode(Payroll::PreviewPayroll($month));
			}else{
				echo 0;
			}
		}

		public function commitPayroll($month)
		{
			if ($this->validateAdmin()) {
				echo json_encode(Payroll::CommitPayroll($month));
			}else{
				echo 0;
			}
		}

		public function paySalary($empid, $slipid, $ledger, $mode, $voucher, $amount)
		{
			$tx = Payroll::PaySalary($empid, $slipid, $ledger, $mode, $voucher, $amount);			

			$voucher = $tx->post();

			if ($voucher) {
				echo json_encode($voucher);
			}else{
				echo 0;
			}
		}

		public function getUnclearedPayslips($empid)
		{
			if ($this->validateAdmin()) {
				echo json_encode(Payslip::GetUncleared($empid));
			}else{
				echo 0;
			}
		}

		public function findEmployeeEntries($employeeid, $dates, $all)
		{
			if ($this->validateAdmin()) {
				echo json_encode(TransactionVouchers::GetEmployeeTransactions($employeeid, $dates, $all));
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
	//echo $request_method;
	$data = null;

	switch ($request_method) {
	    case 'post':
	    case 'put':
	        $data = json_decode(file_get_contents('php://input'));
	    break;
	}*/

	$response = new HRMApp();
	//$response->init();
	//echo json_encode($response->mJournals);
?>