<?php
// Manages the Journals list
  	require_once '../../include/config.php';
  	require_once DOMAIN_DIR . 'LSOfficeDomain.php';
  	//require_once DOMAIN_DIR . 'Product.php';
	
	class Operations
	{
		// Constructor reads query string parameter
		public function __construct()
		{
			if(isset($_POST['operation'])){
				$operation = $_POST['operation'];
				if($operation == 'genQuote'){
					if(isset($_POST['client']) && isset($_POST['items'])){
						$clientid = $_POST['client'];
						$items = $_POST['items'];
						$this->generateQuote($clientid, $items);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'createProject'){
					if(isset($_POST['client']) && isset($_POST['project'])){
						$name = $_POST['project'];
						$location = $_POST['location'];
						$descr = $_POST['descr'];
						$clientid =  $_POST['client'];
						if (isset($_POST['quotes'])) {
							$quotes = $_POST['quotes'];
						}else{
							$quotes = [];
						}
						$this->createProject($name, $location, $descr, $clientid, $quotes);
					}else{
						echo 0;
					}
						
				}elseif($operation == 'modifyProject'){
					if(isset($_POST['projectid']) && isset($_POST['project'])){
						$id = $_POST['projectid'];
						$name = $_POST['project'];
						$location = $_POST['location'];
						$descr = $_POST['descr'];
						$status =  $_POST['status'];
						if (isset($_POST['quotes'])) {
							$quotes = $_POST['quotes'];
						}else{
							$quotes = [];
						}
						$this->modifyProject($id, $name, $location, $status, $descr, $quotes);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'fileReport'){
					if(isset($_POST['project']) && isset($_POST['activity']) && isset($_POST['location']) && isset($_POST['personell']) && isset($_POST['report'])){
						$pid = $_POST['project'];
						$location = $_POST['location'];
						$report = $_POST['report'];
						if (isset($_POST['activity'])) {
							$activities = $_POST['activity'];
						}else{
							$activities = [];
						}
						if (isset($_POST['personell'])) {
							$personell = $_POST['personell'];
						}else{
							$personell = [];
						}
						if (isset($_POST['charges'])) {
							$charges = $_POST['charges'];
						}else{
							$charges = [];
						}
						$this->fileReport($pid, $activities, $location, $personell, $report, $charges);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'deleteProject'){
					if(isset($_POST['projectid'])){
						$id = $_POST['projectid'];
						$this->deleteProject($id);
					}else{
						echo 0;
					}
				}elseif($operation == 'createService'){
					if(isset($_POST['name'])){
						$name = $_POST['name'];
						$this->createService($name);
					}else{
						echo 0;
					}
				}elseif($operation == 'deleteService'){
					if(isset($_POST['name'])){
						$name = $_POST['name'];
						$this->deleteService($name);
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
			}elseif(isset($_GET['quotes']) && isset($_GET['clientid'])){
				$this->getClientQuotes($_GET['clientid']);
			}elseif(isset($_GET['quotes']) && isset($_GET['project'])){
				$this->getProjectQuotes($_GET['project']);
			}elseif(isset($_GET['projects']) && isset($_GET['clientid'])){
				$this->getClientProjects($_GET['clientid']);
			}elseif(isset($_GET['allProjects'])){
				$this->getAllProjects();
			}elseif(isset($_GET['projectExpenses'])){
				$this->getProjectExpenses($_GET['projectExpenses']);
			}elseif(isset($_GET['projectClaims'])){
				$this->getProjectClaims($_GET['projectClaims']);
			}elseif(isset($_GET['quote'])){
				$this->getQuote($_GET['quote']);
			}elseif(isset($_GET['services'])){
				$this->getServices($_GET['services']);
			}elseif(isset($_GET['genquotes'])){
				$this->getGeneralQuotes($_GET['genquotes']);
			}elseif(isset($_GET['project'])){
				$this->getProject($_GET['project']);
			}else{
				echo 0;
			}
		}
		/* Calls business tier method to read Journals list and create
		their links */

		public function generateQuote($clientid, $items)
		{
			$client = Client::GetClient($clientid);

			$quotation = Quotation::CreateQuotation($client);

			foreach ($items as $item) {
				$ql = QuotationLine::Create($quotation->id, $item['service'], $item['task'], $item['qty'], $item['price'], $item['tax']);
		        $quotation->addToQuote($ql);
			}
			if ($quotation->generate()) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function getClientQuotes($cid)
		{
			if ($this->validateAdmin()) {
				echo json_encode(Quotation::GetClientQuotations($cid));
			}else{
				echo 0;
			}
		}

		public function getProjectQuotes($pid)
		{
			$project = Project::GetProject($pid);
			if ($this->validateAdmin()) {
				echo json_encode($project->quotations);
			}else{
				echo 0;
			}
		}

		public function getGeneralQuotes($cid)
		{
			if ($this->validateAdmin()) {
				echo json_encode(Quotation::GetGeneralQuotations($cid));
			}else{
				echo 0;
			}
		}

		public function getQuote($qid)
		{
			if ($this->validateAdmin()) {
				echo json_encode(Quotation::GetQuotation($qid));
			}else{
				echo 0;
			}
		}

		public function getServices()
		{
			if ($this->validateAdmin()) {
				echo json_encode(BillableService::GetAll());
			}else{
				echo 0;
			}
		}

		public function createService($name)
		{
			if (BillableService::Create($name)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function deleteService($name)
		{
			if (BillableService::Delete($name)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function createProject($name, $location, $descr, $clientid, $quotes)
		{
			$project = Project::Create($name, $location, $descr, $clientid, $quotes);
			foreach ($quotes as $qid) {
				$project->importQuote($qid);
			}

			if ($project->authorize()) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function modifyProject($id, $name, $location, $status, $descr, $quotes)
		{
			$project = Project::Update($id, $name, $location, $status, $descr);
			foreach ($quotes as $qid) {
				$project->importQuote($qid);
			}

			if ($project->authorize()) {
				echo 1;
			}else{
				echo 0;
			}
		}
		
		public function fileReport($pid, $activities, $location, $personell, $report, $charges)
		{
			$report = WorkReport::Create($pid, $activities, $location, $personell, $report, $charges);
			if ($report) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function getClientProjects($clientid)
		{
			if ($this->validateAdmin()) {
				echo json_encode(Project::GetClientProjects($clientid));
			}else{
				echo 0;
			}
		}

		public function getAllProjects()
		{
			if ($this->validateAdmin()) {
				echo json_encode(Project::GetAllProjects());
			}else{
				echo 0;
			}
		}

		public function getProject($projectid)
		{
			if ($this->validateAdmin()) {
				echo json_encode(Project::GetProject($projectid));
			}else{
				echo 0;
			}
		}

		public function deleteProject($id)
		{
			if ($this->validateAdmin()) {
				if (Project::Delete($id)) {
					echo 1;
				}else{
					echo 0;
				}
			}else{
				echo 0;
			}
		}

		public function getProjectExpenses($pid)
		{
			if ($this->validateAdmin()) {
				echo json_encode(ExpenseVoucher::GetProjectVouchers($pid));
			}else{
				echo 0;
			}
		}

		public function getProjectClaims($pid)
		{
			if ($this->validateAdmin()) {
				echo json_encode(ExpenseVoucher::GetProjectClaims($pid));
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

	$response = new Operations();
	//$response->init();
	//echo json_encode($response->mJournals);
?>