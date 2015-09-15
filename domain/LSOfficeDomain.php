<?php
//Author: - Alexander Maera, +254727596626, Thika.Nairobi.Kisii - Kenya.
session_start();
require_once '/../include/config.php';
require_once DATA_DIR . 'error_handler.php';
ErrorHandler::SetHandler();
require_once DATA_DIR . 'database_handler.php';

require_once('Inventory.php');
require_once('Accountability.php');
require_once('Accounting.php');
require_once('Party.php');
require_once('PaymentMethod.php');
require_once('DomainAccounting.php');
require_once('DomainProjects.php');

class Logger
{
 	//System logging facility
 	function __construct($message)
 	{
 		$datetime = new DateTime();
		$stamp = $datetime->format('YmdHis');
 		$sql = 'INSERT INTO logs (message, stamp) VALUES ("'.$message.'", '.$stamp.')';
		DatabaseHandler::Execute($sql);
 	}

 	public static function Log($message)
 	{
 		$datetime = new DateTime();
		$stamp = $datetime->format('YmdHis');
 		$sql = 'INSERT INTO logs (message, stamp) VALUES ("'.$message.'", '.$stamp.')';
		DatabaseHandler::Execute($sql);
 	}
}

class UserSession
{
 	//All user sessions are non cookie sessions
 	public $user;
 	public $loginTime;
 	public $logoutTime;

 	function __construct($user)
 	{
 		$this->user = $user;
 		$this->login();
 	}

 	public function login()
  	{
      	$datetime = new DateTime();
		$this->loginTime = $datetime->format('Y/m/d H:i:s a');
 		Logger::Log($this->user->username.' logs in at '.$this->loginTime.' from terminal XXX');
  	}

 	public function logout()
  	{
      	$datetime = new DateTime();
		$this->logoutTime = $datetime->format('Y/m/d H:i:s a');
		Logger::Log($this->user->username.' logs out at '.$this->logoutTime.' from terminal XXX');
		//$this::__destroy();
  	}
}

class SessionManager
{
	public static $activeSessions = 0;

	function __construct()
	{
		//$_SESSION['sys_session'] = new ShoppingCart();
	}

	public static function StartSession(User $user)
  	{
      	if ($user->authorized) {
			if (isset($_SESSION['session_key'])){
		       	unset($_SESSION['session_key']);
		  	}

		  	if (isset($_SESSION['session_user'])){
		       	unset($_SESSION['session_user']);
		  	}

	     	$_SESSION['session_key'] = $user->id;
			$_SESSION['session_user'] = new UserSession($user);

			self::$activeSessions++;
			return true;
	  	}else{
			return false;
		}
  	}

  	public static function EndSession()
  	{
      	$userKey = $_SESSION['session_key'];
      	$userSession = $_SESSION['session_user'];
	    unset($_SESSION['session_key']);
	    unset($_SESSION['session_user']);
							
		if (!empty($userKey) && !empty($userSession)) {
	  		if (session_destroy()){
				$userSession->logout();
				self::$activeSessions--;
				return true;
			}else{
				return false;
			}
		}else{
	 		return false;
		}
  	}

  	public static function ValidateUser()
  	{
      	$userKey = $_SESSION['session_key'];
      	$userSession = $_SESSION['session_user'];
							
		if (!empty($userKey) && !empty($userSession)) {
	  		return true;
		}else{
	 		return false;
		}
  	}

  	public static function GetSession()
  	{
      	if (isset($_SESSION['session_key']) && isset($_SESSION['session_user'])){
	  		return $_SESSION['session_user'];
		}else{
	 		return false;
		}
  	}

  	public static function ValidateOperation()
  	{
      	
  	}
}

class Client extends Party
{
  	public $creditRating;
  	public $accounts = array();
  	public $balance;
  	//public $stockAccountNumber;
  	function __construct($id, $name, $telephone, $email, $address, $bal)
	{
		$type = new PartyType('Client');
		$this->balance = new Money(floatval($bal), Currency::Get('KES'));
		parent::__construct($type, $id, $name, $telephone, $email, $address);
	}

  	public static function Update($id, $name, $telephone, $email, $address)
  	{      	
  		try {
	        $sql = 'UPDATE clients SET name = "'.$name.'", telephone = "'.$telephone.'", email = "'.$email.'", address = "'.$address.'" WHERE id = '.$id;
	        DatabaseHandler::Execute($sql);
	        return true;
	    } catch (Exception $e) {
	        
	    }
  	}

  	public function makePayment(Invoice $invoice, Payment $payment)
  	{      	
  		$receipt = $invoice->postPayment($payment);
  		if ($receipt) {
  			return $receipt;
  		}
  		//$latestReceipt = $invoice->payments[count($invoice->payments) - 1];
  	}

  	public static function Create($name, $telephone, $email, $address, $bal)
  	{      	
  		$type = new PartyType('Client');
		$client = new Client($type, $name, $telephone, $email, $address, $bal);
		
		if ($client->save()) {
			return $client;
		}
		return false;
  	}

	public static function GetClient($id)
    {
        $sql = 'SELECT * FROM clients WHERE id = '.intval($id).' AND type = "Client"';
        $res =  DatabaseHandler::GetRow($sql);
        return self::initializeClient($res);
    }

    public static function Delete($id)
    {
        try {
        	$sql = 'DELETE FROM clients WHERE id = '.intval($id);
        	$res =  DatabaseHandler::Execute($sql);
        	return true;
        } catch (Exception $e) {
        	return false;
        }       
    }

    public static function GetAllClients()
    {
        $sql = 'SELECT * FROM clients WHERE type = "Client"';
        // Execute the query and return the results
        $res =  DatabaseHandler::GetAll($sql);
        $parties = array();
        foreach ($res as $item) {
          $parties[] = self::initializeClient($item);
        }
        return $parties;
    }

    private static function initializeClient($args)
    {
      //parent::__construct();
      if (!isset($args['id'])) {
        $args['id'] = 65824;//use random number, more especially a uuid
      }

      $party = new Client($args['id'], $args['name'], $args['telephone'], $args['email'], $args['address'], $args['balance']);
      
      return $party;
    }

    private function save()
    {
      try {
        $sql = 'SELECT * FROM clients WHERE name = "'.$this->name.'" AND telephone = "'.$this->telephone.'"';
	    // Execute the query and return the results
	    $res =  DatabaseHandler::GetRow($sql);
	    if (!empty($res['id'])) {
	    	return false;
	    }else{
	    	$sql = 'INSERT INTO clients (type, name, telephone, address, email) 
	        VALUES ("'.$this->type->name.'", "'.$this->name.'", "'.$this->telephone.'", "'.$this->address.'", "'.$this->email.'")';
	        DatabaseHandler::Execute($sql);
	        if ($this->balance->amount != 0) {
	        	$sql = 'SELECT * FROM clients WHERE name = "'.$this->name.'" AND telephone = "'.$this->telephone.'"';
		        // Execute the query and return the results
		        $res =  DatabaseHandler::GetRow($sql);
	        	return $this->transferBalance($res['id'], $this->balance);
	        }
	    }

        
        return true;
      } catch (Exception $e) {
        return false;
      }

    }

    private function transferBalance($clientId, $amount)
    {      
    	$transfer = new BalanceTransfer($clientId, $amount);
    	return $transfer->execute();
    }
}

class Vendor extends Party
{
	public $website;
	public $contactPerson;

	function __construct($id, $name, $telephone, $email, $address, $city, $country, $website, $contact)
	{
		$type = new PartyType('Vendor');
		parent::__construct($type, $id, $name, $telephone, $email, $address);
		$this->city = $city;
		$this->country = $country;
		$this->website = $website;
		$this->contactPerson = $contact;//PartyType('Account Manager');
	}

	public static function GetVendor()
	{
		return new Vendor(1, 'QET Systems Ltd.', '0727596626', 'support@qet.co.ke', 'Kigio Plaza 3rd Fl, Box 7685-01000, Thika CBD', 'Thika', 'Kenya', 'www.qet.co.ke', 'Alex Mbaka');
	}
}

class Enquiry extends Artifact
{
  	public $name;
  	public $tel;
  	public $services;
  	public $details;
  	public $date;
	public $stamp;
	public $status;

  	function __construct($name, $tel, $services, $details, $date, $stamp, $status = 0)
	{
		$this->name = $name;
		$this->tel = $tel;
		$this->services = $services;
		$this->details = $details;
		$this->date = $date;
		$this->stamp = $stamp;
		$this->status = $status;
	}

  	public static function Check($stamp)
  	{      	
  		try {
	        $sql = 'UPDATE enquiries SET status = 1 WHERE stamp = '.$stamp;
	        DatabaseHandler::Execute($sql);
	    } catch (Exception $e) {
	        
	    }
  	}

  	public static function GetEnquiry($stamp)
  	{      	
  		$sql = 'SELECT * FROM enquiries WHERE stamp = '.$stamp;
        $res =  DatabaseHandler::GetRow($sql);        
        return self::initialize($res);
  	}

  	public static function Create($name, $tel, $services, $details)
  	{      	
  		try {
  			$datetime = new DateTime();
	        $sql = 'INSERT IGNORE INTO enquiries (name, telephone, services, details, date, stamp) 
	        VALUES ("'.$name.'", "'.$tel.'", "'.$services.'", "'.$details.'", "'.$datetime->format('d/m/Y').'", '.$datetime->format('YmdHis').')';
	        DatabaseHandler::Execute($sql);

	        return self::GetEnquiry($datetime->format('YmdHis'));
	    } catch (Exception $e) {
	        
	    }
  	}

  	public static function GetPending()
  	{      	
  		$sql = 'SELECT * FROM enquiries WHERE status = 0';
        // Execute the query and return the results
        $res =  DatabaseHandler::GetAll($sql);
        $enquiries = array();
        foreach ($res as $item) {
          $enquiries[] = self::initialize($item);
        }
        return $enquiries;
  	}

  	private static function initialize($args)
  	{
     	$enquiry = new Enquiry($args['name'], $args['telephone'], $args['services'], $args['details'], $args['date'], $args['stamp'], $args['status']);
      	return $enquiry;
  	}
}

class Supplier extends Party
{
  	public $creditRating;
  	public $accounts = array();
  	public $balance;
  	//public $stockAccountNumber;
  	function __construct($id, $name, $telephone, $email, $address, $bal)
	{
		$type = new PartyType('Supplier');
		$this->balance = new Money(floatval($bal), Currency::Get('KES'));
		parent::__construct($type, $id, $name, $telephone, $email, $address);
	}

  	public static function Update($id, $name, $telephone, $email, $address)
  	{      	
  		try {
	        $sql = 'UPDATE suppliers SET name = "'.$name.'", telephone = "'.$telephone.'", email = "'.$email.'", address = "'.$address.'" WHERE id = '.$id;
	        DatabaseHandler::Execute($sql);
	        return true;
	    } catch (Exception $e) {
	        
	    }
  	}

  	public static function Create($name, $telephone, $email, $address, $bal)
  	{      	
  		$type = new PartyType('Supplier');
		$supplier = new Supplier($type, $name, $telephone, $email, $address, $bal);
		
		if ($supplier->save()) {
			return $supplier;
		}
		return false;
  	}

	public static function GetSupplier($id)
    {
        $sql = 'SELECT * FROM suppliers WHERE id = '.intval($id).' AND type = "Supplier"';
        $res =  DatabaseHandler::GetRow($sql);
        return self::initializeSupplier($res);
    }

    public static function Delete($id)
    {
        try {
        	$sql = 'DELETE FROM suppliers WHERE id = '.intval($id);
        	$res =  DatabaseHandler::Execute($sql);
        	return true;
        } catch (Exception $e) {
        	return false;
        }       
    }

    public static function GetAllSuppliers()
    {
        $sql = 'SELECT * FROM suppliers WHERE type = "Supplier"';
        // Execute the query and return the results
        $res =  DatabaseHandler::GetAll($sql);
        $parties = array();
        foreach ($res as $item) {
          $parties[] = self::initializeSupplier($item);
        }
        return $parties;
    }

    private static function initializeSupplier($args)
    {
      //parent::__construct();
      if (!isset($args['id'])) {
        $args['id'] = 65824;//use random number, more especially a uuid
      }

      $party = new Supplier($args['id'], $args['name'], $args['telephone'], $args['email'], $args['address'], $args['balance']);
      
      return $party;
    }

    private function save()
    {
      try {
        $sql = 'SELECT * FROM suppliers WHERE name = "'.$this->name.'" AND telephone = "'.$this->telephone.'"';
	    // Execute the query and return the results
	    $res =  DatabaseHandler::GetRow($sql);
	    if (!empty($res['id'])) {
	    	return false;
	    }else{
	    	$sql = 'INSERT INTO suppliers (type, name, telephone, address, email) 
	        VALUES ("'.$this->type->name.'", "'.$this->name.'", "'.$this->telephone.'", "'.$this->address.'", "'.$this->email.'")';
	        DatabaseHandler::Execute($sql);
	        if ($this->balance->amount != 0) {
	        	$sql = 'SELECT * FROM suppliers WHERE name = "'.$this->name.'" AND telephone = "'.$this->telephone.'"';
		        // Execute the query and return the results
		        $res =  DatabaseHandler::GetRow($sql);
	        	return $this->transferBalance($res['id'], $this->balance);
	        }
	    }

        
        return true;
      } catch (Exception $e) {
        return false;
      }

    }

    private function transferBalance($supplierId, $amount)
    {      
    	$transfer = new SupplierBalanceTransfer($supplierId, $amount);
    	return $transfer->execute();
    }
}

class Employee extends Party
{
  	public $creditRating;
  	public $accounts = array();
  	public $balance;
  	public $gender;
  	public $department;
  	public $position;
  	public $salary;
  	//public $stockAccountNumber;
  	function __construct($id, $name, $telephone, $email, $address, $gender, $department, $position, $salary, $bal)
	{
		$type = new PartyType('Employee');
		$this->balance = new Money(floatval($bal), Currency::Get('KES'));
		$this->salary = $salary;
		$this->gender = $gender;
		$this->department = $department;
		$this->position = $position;
		parent::__construct($type, $id, $name, $telephone, $email, $address);
	}

  	public static function Update($id, $name, $telephone, $email, $address, $gender, $department, $position, $salary)
  	{      	
  		try {
	        $sql = 'UPDATE employees SET name = "'.$name.'", telephone = "'.$telephone.'", email = "'.$email.'", address = "'.$address.'", gender = "'.$gender.'", department = "'.$department.'", position = "'.$position.'", salary = '.floatval($salary).' WHERE id = '.$id;
	        DatabaseHandler::Execute($sql);
	        return true;
	    } catch (Exception $e) {
	        
	    }
  	}

  	public static function Create($name, $telephone, $email, $address, $gender, $department, $position, $salary)
  	{      	
  		$type = new PartyType('Employee');
		$employee = new Employee($type, $name, $telephone, $email, $address, $gender, $department, $position, $salary, 0);
		
		if ($employee->save()) {
			return $employee;
		}
		return false;
  	}

	public static function GetEmployee($id)
    {
        $sql = 'SELECT * FROM employees WHERE id = '.intval($id).' AND type = "Employee"';
        $res =  DatabaseHandler::GetRow($sql);
        return self::initializeEmployee($res);
    }

    public static function Delete($id)
    {
        try {
        	$sql = 'DELETE FROM employees WHERE id = '.intval($id);
        	$res =  DatabaseHandler::Execute($sql);
        	return true;
        } catch (Exception $e) {
        	return false;
        }       
    }

    public static function GetAllEmployees()
    {
        $sql = 'SELECT * FROM employees WHERE type = "Employee"';
        // Execute the query and return the results
        $res =  DatabaseHandler::GetAll($sql);
        $parties = array();
        foreach ($res as $item) {
          $parties[] = self::initializeEmployee($item);
        }
        return $parties;
    }

    private static function initializeEmployee($args)
    {

      $party = new Employee($args['id'], $args['name'], $args['telephone'], $args['email'], $args['address'], $args['gender'], $args['department'], $args['position'], $args['salary'], $args['balance']);
      
      return $party;
    }

    private function save()
    {
      try {
        $sql = 'SELECT * FROM employees WHERE name = "'.$this->name.'" AND telephone = "'.$this->telephone.'"';
	    // Execute the query and return the results
	    $res =  DatabaseHandler::GetRow($sql);
	    if (!empty($res['id'])) {
	    	return false;
	    }else{
	    	$sql = 'INSERT INTO employees (type, name, telephone, address, email, gender, department, position, salary, balance) 
	        VALUES ("'.$this->type->name.'", "'.$this->name.'", "'.$this->telephone.'", "'.$this->address.'", "'.$this->email.'", "'.$this->gender.'", "'.$this->department.'", "'.$this->position.'", '.floatval($this->salary).', '.$this->balance->amount.')';
	        DatabaseHandler::Execute($sql);
	    }

        
        return true;
      } catch (Exception $e) {
        return false;
      }

    }

    public function registerUser($username, $password, $role)
  	{
      	
  		//$query = self::customerCheck($this->email);

  		try {
	        $sql = 'UPDATE employees SET identification = "'.$username.'", password = sha1("'.$password.'"), role = "'.$role.'", access = 1 WHERE id = '.$id;
	        DatabaseHandler::Execute($sql);
	        
	        $sql2 = 'SELECT * FROM employees WHERE email = "'.$this->email.'"';
			// Execute the query and return the results
			$res =  DatabaseHandler::GetRow($sql2);
			return self::initialize($res);
	    } catch (Exception $e) {
	        return false;
	    }
  	}
}

class View
{
	public $id;
  	public $moduleId;
  	public $name;
  	public $logo;
  	public $link;

  	function __construct($id, $moduleId, $name, $logo, $link)
	{
		$this->id = $id;
		$this->moduleId = $moduleId;		
		$this->name = $name;
		$this->logo = $logo;
		$this->link = $link;
	}

	public static function Create($moduleId, $name, $logo, $link)
    {
      try {
		$sql = 'INSERT IGNORE INTO views (module_id, name, logo, link) 
		VALUES ('.$moduleId.', "'.$name.'", "'.$logo.'", "'.$link.'")';
		DatabaseHandler::Execute($sql);

		$sql = 'SELECT * FROM views WHERE module_id = '.$moduleId.' AND link = "'.$link.'"';
		$res =  DatabaseHandler::GetRow($sql);        
        return self::initialize($res);
      } catch (Exception $e) {
        return false;
      }

    }

    public static function GetModuleViews($mid)
    {
      	try {
        	$sql = 'SELECT * FROM views WHERE module_id = '.$mid.' AND status = 1';
			$res =  DatabaseHandler::GetAll($sql);
			$activities = array();
        	foreach ($res as $act) {
        		$activities[] = self::initialize($act);
        	}                
        	return $activities;

      	} catch (Exception $e) {
        
      	}

    }

    public static function GetView($id)
    {
      	try {
        	$sql = 'SELECT * FROM views WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);             
        	return self::initialize($res);
      	} catch (Exception $e) {
        
      	}
    }

    public static function GetViews($viewids)
    {
      	$views = [];
      	foreach ($viewids as $viewid) {
      		$views[] = self::GetView(intval($viewid));
      	}

      	return $views;
    }

    private static function initialize($args)
  	{
     	$view = new View($args['id'], $args['module_id'], $args['name'], $args['logo'], $args['link']);
      	return $view;
  	}
}

class Module
{
  	public $id;
  	public $name;
  	public $logo;
	public $views = [];

  	function __construct($id, $name, $logo)
	{
		$this->id = $id;
		$this->name = $name;		
		$this->logo = $logo;
		$this->views = View::GetModuleViews($this->id);
	}

  	private static function initialize($args)
  	{
     	$module = new Module($args['id'], $args['name'], $args['logo']);      	
      	return $module;
  	}

  	public function addView(View $view)
	{
		$this->views[] = $view;
	}

	public static function GetModules()
  	{      	
  		$sql = 'SELECT * FROM modules';
        $res =  DatabaseHandler::GetAll($sql);
        $modules = [];
        foreach ($res as $module) {
          $modules[] = self::initialize($module);
        }
        return $modules;
  	}

	public static function GetModule($id)
  	{      	
  		try {
  			$sql = 'SELECT * FROM modules WHERE id = '.$id;
	        $res =  DatabaseHandler::GetRow($sql);        
	        return self::initialize($res);
  		} catch (Exception $e) {
  			return false;
  		}
  	}

  	public static function Create($name, $logo)
    {
      try {
        $sql = 'INSERT INTO modules (name, logo) 
        VALUES ("'.$name.'", "'.$logo.'")';
        DatabaseHandler::Execute($sql);

        $sql = 'SELECT * FROM modules WHERE name = "'.$name.'"';
        $res =  DatabaseHandler::GetRow($sql);        
        return self::initialize($res);
      } catch (Exception $e) {
        return false;
      }
    }

	public static function Delete($id)
	{
		try {
			$sql = 'DELETE FROM modules WHERE id = '.$id;			
			DatabaseHandler::Execute($sql);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}

class User
{
  	public $id;
  	public $record;
  	public $username;
  	public $role;
  	public $access;
  	public $authorized = false;

  	function __construct($id, $uname, $role, $category, $pid, $access)
	{
		$this->id = $id;
		$this->username = $uname;		
		$this->access = $access;
		$this->role = Role::GetRole(intval($role));
		if ($category == "Employee") {
			$this->record = Employee::GetEmployee($pid);
		}

		if ($category == "Vendor") {
			$this->record = Vendor::GetVendor();
		}
		
	}

  	private static function initialize($args)
  	{
     	$user = new User($args['id'], $args['username'], $args['role_id'], $args['category'], $args['party_id'] , $args['access']);      	
      	return $user;
  	}

  	public function authorize()
	{
		$this->authorized = true;
	}

	public function deauthorize()
	{
		$this->authorized = false;
	}

	public static function Login($username, $password)
	{
		// Build SQL query
		//$sql = 'CALL blog_get_comments_list(:blog_id)';
		$sql = 'SELECT id FROM users WHERE username = "'.$username.'" AND password = sha1("'.$password.'") AND access = 1';
		// Execute the query and return the results
		$id = DatabaseHandler::GetOne($sql);

		if ($id) {
			$user = self::GetUser($id);
			$user->authorize();
			//SessionManager::EndSession();
			if (SessionManager::StartSession($user)) {
				return SessionManager::GetSession();
			}
		}else{
			return false;
		}
	}

	public static function GetUsers()
  	{      	
  		$sql = 'SELECT * FROM users WHERE username IS NOT NULL';
        $res =  DatabaseHandler::GetAll($sql);
        $users = [];
        foreach ($res as $user) {
          $users[] = self::initialize($user);
        }
        return $users;
  	}

	public static function GetUser($id)
  	{      	
  		try {
  			$sql = 'SELECT * FROM users WHERE id = '.$id.' AND access = 1';
	        $res =  DatabaseHandler::GetRow($sql);        
	        return self::initialize($res);
  		} catch (Exception $e) {
  			return false;
  		}
  	}

  	public static function Create($pid, $username, $password, $role)
    {
      	try {
      		$sql = 'INSERT INTO users (username, password, category, party_id, role_id, access) VALUES ("'.$username.'", sha1("'.$password.'"), "Employee", '.$pid.', '.$role.', 1)';
	        //$sql = 'UPDATE users SET username = "'.$username.'", password = sha1("'.$password.'"), role_id = '.$role.', access = 1 WHERE id = '.$pid;
	        DatabaseHandler::Execute($sql);

	        Logger::Log('User - '.$username.' created from the employees category');
	        
	        $sql2 = 'SELECT * FROM users WHERE party_id = '.$pid.' AND category = "Employee"';
			// Execute the query and return the results
			$res =  DatabaseHandler::GetRow($sql2);
			return self::initialize($res);
	    } catch (Exception $e) {
	        return false;
	    }
    }

    public static function Update($id, $uname, $role, $access)
	{
		if ($id != 1) {
			try {
				$sql = 'UPDATE users SET username = "'.$uname.'", role_id = '.$role.', access = '.$access.' WHERE id = '.$id;
		        DatabaseHandler::Execute($sql);
				return true;
			} catch (Exception $e) {
				return false;
			}
		}else{
			return false;
		}
		
	}

	public static function Remove($id)
	{
		if ($id != 1) {
			try {
				$sql = 'DELETE FROM roles WHERE id = '.$id;
				//$sql = 'UPDATE users SET username = NULL, signature = NULL, access = 0 WHERE id = '.$id;
		        DatabaseHandler::Execute($sql);
				return true;
			} catch (Exception $e) {
				return false;
			}
		}else{

		}
		
	}
}

class Role
{
  	public $id;
  	public $name;
  	//public $views = [];
  	public $presentation;
  	public $operations = [];

  	function __construct($id, $name, $views, $operations)
	{
		$this->id = $id;
		$this->name = $name;		
		//$this->views = View::GetViews(explode(',', $views));
		$this->operations = explode(',', $operations);
		$this->prepareViews($views);
	}

	private function prepareViews($views)
  	{
     	$views = View::GetViews(explode(',', $views));
     	$hierarchy = [];//some sort of hierarchical sparse array, thank God it worked.
     	foreach ($views as $view) {
     		$hierarchy[$view->moduleId]['views'][] = $view;
     	}

     	foreach ($hierarchy as $key => $section) {
     		$module = Module::GetModule(intval($key));
     		$hierarchy[$key]['name'] = $module->name;
     		$hierarchy[$key]['logo'] = $module->logo;
     	}

     	$this->presentation = $hierarchy;
  	}

  	private static function initialize($args)
  	{
     	$role = new Role($args['id'], $args['name'], $args['views'], $args['operations']);      	
      	return $role;
  	}

	public static function GetRoles()
  	{      	
  		$sql = 'SELECT * FROM roles';
        $res =  DatabaseHandler::GetAll($sql);
        $users = [];
        foreach ($res as $user) {
          $users[] = self::initialize($user);
        }
        return $users;
  	}

	public static function GetRole($id)
  	{      	
  		try {
  			$sql = 'SELECT * FROM roles WHERE id = '.$id;
	        $res =  DatabaseHandler::GetRow($sql);      
	        return self::initialize($res);
  		} catch (Exception $e) {
  			return false;
  		}
  	}

  	public static function Create($name, $views)
    {
      	try {
      		$sql = 'INSERT INTO roles (name, views) VALUES ("'.$name.'", "'.implode(',', $views).'")';
			DatabaseHandler::Execute($sql);
	        
	        $sql2 = 'SELECT * FROM roles WHERE name = "'.$name.'" AND views = "'.implode(',', $views).'"';
			// Execute the query and return the results
			$res =  DatabaseHandler::GetRow($sql2);
			return self::initialize($res);
	    } catch (Exception $e) {
	        return false;
	    }
    }

    public static function Update($id, $name, $views)
	{
		try {
			$sql = 'UPDATE roles SET name = "'.$name.'", views = "'.implode(',', $views).'" WHERE id = '.$id;
	        DatabaseHandler::Execute($sql);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public static function Delete($id)
	{
		if ($id != 1) {
			try {
				$sql = 'DELETE FROM roles WHERE id = '.$id;
		        DatabaseHandler::Execute($sql);
				return true;
			} catch (Exception $e) {
				return false;
			}
		}else{
			return false;
		}		
	}
}

?>


