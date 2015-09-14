<?php
session_start();
require_once('Inventory.php');
require_once('Accountability.php');
require_once('Accounting.php');
require_once('Party.php');
require_once('PaymentMethod.php');

class ShoppingSession 
{
	function __construct()
	{
		$_SESSION['shopping_cart'] = new ShoppingCart();
	}

	public static function AddToCart(OrderLine $orderItem)
  	{
      	if (!isset($_SESSION['shopping_cart'])){
      		new ShoppingSession();
	  	}
	  	$cart = $_SESSION['shopping_cart'];
	  	$cart->order->addToOrder($orderItem);
	  	$_SESSION['shopping_cart'] = $cart;

	  	//isset($_COOKIE['cookie_key']
	  	//setcookie("cookie_key", $email, time()+1209600, "/");		
  	}

  	public static function CompleteSession()
  	{
      	if (!isset($_SESSION['shopping_cart'])){
	       	return false;
	  	}else{
	  		$cart = $_SESSION['shopping_cart'];
	  		unset($_SESSION['shopping_cart']);
	  		return $cart;
	  	}
  	}

  	public static function GetOrderId()
  	{
      	if (!isset($_SESSION['shopping_cart'])){
	       	new ShoppingSession();
	  	}	  	
	  	$cart = $_SESSION['shopping_cart'];
	  	return $cart->order->id;
  	}
}

class ShoppingCart extends Artifact
{
 	public $datetimeCreated;
 	public $order;

 	function __construct()
 	{
 		$datetime = new DateTime();
		$this->datetimeCreated = $datetime->format('Y/m/d H:i a');
 		$this->order = Order::CreateEmptyOrder();
 	}

 	public function addToOrder(OrderLine $orderItem)
  	{
      	$this->order->addToOrder($orderItem);
  	}

 	public function persist()
  	{
      	try {

			$sql = 'INSERT INTO Shopping_carts (order_id, date_created, status) 
			VALUES ('.$this->order->id.', "'.$this->datetimeCreated.'", 1)';
		 	DatabaseHandler::Execute($sql);

		 	$sql = 'SELECT id FROM Shopping_carts WHERE order_id = '.$this->order->id;
			$res =  DatabaseHandler::GetOne($sql);
			$this->id = $res;

		} catch (Exception $e) {
				
		}    	 	
  	}
}

class Order
{
	public $id;
	//public $saleId;
	public $dateReceived;
	//public $isPrepaid;
	public $lineItems = array();
	public $vat;
	public $amount;
	public $discount;
	public $status;
	public $recepientId;//PartyID
	public $freightCost;//Money

	function __construct($orderId, $dateReceived, $status)
	{
		$this->id = $orderId;
		$this->dateReceived = $dateReceived;
		$this->status = $status;
	}

	public function initializeOrder($amount, $discount, $vat, $freight, $status)
	{
		$this->amount = $amount;
		$this->discount = $discount;
		$this->vat = $vat;
		$this->freightCost = $freight;
		$this->status = $status;
		$this->lineItems = OrderLine::GetOrderItems($this->id);
	}

	public function initRecipient($partyId)
	{
		$this->recepientId = $partyId;
	}


	public function addToOrder(OrderLine $orderItem)
	{
		array_push($this->lineItems, $orderItem);
		//$this->lineItems[] = $orderItem;
	}

	public function removeFromOrder($lineId)
	{

	}

	public function authorize()
	{
		$vat = 0.00;
		$freight = 0.00;
		$discount = 0.00;
		$amount = 0.00;
		$items = 0;

		foreach ($this->lineItems as $orderLine) {
			$lineItemAmount = ($orderLine->quantity * $orderLine->unitPrice) - $orderLine->discount;
			$amount = $amount + $lineItemAmount;
			$vat = $vat + $orderLine->vat;
			$discount = $discount + $orderLine->discount;
			$items = $items + $orderLine->quantity;
		}

		//$vatableAmount = $amount - $vat;

		try {
			//status: 0 - unauthorized, 1 - awaiting shipment, 3 - dispatched, 4 - delivered
			$sql = 'UPDATE orders SET items = '.$items.', amount = '.$amount.', vat = '.$vat.', discount = '.$discount.', freight = '.$freight.', status = 1 WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);
	 		$this->initializeOrder($amount, $discount, $vat, $freight, 1);
	 		//$this->initRecipient($partyId);
		} catch (Exception $e) {
			
		}
	}

	public function dispatch()
	{
		# status = shipped
	}

	public function close()
	{
		# code...
	}

	public function changeStatus()
	{
		# code...
	}

	public function addRecipient($partyId)
	{
		try {
			//status - 0 - awaiting shipment, 1 - dispatched, 2 - delivered
			$sql = 'UPDATE orders SET recepient_id = '.$partyId.' WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);
	 		$this->initRecipient($partyId);
		} catch (Exception $e) {
			
		}
	}

	public static function GetOrder($id)
	{
		$sql = 'SELECT * FROM orders WHERE id = '.$id;
		$res =  DatabaseHandler::GetRow($sql);
		$order = new Order($res['id'], $res['date_received'], $res['status']);
		$order->initializeOrder($res['amount'], $res['discount'], $res['vat'], $res['freight'], $res['status']);
		$order->initRecipient($res['recepient_id']);
		return $order;
	}

	public static function CreateEmptyOrder()
	{
		//Called and stored in a session object
		try {
			
			$datetime = new DateTime();
			$sql = 'INSERT INTO orders (date_received, stamp, status) VALUES ("'.$datetime->format('Y/m/d').'", '.$datetime->format('YmdHis').', 0)';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql = 'SELECT * FROM orders WHERE stamp = '.$datetime->format('YmdHis');
			$res =  DatabaseHandler::GetRow($sql);

			return new Order($res['id'], $res['date_received'], $res['status']);

		} catch (Exception $e) {
			
		}
	}
}

class OrderLine
{
	public $lineId;
	public $orderId;
	public $itemId;
	public $itemName;
	public $quantity;//number + unitofmeasure e.g 1 unit, 6 parts etc
	public $vat;
	public $discount;
	public $unitPrice;//Money class - price per part
	public $unitCost;
	public $isAvailable = 0;//is available 1/0

	function __construct($orderId, $itemId, $itemName, $quantity, $vat, $unitPrice, $unitCost, $discount)
	{
		$this->orderId = $orderId;
		$this->itemId = $itemId;
		$this->itemName = $itemName;
		$this->quantity = intval($quantity);
		$this->vat = floatval($vat);
		$this->discount = floatval($discount);
		$this->unitPrice = floatval($unitPrice);
		$this->unitCost = floatval($unitCost);
		//$var = '37152548';number_format($var / 100, 2, ".", "") == 371525.48 ;
	}

	public function initId($id)
  	{
      	$this->lineId = $id;  		
  	}

	public static function Create($orderId, $itemId, $itemName, $quantity, $vatrate, $unitPrice, $unitCost, $discount)
	{
		//check whether available and make necessary inventory deductions, then
		$vat = (intval($vatrate)/(intval($vatrate) + 100)) * (intval($quantity) * floatval($unitPrice));
		$discount = floatval($discount);
		$lineItem = new OrderLine($orderId, $itemId, $itemName, $quantity, $vat, $unitPrice, $unitCost, $discount);
		
		try {

			$sql = 'SELECT * FROM stock_accounts WHERE resource_id = '.$itemId;
			$res =  DatabaseHandler::GetRow($sql);

			if ($res['stock_bal'] >= intval($quantity)) {
				$lineItem->setAvailabile();
				$sql = 'UPDATE stock_accounts SET stock_bal = '.(intval($res['stock_bal']) - intval($quantity)).' WHERE resource_id = '.$itemId;
        		DatabaseHandler::Execute($sql);
			}else{

			}

			$lineItem->save();
			return $lineItem;

		} catch (Exception $e) {
			
		}		
	}

	public static function GetOrderItems($orderId)
	{
		//check whether available and make necessary inventory deductions, then
		$lineItems = array();
		try {
			$sql = 'SELECT * FROM order_items WHERE order_id = '.$orderId;
			$res =  DatabaseHandler::GetAll($sql);

			foreach ($res as $item) {
				$lineItem = new OrderLine($item['order_id'], $item['item_id'], $item['item_name'], $item['quantity'], $item['vat'], $item['unit_price'], $item['unit_cost'], $item['discount'], $item['status']);
				$lineItem->initId($item['id']);
				$lineItems[] = $lineItem;
			}

			return $lineItems;
		} catch (Exception $e) {
			
		}
	}

	public function setAvailabile()
  	{
      	$this->isAvailable = 1;  		
  	}

  	public function setUnavailabile()
  	{
      	$this->isAvailable = 0;  		
  	}

	public function save()
  	{
      	try {
      		$datetime = new DateTime();
 			$stamp = $datetime->format('YmdHis');
      		$sql = 'INSERT INTO order_items (order_id, item_id, item_name, quantity, vat, unit_price, unit_cost, discount, status, stamp) 
      		VALUES ('.$this->orderId.', '.$this->itemId.', "'.$this->itemName.'", '.$this->quantity.', '.$this->vat.', '.$this->unitPrice.', '.$this->unitCost.', '.$this->discount.', '.$this->isAvailable.', '.$stamp.')';
	 		DatabaseHandler::Execute($sql);
	 		//get lineId???? and set to object

      	} catch (Exception $e) {
      		
      	}
  	}
}

class Receipt extends Artifact
{
	public $id;
	public $saleId;
	public $orderId;
	public $invoiceId;
	public $transactionId;
	public $amount;
	public $tendered;
	public $date;
	public $stamp;
	public $description;

	function __construct($invoice, $payment)
	{		
		$this->invoiceId = $invoice->id;
		$this->orderId = $invoice->orderId;
		$this->transactionId = $payment->transactionId;
		$this->amount = $payment->amount->amount;
		$this->tendered = $payment->amount->amount;
		$this->description = $payment->description;
		$this->date = $payment->date;
		$this->stamp = $payment->stamp;
		
		try {

			$sql = 'INSERT INTO receipts (invoice_id, order_id, transaction_id, amount, tendered, invoice_ratio, description, datetime, stamp) 
			VALUES ('.$this->invoiceId.', '.$this->orderId.', '.$this->transactionId.', '.$this->amount.', '.$this->tendered.', '.$payment->sufficiency.', "'.$this->description.'", "'.$this->date.'", '.$this->stamp.')';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql2 = 'SELECT * FROM receipts WHERE transaction_id = '.$this->transactionId;
			$res =  DatabaseHandler::GetOne($sql2);

			$this->id = $res;

		} catch (Exception $e) {
			
		}
	}
}

class PayPalFullPayment extends PayPal
{//A kind of process type or protocol - 5d - a kind of event????

	function __construct()
	{
		parent::__construct();//provider
		//name - electronic payment - paypal
		$this->drAccounts[] = Account::GetAccount('PayPal', 'ledgers');
		$this->drRatios[] = 1;
		$this->crAccounts[] = Account::GetAccount('Sales Revenue', 'ledgers');
		$this->crRatios[] = 1;
		//this info should originate from the database, insert ignore protocol included
		//$this->code = self::GetCode('PayPal');
		//parent::__construct();
	}
}

class PaypalPayment extends Payment
{//A payment is a financial transaction and thus a transaction
	public $sufficiency;//payment - invoice amount ratio
	public $email;
	public $reference;

	function __construct($email, $ref, Money $amount)
	{		
		$this->reference = $ref;
		$this->email = $email;
		$description = 'Payment for goods bought by '.$email;
		$paymentMethod = new PayPalFullPayment();
		parent::__construct($amount, $description, $paymentMethod);
	}

	public function processPayment()
	{
		//Communicate with paypal via API to confim payment then
		$this->commit();
	}

	public function setSufficiency($ratio)
	{
		$this->sufficiency = $ratio;
	}

	public function prepare(Invoice $invoice)
	{		
		//singleton processor for all payments with an static instance of a transaction processor
		//Transaction == $payment
		//payment is a subclass of transaction
		//since it processes payments, it generates receipts
		//sum(cr) + sum(dr) = 0

		//Evaluate sufficiency of payment
		if ($invoice->balance == $invoice->amount && $invoice->amount == $this->amount->amount) {
			$this->setSufficiency(1.00);			
			//This is the first payment
			//this is a single cash sale
			//post to income revenue and paypal/mpesa/cash
		}else if ($invoice->balance == $invoice->amount && $invoice->amount < $this->amount->amount) {
			$this->setSufficiency(1.00);
			//this is a first over this
			//post transaction to income revenue & paypal + client account(overthis)
		}else if ($invoice->balance == $invoice->amount && $invoice->amount > $this->amount->amount) {
			$this->setSufficiency(floatval($this->amount->amount/$invoice->amount));
			//this is the first partial this
			//post transaction to income revenue & paypal + accounts receivable
		}else if ($invoice->balance < $invoice->amount && $invoice->balance == $this->amount->amount) {
			$this->setSufficiency(floatval($this->amount->amount/$invoice->amount));
			//this is the last partial payment
			//post transaction to ac receivable + paypal
		}else if ($invoice->balance < $invoice->amount && $invoice->balance < $this->amount->amount) {
			$this->setSufficiency(floatval($this->amount->amount/$invoice->amount));
			//this is the last partial over payment
			//post transaction to income revenue & paypal + client account(overthis)
			//post transaction to ac receivable + paypal
		}else if ($invoice->balance < $invoice->amount && $invoice->balance > $this->amount->amount) {
			$this->setSufficiency(floatval($this->amount->amount/$invoice->amount));
			//this is a subsequent partial payment
			//post transaction to ac receivable + paypal
		}

		for ($i=0; $i < count($this->paymentMethod->drAccounts); $i++) { 
			$amount = new Money(floatval($this->amount->amount * $this->paymentMethod->drRatios[$i]), $this->amount->unit);
			$this->add(new AccountEntry($this, $this->paymentMethod->drAccounts[$i], $amount, $this->date, 'dr'));
		}

		for ($i=0; $i < count($this->paymentMethod->crAccounts); $i++) { 
			$amount = new Money(floatval($this->amount->amount * $this->paymentMethod->crRatios[$i]), $this->amount->unit);
			$this->add(new AccountEntry($this, $this->paymentMethod->crAccounts[$i], $amount, $this->date, 'cr'));
		}

		return true;

	}
}

class PaymentProcessor extends TransactionProcessor
{
	public static $transactionProcessor;
	//creates necessary transactions queues and all
	function __construct()
	{

	}

	public static function ProcessPayment($invoice, $payment)
	{
		//if busy - add to queue, if ready - add to currentTransaction then prepare and commit
		if ($payment->prepare($invoice)) {
			if ($payment->commit()) {
				return new Receipt($invoice, $payment);
			}else{
				return false;
			}			
		}else{
			return false;
		}		
	}
}

class Invoice
{
	public $id;
	public $orderId;
	public $date;
	//public $dateDue;
	public $amount;
	public $payments = [];
	public $balance;
	public $status;

	function __construct($id, $orderId, $date, $amount, $balance, $status)
	{
		$this->id = $id;
		$this->orderId = $orderId;
		$this->date = $date;
		$this->amount = floatval($amount);
		$this->balance = floatval($balance);
		$this->status = $status;
	}

	public function postPayment(Payment $payment)
	{
		//Payment is used to create account entries
		//from buyer in sale
		//post tx to sales/income revenue and paypal/mpesa/cash in hand account
		$receipt = PaymentProcessor::ProcessPayment($this, $payment);
		if ($receipt) {
			//payment has gone trough;
			#status 0 - awaiting payment, 1- partially paid, 2-overdue, 3-paid
			$this->payments[] = $payment;
			
			if ($payment->amount->amount > $this->balance) {
				//$this->balance = $this->balance - $payment->amount->amount;
				$this->balance = 0.00;
				$this->status = 3;
			} else if ($payment->amount->amount == $this->balance) {
				$this->balance = 0.00;
				$this->status = 3;
			} else {
				$this->balance = $this->balance - $payment->amount->amount;
				$this->status = 1;
			}

			$this->updateInvoice();
			return $receipt;
		}else{
			return false;
		}
	}

	public function loadPayments()
	{
		//from buyer in sale
		//- from sales get entries with this->invoiceid
	}

	private function updateInvoice()
	{
		try {

			$sql = 'UPDATE invoices SET balance = '.$this->balance.', status = '.$this->status.' WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);	 		

		} catch (Exception $e) {
			
		}
	}

	public static function CreateInvoice($order)
	{
		try {
			
			$datetime = new DateTime();
			#status 0 - awaiting payment, 1- partially paid, 2-overdue, 3-paid

			$sql = 'INSERT INTO invoices (date, order_id, amount, balance, status, stamp) VALUES ("'.$datetime->format('Y/m/d').'", '.$order->id.', '.$order->amount.', '.$order->amount.', 0, '.$datetime->format('YmdHis').')';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql2 = 'SELECT * FROM invoices WHERE order_id = '.$order->id;
			$res =  DatabaseHandler::GetRow($sql2);

			return new Invoice($res['id'], $res['order_id'], $res['date'], $res['amount'], $res['balance'], $res['status']);

		} catch (Exception $e) {
			
		}
	}

	public static function GetInvoice($id)
	{
		try {
	 		
	 		$sql = 'SELECT * FROM invoices WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);

			$invoice = new Invoice($res['id'], $res['order_id'], $res['date'], $res['amount'], $res['balance'], $res['status']);
			$invoice->loadPayments();

		} catch (Exception $e) {
			
		}
	}

	public static function GetInvoices()
	{
		try {
	 		
	 		$sql = 'SELECT * FROM invoices ORDER BY stamp DESC LIMIT 0,30';
			$res =  DatabaseHandler::GetAll($sql);

			$invoice = [];

			foreach ($res as $inv) {
				$invoice = new Invoice($inv['id'], $inv['order_id'], $inv['date'], $inv['amount'], $inv['balance'], $inv['status']);
				$invoice->loadPayments();
				$invoices[] = $invoice;
			}
			
			return $invoices;

		} catch (Exception $e) {
			
		}
	}
}

class SimpleSaleAccountability extends Accountability
{//Designed for sale agreements with one order, one invoice and one payment - refactor for flexibility
 	public static $sales = [];
 	public $buyer;
 	public $seller;
 	public $order;
 	public $invoice;
 	private $inv_status = 0;

 	function __construct()
 	{
 		$datetime = new DateTime();
		$this->datetime = $datetime->format('Y/m/d H:i a');
 		$this->startstamp = $datetime->format('YmdHis');
 		//create new order and return order id
 	}

 	public function setBuyer(Party $party)
  	{
      	$this->buyer = $party;
  	}

  	public function setSeller(Party $party)
  	{
      	$this->seller = $party;
  	}

 	public function initialize(Order $order)
  	{
      	$this->order = $order;
      	if (!empty($this->buyer) && !empty($this->seller)) {
      		parent::__construct($this->buyer, $this->seller, new ConnectedAccountabilityType('Sale Agreement'));
      		$this->type->addConnectionRule($this->parent->type, $this->child->type);
      		//save to db
      		try {
				$sql = 'INSERT INTO accountabilities (name, parent_id, child_id, datetime, startstamp, status) 
				VALUES ("'.$this->type->name.'",'.$this->parent->id.', '.$this->child->id.', "'.$this->datetime.'", '.$this->startstamp.', "Opened")';
		 		DatabaseHandler::Execute($sql);

		 		$sql = 'SELECT id FROM accountabilities WHERE startstamp = '.$this->startstamp;
				$res =  DatabaseHandler::GetOne($sql);
				$this->id = $res;

		 		$sql = 'INSERT INTO accountability_features (accountability_id, attribute, value) VALUES ('.$this->id.', "orderId", "'.$this->order->id.'")';
		 		DatabaseHandler::Execute($sql);

			} catch (Exception $e) {
				
			}
      	}      	 	
  	}

  	/*public function addToOrder(OrderLine $orderItem)
  	{
      	$this->order->addToOrder($orderItem);
  	}

  	public function addOrder(Order $order)
  	{
      	$this->order = $order;
      	try {
		 	$sql = 'SELECT id FROM accountabilities WHERE startstamp = '.$this->startstamp;
			$res =  DatabaseHandler::GetOne($sql);
			$this->id = $res;

		 	$sql = 'INSERT INTO accountability_features (accountability_id, attribute, value) VALUES ('.$this->id.', "orderId", "'.$this->order->id.'")';
		 	DatabaseHandler::Execute($sql);

		} catch (Exception $e) {
				
		}
  	}*/

 	public function generateInvoice()
  	{
      	//check if order has been authorized
      	if ($this->order->status !== 0 && $this->inv_status == 0) {
      		$this->invoice = Invoice::CreateInvoice($this->order);
      		
      		try {

		 		$sql = 'INSERT INTO accountability_features (accountability_id, attribute, value) VALUES ('.$this->id.', "invoiceId", "'.$this->invoice->id.'")';
		 		DatabaseHandler::Execute($sql);
			} catch (Exception $e) {
				
			}
			$this->inv_status = 1;
      		return true;
      	}else{
      		return false;
      	}      	
  	}

  	public function getInvoice()
  	{
      	if ($this->inv_status == 0) {
      		if ($this->generateInvoice()) {
      			return $this->invoice;
      		}else{
      			return false;
      		}     		
      	}else{
      		return $this->invoice;
      	}
  	}
}

class Catalog
{

	public static function GetCatalog()
	{
	  // test to ensure that the object from an fsockopen is valid
	  return StockInventory::GetInventory(1);
	}
  	
  	public static function OrderFromCatalog($itemId, $number, $amount)
  	{

  	}
  
}

class Customer extends Party
{//could be CorporateCustomer class
  	public $obligationsAccount;
  	public $operationsAccount;
  	public $creditRating;
  	public $accounts = array();
  	//public $stockAccountNumber;
  	function __construct($id, $name, $telephone, $email, $address, $city, $country)
	{
		$type = new PartyType('Customer');
		parent::__construct($type, $id, $name, $telephone, $email, $address, $city, $country);
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

  	public function authorizeOrder($order)
  	{
      	$order->authorize();
  	}

  	public function getInvoice($invoiceId)
  	{
      	
  		
  	}

  	public function makePayment(Invoice $invoice, Payment $payment)
  	{      	
  		$receipt = $invoice->postPayment($payment);
  		if ($receipt) {
  			return $receipt;
  		}
  		//$latestReceipt = $invoice->payments[count($invoice->payments) - 1];
  	}
 
  	public static function Get($id)
  	{
      	$sql = 'SELECT * FROM customers WHERE id = '.$id;
		// Execute the query and return the results
		$res =  DatabaseHandler::GetRow($sql);
		return self::initialize($res);
  	}

  	public static function RegisterNew($name, $telephone, $email, $address, $shippingInfo, $password)
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

  	public static function Create($name, $telephone, $email, $address, $shippingInfo)
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

  	public static function CustomerCheck($email)
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
     		$args['id'] = 65824;//use random number, more especially a uuid
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

  	public static function Authorize($email, $password)
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

class Vendor extends Party
{
	public $website;
	public $contactPerson;

	function __construct($id, $name, $telephone, $email, $address, $city, $country, $website, $contactId)
	{
		$type = new PartyType('Vendor');
		parent::__construct($type, $id, $name, $telephone, $email, $address, $city, $country);
		$this->website = $website;
		//$this->contactPerson = Party::Get($contactId);//PartyType('Account Manager');
	}

	public static function GetVendor()
	{
		return new Vendor(1, 'Pablo Gift Shop', '072255555', 'care@pablogifts.co.ke', '1234 Long Street', 'Nairobi', 'Kenya', 'www.geajo.com', 'EA098736');
	}
}
?>


