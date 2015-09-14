 <?php

 /**
 * 
 */
require_once '../include/config.php';
require_once DOMAIN_DIR . 'error_handler.php';
ErrorHandler::SetHandler();
require_once DOMAIN_DIR . 'database_handler.php';
require_once 'Party.php';

class CustomerProfile
{// CustomerAccount extends Account
	private $name;
	private $address;
	private $telephone;
	private $email;
	private $shippingInfo;

	function __construct($name, $telephone, $address, $email, $shippingInfo)
	{
		$this->name = $name;
		$this->telephone = $telephone;
		$this->address = $address;
		$this->email = $email;
		$this->shippingInfo = $shippingInfo;
	}

	public function setProfile()
  	{
      	
  		$query = self::customerCheck($this->email);

  		if (empty($query)) {
	  		$today = new DateTime();
			$today = $today->format('Y-m-d H:i:s');
			//"UPDATE customers SET password = sha1('".$password."') WHERE email = '".$this->email."'";
	      	$sql = 'INSERT INTO customers (type, name, telephone, address, email, shippingInfo) VALUES ("UnregisteredCustomer", "'.$this->name.'", "'.$this->telephone.'", "'.$this->address.'", "'.$this->email.'", "'.$this->shippingInfo.'")';
	 		DatabaseHandler::Execute($sql);

	      	$sql2 = 'SELECT * FROM customers WHERE email = "'.$this->email.'"';
			// Execute the query and return the results
			$res =  DatabaseHandler::GetRow($sql2);
			return self::initialize($res);
		}else{
  			return false;
  		}
  	}

  	public function updateProfile()
  	{
      	
  		$query = self::customerCheck($this->email);

  		if (empty($query)) {
	  		$today = new DateTime();
			$today = $today->format('Y-m-d H:i:s');

			$sqltwo = "UPDATE customers SET type = $this->email, subscription_date = '". new DateTime() ."' subscription_expiry = '".$date."' WHERE email = '".$email."'";
			// Execute the query and return the results
			return DatabaseHandler::Execute($sqltwo);
			//"UPDATE customers SET password = sha1('".$password."') WHERE email = '".$this->email."'";
	      	$sql = 'INSERT INTO customers (type, name, telephone, address, email, shippingInfo) VALUES ("UnregisteredCustomer", "'.$this->name.'", "'.$this->telephone.'", "'.$this->address.'", "'.$this->email.'", "'.$this->shippingInfo.'")';
	 		DatabaseHandler::Execute($sql);

	      	$sql2 = 'SELECT * FROM customers WHERE email = "'.$this->email.'"';
			// Execute the query and return the results
			$res =  DatabaseHandler::GetRow($sql2);
			return self::initialize($res);
		}else{
  			return false;
  		}
  	}

  	private static function customerCheck($email)
	{
		// Build SQL query
		//$sql = 'CALL blog_get_comments_list(:blog_id)';
		$sql = 'SELECT * FROM customers WHERE email = "'.$email.'"';

		return DatabaseHandler::GetRow($sql);
	}

	private static function initialize($args)
  	{
      	return new CustomerProfile($args['name'], $args['telephone'], $args['address'], $args['email'], $args['shippingInfo']);
  	}
}
 
class Customer extends Party
{
  	public $shippingaddress;
  	public $accountNumber;
  	//public $stockAccountNumber;
  	function __construct($id, $name, $telephone, $email, $address, $shipaddress)
	{
		$type = new PartyType('Customer');
		parent::__construct($type, $name, $telephone, $email, $address);
		$this->shippingaddress = $shipaddress;
	}
 
  	public static function get($id)
  	{
      	$sql = 'SELECT * FROM customers WHERE id = '.$id;
		// Execute the query and return the results
		$res =  DatabaseHandler::GetRow($sql);
		return self::initialize($res);
  	}

  	public static function registerNew($name, $telephone, $email, $address, $shippingInfo, $password)
  	{
      	
  		$query = self::customerCheck($email);

  		if (empty($query)) {
  			$today = new DateTime();
			$today = $today->format('Y-m-d H:i:s');

	      	$sql = 'INSERT INTO customers (type, name, telephone, address, email, shippingInfo, password, reg_date) VALUES ("RegisteredCustomer",'.$name.'", "'.$telephone.'", "'.$address.'", "'.$email.'", "'.$this->shippingInfo.'", sha1("'.$password.'"), "'.$today.'")';
	 		DatabaseHandler::Execute($sql);

	      	$sql2 = 'SELECT * FROM customers WHERE email = "'.$email.'"';
			// Execute the query and return the results
			$res =  DatabaseHandler::GetRow($sql2);
			return self::initialize($res);
  		}else{
  			return false;
  		}
  		
  	}

  	public function register($password)
  	{
      	
  		$query = self::customerCheck($this->email);

  		if (empty($query)) {
	  		$today = new DateTime();
			$today = $today->format('Y-m-d H:i:s');
			//"UPDATE customers SET password = sha1('".$password."') WHERE email = '".$this->email."'";
	      	$sql = 'INSERT INTO customers (type, name, telephone, address, email, shippingInfo, password, reg_date) VALUES ("RegisteredCustomer", "'.$this->name.'", "'.$this->telephone.'", "'.$this->address.'", "'.$this->email.'", "'.$this->shippingInfo.'", sha1("'.$password.'"), "'.$today.'")';
	 		DatabaseHandler::Execute($sql);

	      	$sql2 = 'SELECT * FROM customers WHERE email = "'.$this->email.'"';
			// Execute the query and return the results
			$res =  DatabaseHandler::GetRow($sql2);
			return self::initialize($res);
		}else{
  			return false;
  		}
  	}

  	public static function create($name, $telephone, $email, $address, $shippingInfo)
  	{      	
  		$party = array(
		  			"name"=>$name,
		  			"telephone"=>$telephone,
		  			"address"=>$address,
		  			"email"=>$email,
		  			"shippingInfo"=>$shippingInfo
		  		);

		  return self::initialize($party);
  	}

  	public static function customerCheck($email)
  	{
  		// Build SQL query
  		//$sql = 'CALL blog_get_comments_list(:blog_id)';
  		$sql = 'SELECT * FROM customers WHERE email = "'.$email.'"';

  		return DatabaseHandler::GetRow($sql);
  	}

  	private static function initialize($args)
  	{
     	//parent::__construct();
     	if (!isset($args['id'])) {
     		$args['id'] = 65824;
     	}else{

     	}
     	$customer = new Customer($args['id'], $args['name'], $args['telephone'], $args['email'], $args['address'], $args['shippingInfo']);

     	/*if (isset($args['id'])) {
     		$customer->id = $args['id'];
     	}     	
     	$customer->name = $args['name'];
     	$customer->telephone = $args['telephone'];
      	$customer->address = $args['address'];
      	$customer->email = $args['email'];
      	$customer->shippingInfo = $args['shippingInfo'];
      	foreach($args as $key=>$value){
      		if ($key == 'password') {
	     		$value = 'xxx-xxxx';
	     	}
			$customer->$key = $value;
		}*/
      	return $customer;
  	}

  	public static function authorize($email, $password)
	{
		// Build SQL query
		//$sql = 'CALL blog_get_comments_list(:blog_id)';
		$sql = 'SELECT id FROM customers WHERE email = "'.$email.'" AND password = sha1("'.$password.'")';
		// Execute the query and return the results
		$id = DatabaseHandler::GetOne($sql);

		if ($id) {
			//initiate global $_SESSION variables
			return self::get($id);
		}else{
			return false;
		}
	}
}
 
// Create a new object
//$newobj = new Customer('UnregisteredCustomer');
$newobj3 = Customer::create('Alex Mbaka', '0727596626', 'Apartment 602, Marafique Arcade, Thika', 'alex@qet.co.ke', 'Apartment 602, Marafique Arcade, Thika, Between 1800Hrs and 2100Hrs');
$val = $newobj3->register('password');
if ($val) {
	echo json_encode($val);
} else {
	echo 'Customer already exists. Try update';
}
//Replace all queries with procedure calls
 
?>