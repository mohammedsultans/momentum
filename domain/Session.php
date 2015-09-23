<?php

/*function __autoload($class_name)
    {
        //class directories
        $directorys = array(
            'classes/',
            'classes/otherclasses/',
            'classes2/',
            'module1/classes/'
        );
       
        //for each directory
        foreach($directorys as $directory)
        {
            //see if the file exsists
            if(file_exists($directory.$class_name . '.php'))
            {
                require_once($directory.$class_name . '.php');
                //only require the class once, so quit after to save effort (if you got more, then name them something else
                return;
            }           
        }
    }*/
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
 		Logger::Log(get_class($this), 'OK', $this->user->username.' logs in at '.$this->loginTime.' from terminal XXX');
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