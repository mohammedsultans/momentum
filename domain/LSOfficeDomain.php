<?php
//Author: - Alexander Maera, +254727596626, Thika.Nairobi.Kisii - Kenya.

require_once '/../include/config.php';
require_once DATA_DIR . 'error_handler.php';
ErrorHandler::SetHandler();
require_once DATA_DIR . 'database_handler.php';

date_default_timezone_set('Africa/Nairobi');

require_once('Logger.php');
//require_once('Session.php');
require_once('Party.php');
require_once('Accountability.php');
require_once('Accounting.php');
//require_once('PaymentMethod.php');
require_once('DomainCRM.php');
require_once('DomainSCM.php');
require_once('DomainPrjMgt.php');
require_once('DomainHRM.php');

class View
{
	  public $id;
  	public $moduleId;
  	public $name;
  	public $logo;
  	public $link;
  	public $position;

  	function __construct($id, $moduleId, $name, $logo, $link, $position)
  	{
  		$this->id = $id;
  		$this->moduleId = $moduleId;		
  		$this->name = $name;
  		$this->logo = $logo;
  		$this->link = $link;
  		$this->position = $position;
  	}

	  public static function Create($moduleId, $name, $logo, $link)
    {
      try {
      	$sql = 'SELECT * FROM views WHERE module_id = '.$moduleId.' ORDER BY pos DESC LIMIT 0,1';
      	$rs =  DatabaseHandler::GetRow($sql);
      	$pos = intval($rs['pos']) + 1;

		$sql = 'INSERT IGNORE INTO views (module_id, name, logo, link, pos) 
		VALUES ('.$moduleId.', "'.$name.'", "'.$logo.'", "'.$link.'",  '.$pos.')';
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
        	$sql = 'SELECT * FROM views WHERE module_id = '.$mid.' AND status = 1 ORDER BY pos ASC';
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
     	$view = new View($args['id'], $args['module_id'], $args['name'], $args['logo'], $args['link'], $args['pos']);
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
  			$this->record = SystemVendor::GetVendor();
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

    public static function GetUserByName($name)
    {       
      try {
        $sql = 'SELECT * FROM users WHERE name = "'.$name.'" AND access = 1';
        $res =  DatabaseHandler::GetRow($sql);        
        return self::initialize($res);
      } catch (Exception $e) {
        return false;
      }
    }

  	public static function Create($pid, $username, $password, $role)
    {
      	try {

          $sqla = 'SELECT * FROM users WHERE party_id = '.$pid.' AND category = "Employee"';
          // Execute the query and return the results
          $resa =  DatabaseHandler::GetRow($sqla);

          if ($resa['party_id']) {
            return false;
          }else{
            $sql = 'INSERT INTO users (username, password, category, party_id, role_id, access) VALUES ("'.$username.'", sha1("'.$password.'"), "Employee", '.$pid.', '.$role.', 1)';
            //$sql = 'UPDATE users SET username = "'.$username.'", password = sha1("'.$password.'"), role_id = '.$role.', access = 1 WHERE id = '.$pid;
            DatabaseHandler::Execute($sql);

            Logger::Log(get_class(self), 'OK', 'User created with username : '.$username.'; Party type: Employees');
            
            $sql2 = 'SELECT * FROM users WHERE party_id = '.$pid.' AND category = "Employee"';
            // Execute the query and return the results
            $res =  DatabaseHandler::GetRow($sql2);
            return self::initialize($res);
          }
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

class Dashboard
{
 	public $name;
  public $role;

 	function __construct($name)
 	{
 		$this->name = $name;
 	}
}

class DirectorsDashboard extends Dashboard
{
 	public static $todayStamp;
 	public static $lastStamp;

 	public static $updateDayStamp;
 	public static $updateTimeStamp;
 	public static $status = false;

 	public $thirtydaydata;
 	public $sevendaydata;
 	public $yesterdaydata;
 	public $todaydata;
 	public $latestProjects = [];//6
 	public $latestInvoices = [];//4
 	public $latestEnquiries = [];//4
 	public $latestMessages = [];//4

 	function __construct()
 	{
 		parent::__construct('DirectorsDashboard');
 		$datetime = new DateTime();
    self::$todayStamp = $datetime->format('Ymd');

    try {
    	$sql = 'SELECT * FROM daily_totals ORDER BY daystamp DESC LIMIT 0,1';
			$res =  DatabaseHandler::GetRow($sql);
			self::$lastStamp = intval($res['daystamp']);
    } catch (Exception $e) {
      		
    }
    $this->postUnpostedDays();
 		$this->thirtydaydata = $this->processDays(30);
 		$this->sevendaydata = $this->processDays(7);
 		$this->yesterdaydata = $this->processDays(1);
 		$this->todaydata = $this->processToday();
 		$this->processLatestProjects();
 		$this->processLatestInvoices();
 		$this->processLatestEnquiries();
 		//$this->processLatestMessages();
 		return $this;
 	}

 	private function daystamptodate($daystamp)
  {
    $arr = str_split($daystamp);
    $date = $arr[0].$arr[1].$arr[2].$arr[3].'-'.$arr[4].$arr[5].'-'.$arr[6].$arr[7];
    return DateTime::createFromFormat('Y-m-d', $date);
  }

  private function calculateTotal($stamp, $lids_query, $effect)
  {
   	$sum = 0.00;

  	try {
  		$lower = $stamp.'000000' + 0;
  	  $upper = $stamp.'999999' + 0;
  	  $sql = 'SELECT * FROM general_ledger_entries WHERE ledger_id IN("'.$lids_query.'") AND effect = "'.$effect.'" AND stamp BETWEEN '.$lower.' AND '.$upper;
  	  $res =  DatabaseHandler::GetAll($sql);
  		foreach ($res as $entry) {
  			$sum = floatval($sum + floatval($entry['amount']));
  		}
  	} catch (Exception $e) {
  				
  	}

  	return $sum;
  }

 	private function postUnpostedDays()
  {
   	$lastdate = $this->daystamptodate(self::$lastStamp);
   	$lastdate->modify('+1 day');
   	$stamp = $lastdate->format('Ymd');
   	//All revenues
   	try {
			$sql = 'SELECT * FROM ledgers WHERE type = "Revenue"';
			$res =  DatabaseHandler::GetAll($sql);
			$ids = [];
			foreach ($res as $ledger) {
				$ids[] = intval($ledger['id']);
			}
		  $rquery = implode('","', $ids);
		} catch (Exception $e) {
				
		}			
		//All expenses
		try {
			$sql = 'SELECT * FROM ledgers WHERE (type = "Expense") OR (type = "Asset" AND class ="Fixed Asset")';
			$res =  DatabaseHandler::GetAll($sql);
			$ids = [];
			foreach ($res as $ledger) {
				$ids[] = intval($ledger['id']);
		  }
		  $equery = implode('","', $ids);
		} catch (Exception $e) {
				
		}
      	
   	while ($stamp < self::$todayStamp) {//post till yesterday
   		//All revenues
   		$revenues = $this->calculateTotal($stamp, $rquery, 'cr');
			
			$expenses = $this->calculateTotal($stamp, $equery, 'dr');

  		try {
  				$sql = 'INSERT INTO daily_totals (revenues, expenses, daystamp) VALUES ('.$revenues.', '.$expenses.', '.$stamp.')';
  				DatabaseHandler::Execute($sql);
  		} catch (Exception $e) {
  				
  		}
  			
     	$lastdate->modify('+1 day');
     	$stamp = $lastdate->format('Ymd');
     	self::$lastStamp = $stamp;
   	}
  }

  private function processDays($days)
  {
    try {
	    $sql = 'SELECT * FROM daily_totals ORDER BY daystamp DESC LIMIT 0,'.intval($days);
			$res =  DatabaseHandler::GetAll($sql);
			
			$rsum = 0.00;
			$esum = 0.00;
			$revs = array();
			$exps = array();
	    foreach ($res as $entry) {
	      $rsum = floatval($rsum + floatval($entry['revenues']));
	      $esum = floatval($esum + floatval($entry['expenses']));
	      $revs[] = floatval($entry['revenues']);
	      $exps[] = floatval($entry['expenses']);
	    }

	    $sql = 'SELECT * FROM daily_totals ORDER BY daystamp DESC LIMIT '.intval($days).','.(intval($days) * 2);
			$res =  DatabaseHandler::GetAll($sql);

			$rrsum = 0.00;
			$eesum = 0.00;
	    foreach ($res as $entry) {
	      $rrsum = floatval($rrsum + floatval($entry['revenues']));
	      $eesum = floatval($eesum + floatval($entry['expenses']));
	    }

	    $a = $rsum - $esum;
	    $b = $rrsum - $eesum;

	    $obj = new stdClass();
   		$obj->rsum = $rsum;
   		$obj->esum = $esum;
   		$obj->revs = $revs;
   		$obj->exps = $exps;
   		$obj->margin = floatval($a - $b);

	    return $obj;

	  } catch (Exception $e) {
	        
	  }
  }

  private function processToday()
  {
  	$stamp = self::$todayStamp;
      	
  	if ($stamp) {
   			//All revenues
  		$query;
  		try {
				$sql = 'SELECT * FROM ledgers WHERE type = "Revenue"';
				$res =  DatabaseHandler::GetAll($sql);
				$ids = [];
				foreach ($res as $ledger) {
					$ids[] = intval($ledger['id']);
		    }
	      $query = implode('","', $ids);
			} catch (Exception $e) {
				
			}

			$revenues = 0.00;
			$revs = [];
			try {
				$lower = $stamp.'000000' + 0;
		    $upper = $stamp.'999999' + 0;
		    $sql = 'SELECT * FROM general_ledger_entries WHERE ledger_id IN("'.$query.'") AND effect = "cr" AND stamp BETWEEN '.$lower.' AND '.$upper;
				$res =  DatabaseHandler::GetAll($sql);
					
				foreach ($res as $entry) {
					$revenues = floatval($revenues + floatval($entry['amount']));
					$revs[] = $entry['amount'];
				}
			} catch (Exception $e) {
					
			}


			//All expenses
			try {
				$sql = 'SELECT * FROM ledgers WHERE (type = "Expense") OR (type = "Asset" AND class ="Fixed Asset")';
				$res =  DatabaseHandler::GetAll($sql);
				$ids = [];
				foreach ($res as $ledger) {
					$ids[] = intval($ledger['id']);
		    }
	      $query = implode('","', $ids);
			} catch (Exception $e) {
				
			};	

			$expenses = 0.00;
			$exps = [];
			try {
				$lower = $stamp.'000000' + 0;
		    $upper = $stamp.'999999' + 0;
		    $sql = 'SELECT * FROM general_ledger_entries WHERE ledger_id IN("'.$query.'") AND effect = "dr" AND stamp BETWEEN '.$lower.' AND '.$upper;
				$res =  DatabaseHandler::GetAll($sql);
					
				foreach ($res as $entry) {
					$expenses = floatval($expenses + floatval($entry['amount']));
					$exps[] = $entry['amount'];
				}
			} catch (Exception $e) {
					
			}
			
			$obj = new stdClass();
  		$obj->rsum = $revenues;
  		$obj->esum = $expenses;
  		$obj->revs = $revs;
  		$obj->exps = $exps;

  		return $obj;
  	}
  }

 	private function processLatestProjects()
	{
    try {
	    $sql = 'SELECT id FROM projects ORDER BY modified DESC LIMIT 0,6';
	    $res =  DatabaseHandler::GetAll($sql);
	    $sql2 = 'SELECT count(id) FROM projects';
	    $res2 =  DatabaseHandler::GetOne($sql2);
	    $projects = [];
	    foreach ($res as $project) {
	      $projects[] = Project::GetProject(intval($project['id']));
	    }
	    $obj = new stdClass();
   		$obj->projects = $projects;
   		$obj->total = $res2;
	    $this->latestProjects = $obj;
	  } catch (Exception $e) {
	        
	  }
  }

  private function processLatestInvoices()
  {
    try {
	    $sql = 'SELECT id FROM invoices ORDER BY stamp DESC LIMIT 0,5';
	    $res =  DatabaseHandler::GetAll($sql);
	    $sql2 = 'SELECT count(id) FROM invoices';
	    $res2 =  DatabaseHandler::GetOne($sql2);
	    $invoices = [];
	    foreach ($res as $invoice) {
	      $invoices[] = SalesVoucher::GetInvoice(intval($invoice['id']));
	    }
	    $obj = new stdClass();
   		$obj->invoices = $invoices;
   		$obj->total = $res2;
	    $this->latestInvoices = $obj;
	  } catch (Exception $e) {
	        
	  }
  }

  private function processLatestEnquiries()
  {
    try {
	    $sql = 'SELECT stamp FROM enquiries WHERE status = 0 ORDER BY stamp DESC LIMIT 0,5';
	    $res =  DatabaseHandler::GetAll($sql);
	    $sql2 = 'SELECT count(*) FROM enquiries WHERE status = 0';
	    $res2 =  DatabaseHandler::GetOne($sql2);
	    $enquiries = [];
	    foreach ($res as $enquiry) {
	      $enquiries[] = Enquiry::GetEnquiry($enquiry['stamp']);
	    }
	    $obj = new stdClass();
   		$obj->enquiries = $enquiries;
   		$obj->total = $res2;
	    $this->latestEnquiries = $obj;
	  } catch (Exception $e) {
	        
	  }
  }

  private function processLatestMessages()
  {
      	
  }
}

session_start();

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
 		Logger::Log(get_class($this), 'OK', $this->user->username.' logs in at '.$this->loginTime.' from terminal XXX and IP xxx');
  }

 	public function logout()
  {
    $datetime = new DateTime();
		$this->logoutTime = $datetime->format('Y/m/d H:i:s a');
		Logger::Log(get_class($this), 'OK', $this->user->username.' logs out at '.$this->logoutTime.' from terminal XXX');
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

	public static function StartSession($user)
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

  public static function GetUsername()
  {
    if (isset($_SESSION['session_key']) && isset($_SESSION['session_user'])){
	  	$session = $_SESSION['session_user'];
	  	return $session->user->record->name;
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

?>


