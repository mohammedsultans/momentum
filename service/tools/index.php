<?php
// Manages the Journals list
  	require_once '../../include/config.php';
  	require_once DOMAIN_DIR . 'LSOfficeDomain.php';
  	//require_once DOMAIN_DIR . 'Product.php';
	
	class Tools
	{
		public function __construct()
		{
			if(isset($_POST['operation'])){
				$cmd = $_POST['operation'];
				if($cmd == 'createUser'){
					if(isset($_POST['empid']) && isset($_POST['uname']) && isset($_POST['pass']) && isset($_POST['role'])){
						$empid = $_POST['empid'];
						$uname = $_POST['uname'];
						$pass = $_POST['pass'];
						$role = $_POST['role'];
						$this->createUser($empid, $uname, $pass, $role);
					}else{
						echo 0;
					}
						
				}elseif($cmd == 'modifyUser'){
					if(isset($_POST['empid']) && isset($_POST['uname2']) && isset($_POST['role2']) && isset($_POST['access'])){
						$empid = $_POST['empid'];
						$uname = $_POST['uname2'];
						$role = $_POST['role2'];
						$access = $_POST['access'];
						$this->modifyUser($empid, $uname, $role, $access);
					}else{
						echo 0;
					}
				
				}elseif($cmd == 'removeUser'){
					if(isset($_POST['id'])){
						$empid = $_POST['id'];
						$this->removeUser($empid);
					}else{
						echo 0;
					}

				}elseif($cmd == 'createRole'){
					if(isset($_POST['name']) && isset($_POST['views'])){
						$name = $_POST['name'];
						$views = $_POST['views'];
						if (isset($_POST['views'])) {
							$views = $_POST['views'];
						}else{
							$views = [];
						}
						$this->createRole($name, $views);
					}else{
						echo 0;
					}
				
				}elseif($cmd == 'modifyRole'){
					if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['views'])){
						$id = $_POST['id'];
						$name = $_POST['name'];
						$views = $_POST['views'];
						$this->modifyRole($id, $name, $views);
					}else{
						echo 0;
					}
				
				}elseif($cmd == 'deleteRole'){
					if(isset($_POST['id'])){
						$empid = $_POST['id'];
						$this->deleteUser($empid);
					}else{
						echo 0;
					}

				}elseif($cmd == 'postmemo'){
					if(isset($_POST['name']) && isset($_POST['role']) && isset($_POST['title']) && isset($_POST['message'])){
						$this->postMemo($_POST['name'], $_POST['role'], $_POST['title'], $_POST['message'], $_POST['expiry']);
					}else{
						echo 0;
					}				
				}elseif($cmd == 'changePassword'){
					if(isset($_POST['opass']) && isset($_POST['npass']) && isset($_POST['cpass'])){
						if ($_POST['npass'] == $_POST['cpass']) {
							$this->changePassword($_POST['opass'], $_POST['npass']);
						}else{
							echo 0;
						}
					}else{
						echo 0;
					}				
				}elseif($cmd == 'login'){
					if(isset($_POST['uname']) && isset($_POST['pass'])){
						$uname = $_POST['uname'];
						$pass = $_POST['pass'];
						$this->login($uname, $pass);
					}else{
						echo 0;
					}
				}elseif($cmd == 'logout'){
					$this->logout();
				}elseif($cmd == 'checkauth'){
					$this->check_auth();
				}else{ 
					echo 0;
				}
			}elseif(isset($_GET['modules'])){
				$this->getModules();
			}elseif(isset($_GET['users'])){
				$this->getUsers();
			}elseif(isset($_GET['user'])){
				$this->getUser($_GET['user']);
			}elseif(isset($_GET['roles'])){
				$this->getRoles();
			}elseif(isset($_GET['session'])){
				$this->getSession();
			}elseif(isset($_GET['dirdash'])){
				$this->getDirectorsDashboard();
			}elseif(isset($_GET['notices'])){
				$this->notices();
			}else{
				echo 0;
			}
		}
		/* Calls business tier method to read Journals list and create
		their links */

		public function createUser($empid, $uname, $pass, $role)
		{
			if (User::Create($empid, $uname, $pass, $role)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function modifyUser($empid, $uname, $role, $access)
		{
			if (User::Update($empid, $uname, $role, $access)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function removeUser($stamp)
		{
			if ($this->validateAdmin()) {
				if (User::Remove($id)) {
					echo 1;
				}else{
					echo 0;
				}
			}else{
				echo 0;
			}
		}

		public function createRole($name, $views)
		{
			if (Role::Create($name, $views)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function modifyRole($id, $name, $views)
		{
			if (Role::Update($id, $name, $views)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function deleteRole($id)
		{
			if ($this->validateAdmin()) {
				if (Role::Delete($id)) {
					echo 1;
				}else{
					echo 0;
				}
			}else{
				echo 0;
			}
		}

		public function getModules()
		{
			if ($this->validateAdmin()) {
				echo json_encode(Module::GetModules());
			}else{
				echo 0;
			}
		}

		public function getUsers()
		{
			if ($this->validateAdmin()) {
				echo json_encode(User::GetUsers());
			}else{
				echo 0;
			}
		}

		public function getUser($id)
		{
			if ($this->validateAdmin()) {
				echo json_encode(User::GetUser($id));
			}else{
				echo 0;
			}
		}

		public function getRoles()
		{
			if ($this->validateAdmin()) {
				echo json_encode(Role::GetRoles());
			}else{
				echo 0;
			}
		}

		public function getSession()
		{
			$session = SessionManager::GetSession();
			if ($session) {
				echo json_encode($session);
			}else{
				echo 0;
			}
		}

		public function login($username, $password)
		{
			$session = User::Login($username, $password);
			if ($session) {
				echo json_encode($session);
			}else{
				echo 0;
			}
		}

		public function logout()
		{
			if (SessionManager::EndSession()) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function changePassword($opass, $npass)
		{
			$session = SessionManager::GetSession();
			if ($session) {
				User::ChangePassword($session->user, $opass, $npass);
				echo 1;
			}else{
				echo 0;
			}
		}

		public function postMemo($name, $position, $title, $message, $expiry)
		{
			if (OfficeMemo::Create($name, $position, $title, $message, $expiry)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function notices()
		{			
			echo json_encode(OfficeMemo::GetCurrent());
		}

		public function getDirectorsDashboard()
		{
			if ($this->validateAdmin()) {
				echo json_encode(new DirectorsDashboard());
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

	$response = new Tools();
	//$response->init();
	//echo json_encode($response->mJournals);
?>