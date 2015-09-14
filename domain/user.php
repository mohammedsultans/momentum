<?php
	// Business tier class for reading journal information
	//require_once '../include/config.php';
  	require_once DATA_DIR . 'error_handler.php';
  	ErrorHandler::SetHandler();
  	require_once DATA_DIR . 'database_handler.php';

	class User
	{

		public static function CreateUser($name, $email, $password)
		{
			//$date = date("Y-m-d H:i:s");
			//date('Y-m-d H:i:s', mktime(date("H"), date("i"), date("s"), date("m") + 3, date("d"), date("Y"));
			
			/*
				subscription status:
				1. 0 -- expired
				2. 1 -- subscribed
				3. 2 -- evaluation 

				standing order
				4. 1 -- renew_on_expiry
				5. 0 -- cancel_on_expiry

				*/
			$res = self::GetUser($email);

			if (empty($res)) {
				//user does not exists
				$today = new DateTime();
				$today = $today->format('Y-m-d');
				$date = new DateTime();
				$date->modify('+3 months');
				$date = $date->format('Y-m-d');
				// Build SQL query
				$sql = 'INSERT INTO users (email, username, password, package, subscription_status, subscription_date, subscription_expiry) 
						VALUES ("'.$email.'", "'.$name.'", sha1("'.$password.'"), "Evaluation", 2, "'.$today.'", "'.$date.'")';

				// Execute the query and return the results
				DatabaseHandler::Execute($sql);

			  	return 1;
			}else{
				return 0;
			}

			//
		}

		public static function SubscribeUser($email, $password)
		{
			//FIRST: Perform some logic to ensure that the user has paid the subscription fee before this function is called

			$sqlone = 'SELECT * FROM users WHERE email = "'.$email.'" AND password = sha1("'.$password.'")';

			$res = DatabaseHandler::GetRow($sqlone);

			if ($res['email'] == $email) {
				$today = new DateTime();
				$expiry = new DateTime($res['subscription_expiry']);
				$date = null;
				if ($expiry <= $today) {
					$today->modify('+1 year');
					$date = $today->format('Y-m-d');
				}else {
					$expiry->modify('+1 year');
					$date = $expiry->format('Y-m-d');
				}

				$sqltwo = "UPDATE users SET subscription_status = 1, subscription_date = '". new DateTime() ."' subscription_expiry = '".$date."' WHERE email = '".$email."'";
					
				// Execute the query and return the results
				return DatabaseHandler::Execute($sqltwo);
				
			}
		}

		public static function GetUser($email)
		{
			// Build SQL query
			//$sql = 'CALL blog_get_comments_list(:blog_id)';
			$sql = 'SELECT * FROM users WHERE email = "'.$email.'"';

			//return $sql;

			//$params = array(':blog_id' => $blogId);
			// Execute the query and return the results
			return DatabaseHandler::GetRow($sql);
			//return DatabaseHandler::Execute($sql);
		}

		public static function GetSubscribers()
		{
			// Build SQL query
			//$sql = 'CALL blog_get_comments_list(:blog_id)';
			$sql = 'SELECT user_id, username, email, oauth_provider, package, subscription_status, subscription_expiry, paymode, standing_order, status FROM users ORDER BY username ASC LIMIT 0,30';

			//return $sql;

			//$params = array(':blog_id' => $blogId);
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
			//return DatabaseHandler::Execute($sql);
		}

		public static function GetSubscriber($id)
		{
			// Build SQL query
			//$sql = 'CALL blog_get_comments_list(:blog_id)';
			$sql = 'SELECT user_id, username, email, oauth_provider, package, subscription_status, subscription_expiry, paymode, standing_order, status FROM users WHERE user_id = '.$id;

			//return $sql;

			//$params = array(':blog_id' => $blogId);
			// Execute the query and return the results
			return DatabaseHandler::GetRow($sql);
			//return DatabaseHandler::Execute($sql);
		}

		public static function SearchSubscribers($name)
		{
			// Build SQL query
			//$sql = 'CALL blog_get_comments_list(:blog_id)';
			$sql = 'SELECT user_id, username, email, oauth_provider, package, subscription_status, subscription_expiry, paymode, standing_order, status FROM users WHERE username LIKE "%'.$name.'%" ORDER BY username ASC LIMIT 0,30';

			//return $sql;

			//$params = array(':blog_id' => $blogId);
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
			//return DatabaseHandler::Execute($sql);
		}

		public static function FilterSubscribers($key)
		{
			if ($key == 0) {
				return self::GetSubscribers();
			}elseif ($key == 6) {
				$sql = 'SELECT user_id, username, email, oauth_provider, package, subscription_status, subscription_expiry, paymode, standing_order, status FROM users WHERE status = 1 ORDER BY username ASC LIMIT 0,30';		
				return DatabaseHandler::GetAll($sql);
			}elseif ($key == 7) {
				$sql = 'SELECT user_id, username, email, oauth_provider, package, subscription_status, subscription_expiry, paymode, standing_order, status FROM users WHERE status = 0 ORDER BY username ASC LIMIT 0,30';		
				return DatabaseHandler::GetAll($sql);
			}else{
				$sql = 'SELECT user_id, username, email, oauth_provider, package, subscription_status, subscription_expiry, paymode, standing_order, status FROM users WHERE subscription_status = '.$key.' ORDER BY username ASC LIMIT 0,30';		
				return DatabaseHandler::GetAll($sql);
			}
		}

		public static function UpdateSubscriber($id, $name, $email, $expiry, $status)
		{
			// Build SQL query
			//$sql = 'CALL blog_get_comments_list(:blog_id)';
			$sql = 'SELECT * FROM users WHERE user_id = '.$id;
			// Execute the query and return the results
			$res = DatabaseHandler::GetRow($sql);

			if ($res) {
				$sqltwo = "UPDATE users SET username = '".$name."', email = '".$email."', subscription_expiry = '".$expiry."',  status = '".$status."' WHERE user_id = '".$id."'";					
				// Execute the query and return the results
				DatabaseHandler::Execute($sqltwo);
				return 1;
			}else{
				return 0;
			}
		}

		public static function DeleteSubscriber($id)
		{

			$sqld = 'DELETE FROM users WHERE user_id = '.$id;
			DatabaseHandler::Execute($sqld);
		}

		public static function GetNewsletterSubscription($email)
		{
			// Build SQL query
			//$sql = 'CALL blog_get_comments_list(:blog_id)';
			$sql = 'SELECT * FROM newsletter WHERE email = "'.$email.'"';

			//return $sql;

			//$params = array(':blog_id' => $blogId);
			// Execute the query and return the results
			return DatabaseHandler::GetRow($sql);
			//return DatabaseHandler::Execute($sql);
		}

		public static function GetUserId($email)
		{
			// Build SQL query
			//$sql = 'CALL blog_get_comments_list(:blog_id)';
			$sql = 'SELECT user_id FROM users WHERE email = "'.$email.'"';

			//return $sql;

			//$params = array(':blog_id' => $blogId);
			// Execute the query and return the results
			return DatabaseHandler::GetOne($sql);
			//return DatabaseHandler::Execute($sql);
		}

		public static function GetUserIdOauth($id)
		{
			// Build SQL query
			//$sql = 'CALL blog_get_comments_list(:blog_id)';
			$sql = 'SELECT user_id FROM users WHERE oauth_uid = "'.$id.'"';

			//return $sql;

			//$params = array(':blog_id' => $blogId);
			// Execute the query and return the results
			return DatabaseHandler::GetOne($sql);
			//return DatabaseHandler::Execute($sql);
		}



		public static function AuthorizeUser($email, $password)
		{
			// Build SQL query
			//$sql = 'CALL blog_get_comments_list(:blog_id)';
			$sql = 'SELECT email, username, subscription_expiry, status FROM users WHERE email = "'.$email.'" AND password = sha1("'.$password.'")';

			// Execute the query and return the results
			$res = DatabaseHandler::GetRow($sql);

			if ($res) {
				$today = new DateTime();
				$expiry = new DateTime($res['subscription_expiry']);
				if ($expiry >= $today && $res['status'] == 1 ) {
					return $res;
				}else {
					return false;
				}
			}else{
				return false;
			}
		}

		public static function CheckUser($uid, $oauth_provider, $username,$email,$twitter_otoken,$twitter_otoken_secret) 
		{       

	        //$query = mysql_query("SELECT * FROM `users` WHERE oauth_uid = '$uid' and oauth_provider = '$oauth_provider'") or die(mysql_error());
	        //$result = mysql_fetch_array($query);
	        $sql = 'SELECT * FROM users WHERE oauth_uid = "'.$uid.'" AND oauth_provider = "'.$oauth_provider.'"';
			// Execute the query and return the results
			$result = DatabaseHandler::GetRow($sql);

	        if (!empty($result)) {
	            # User is already present
	        } else {
	            #user not present. Insert a new Record
		        $date = new DateTime();
		        $date->modify('+3 months');
		        $date = $date->format('Y-m-d');

		        $sqll = 'INSERT INTO users (oauth_provider, oauth_uid, username, email, package, subscription_status, subscription_expiry) VALUES ("'.$oauth_provider.'", '.$uid.', "'.$username.'", "'.$email.'", "Evaluation", 2, "'.$date.'")';
				DatabaseHandler::Execute($sqll);
				
	            $sqlx = 'SELECT * FROM users WHERE oauth_uid = "'.$uid.'" AND oauth_provider = "'.$oauth_provider.'"';
				// Execute the query and return the results
				$res = DatabaseHandler::GetRow($sqlx);
	            return $res;
	        }
	        return $result;
	    }

		public static function ChangePassword($email, $password, $newpassword)
		{
			// Build SQL query
			//$sql = 'CALL blog_get_comments_list(:blog_id)';
			$sql = 'SELECT * FROM users WHERE email = "'.$email.'" AND password = sha1("'.$password.'")';
			// Execute the query and return the results
			$res = DatabaseHandler::GetRow($sql);

			if ($res) {
				$sqltwo = "UPDATE users SET password = sha1('".$newpassword."') WHERE email = '".$email."'";					
				// Execute the query and return the results
				DatabaseHandler::Execute($sqltwo);
			}
		}

		public static function GetAllUsers()
		{
			// Build SQL query
			//$sql = 'CALL blog_get_comments_list(:blog_id)';
			$sql = 'SELECT * FROM users';

			//return $sql;

			//$params = array(':blog_id' => $blogId);
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
			//return DatabaseHandler::Execute($sql);
		}

		public static function RecoverPassword($email)
		{
			// Confirm existence of user
			$res = self::GetUser($email);

			if (!empty($res)) {
				$admin_email = "admin@lightning.com";
			  	$subject = "Password Recovery";

			  	//$newpass = RAND(0,10).RAND('A','z').RAND(0,10).RAND('A','z').'#'.RAND(0,10).RAND('A','z');
			  	$newpass = substr(md5(time()), 0, 10);

			  	$message = "Hello ".$res['username'].",\n\n Your new password is: " . $newpass;

			  	$sqlu = "UPDATE users SET password = sha1('".$newpass."') WHERE email = '".$email."'";
			  	DatabaseHandler::Execute($sqlu);			  
			  	//send email
			  	mail($email, $subject, $message, "From:" . $admin_email);

			  	return 1;
			}else{
				return 0;
			}
		}

		public static function UpdateSubscription($email, $journalIds)//an array of journal ids
		{
			$userId = self::GetUserId($email);

			$sqld = 'DELETE FROM user_journal WHERE user_id = '.$userId;
			DatabaseHandler::Execute($sqld);

			for ($i=0; $i < count($journalIds) ; $i++) { 
				$sql = 'INSERT INTO user_journal (user_id, journal_id) VALUES ('.$userId.', '.$journalIds[$i].')';
				// Execute the query and return the results
				DatabaseHandler::Execute($sql);
			}
		}

		public static function GetUserDetails($email)//an array of journal ids
		{
			$sql = 'SELECT user_id, email, username, package, subscription_expiry, paymode, standing_order FROM users WHERE email = "'.$email.'"';
			// Execute the query and return the results
			return DatabaseHandler::GetRow($sql);
		}

		public static function GetUserDetailsOauth($id)//an array of journal ids
		{
			$sql = 'SELECT user_id, email, username, package, subscription_expiry, paymode, standing_order FROM users WHERE oauth_uid = "'.$id.'"';
			// Execute the query and return the results
			return DatabaseHandler::GetRow($sql);
		}

		public static function GetPreferredJournals($email)//an array of journal ids
		{
			
			$userId = self::GetUserId($email);

			$sql = 'SELECT journal_id FROM user_journal WHERE user_id = '.$userId;

			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
		}

		

		public static function GetPreferredJournalsOauth($id)//an array of journal ids
		{
			
			$userId = self::GetUserIdOauth($id);

			$sql = 'SELECT journal_id FROM user_journal WHERE user_id = '.$userId;

			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
		}

		public static function SetPreferredJournals($userId, $prefs)//an array of journal ids
		{
			
			//$sqlm = 'SELECT COUNT(*) FROM journals';
			//$count = DatabaseHandler::GetOne($sqlm);

			$sqld = 'DELETE FROM user_journal WHERE user_id = '.$userId;
			DatabaseHandler::Execute($sqld);

			//$count = count($prefs);
			foreach($prefs as $key=>$value){
			    $sql = 'INSERT INTO user_journal (user_id, journal_id) 
					VALUES ('.$userId.', '.$key.')';

				DatabaseHandler::Execute($sql);
			}

			/*for ($i=0; $i < $count ; $i++) { 
				if (isset($prefs[$i])){
					$sql = 'INSERT INTO user_journal (user_id, journal_id) 
					VALUES ('.$userId.', '.$i.')';

					// Execute the query and return the results
					DatabaseHandler::Execute($sql);
				}
				
			}*/
		}

		public static function SubscribeNewsletter($name, $email) {
			

			$res = self::GetNewsletterSubscription($email);

			if (empty($res)) {
				$sql = 'INSERT INTO newsletter (email, name) VALUES ("'.$email.'", "'.$name.'")';
				// Execute the query and return the results
				DatabaseHandler::Execute($sql);
			  	return 1;
			}else{
				return 0;
			}
		}

	}

	//$response = new User();
	//echo $response::CreateUser('Alex Mbaka', 'alex@qet.co.ke', 'andromeda');
	//echo $response::SubscribeUser('alex@qet.co.ke', 'andromeda');
	//echo json_encode($response::AuthorizeUser('alex@qet.co.ke', 'andromeda'));
	//echo json_encode($response::GetUserId('alex@qet.co.ke'));
	//echo json_encode($response::Jou('alex@qet.co.ke'));
	//DatabaseHandler::Close();
?>