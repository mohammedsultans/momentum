<?php
// Manages the Journals list
  	require_once '../../include/config.php';
  	require_once DOMAIN_DIR . 'LSOfficeDomain.php';
  	//require_once DOMAIN_DIR . 'Product.php';
	
	class CRM
	{
		// Constructor reads query string parameter
		public function __construct()
		{
			if(isset($_POST['operation'])){
				$operation = $_POST['operation'];
				if($operation == 'enquiry'){
					if(isset($_POST['name']) && isset($_POST['tel'])){
						$name = $_POST['name'];
						$mobile = $_POST['tel'];
						if (isset($_POST['service'])) {
							$services = $_POST['service'];
						}
						$servs;
						foreach ($services as $key => $serv) {
							if ($key == 0) {
								$servs = $serv;
							}else{
								$servs .= ', '.$serv;
							}
						}
						$details = $_POST['details'];
						$this->logEnquiry($name, $mobile, $servs, $details);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'checkenquiry'){
					if(isset($_POST['stamp'])){
						$this->checkEnquiry($_POST['stamp']);
					}else{
						echo 0;
					}
						
				}elseif($operation == 'addEmployee'){
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
				}elseif($operation == 'logout'){
					$this->logout();
				}elseif($operation == 'checkauth'){
					$this->check_auth();
				}else{ 
					echo 0;
				}
			}elseif(isset($_GET['pending'])){
				$this->getPending();
			}elseif(isset($_GET['employees'])){
				$this->getEmployees();
			}elseif(isset($_GET['employee']) && isset($_GET['empid'])){
				$this->getEmployee($_GET['empid']);
			}else{
				echo 0;
			}
		}
		/* Calls business tier method to read Journals list and create
		their links */

		public function logEnquiry($name, $mobile, $services, $details)
		{
			if (Enquiry::Create($name, $mobile, $services, $details)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function getPending()
		{
			if ($this->validateAdmin()) {
				echo json_encode(Enquiry::GetPending());
			}else{
				echo 0;
			}
		}

		public function checkEnquiry($stamp)
		{
			Enquiry::Check($stamp);
			$enquiry = Enquiry::GetEnquiry($stamp);
			if ($enquiry->status == 1) {
				echo 1;
			}else{
				echo 0;
			}
		}

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

		public function getEmployee($id)
		{
			if ($this->validateAdmin()) {
				echo json_encode(Employee::GetEmployee($id));
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

	$response = new CRM();
	//$response->init();
	//echo json_encode($response->mJournals);
?>