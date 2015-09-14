<?php
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


class Quotation
{
	public $id;
	public $date;
	public $lineItems = array();
	public $items;
	public $taxamt;
	public $amount;
	public $total;
	public $status;
	public $client;
	public $projectId;

	function __construct($quoteId, $date, $status, $client)
	{
		$this->id = $quoteId;
		$this->date = $date;
		$this->status = $status;
		$this->client = $client;
	}

	public function initializeQuote()
	{
		$this->lineItems = QuotationLine::GetQuoteItems($this->id);
		$this->generate();
	}

	public function initRecipient($partyId)
	{
		$this->clientId = $partyId;
	}

	public function initProject($projectId)
	{
		$this->projectId = $projectId;
	}


	public function addToQuote(QuotationLine $quoteItem)
	{
		array_push($this->lineItems, $quoteItem);
		//$this->lineItems[] = $orderItem;
	}

	public function removeFromQuote($lineId)
	{

	}

	public function generate()
	{
		$amount = 0.00;
		$taxamt = 0.00;
		$total = 0.00;
		$items = 0;

		foreach ($this->lineItems as $quoteLine) {
			$lineItemAmount = ($quoteLine->quantity * $quoteLine->unitPrice);
			$amount = $amount + $lineItemAmount;
			$items = $items + $quoteLine->quantity;
			$taxamt = $taxamt + ($lineItemAmount * ($quoteLine->tax/100));
		}
		//$taxamt = $amount * $tax/100;
		$total = $amount + $taxamt;
		

		try {
			//status: 0 - unauthorized, 1 - awaiting shipment, 3 - dispatched, 4 - delivered
			$sql = 'UPDATE quotations SET items = '.$items.', amount = '.$amount.', total = '.$total.', tax = '.$taxamt.' WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);
	 		$this->amount = $amount;
			$this->taxamt = $taxamt;
	 		$this->total = $total;
			$this->items = $items;
			//$this->status = 1;
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function discard()
	{
		try {
			$sql = 'DELETE FROM quotations WHERE id = '.$this->id;			
			DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			
		}
	}

	public static function CreateQuotation($client)
	{
		//Called and stored in a session object
		try {
			
			$datetime = new DateTime();
			$sql = 'INSERT INTO quotations (client_id, date, stamp, status) VALUES ("'.$client->id.'","'.$datetime->format('Y/m/d').'", '.$datetime->format('YmdHis').', 1)';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql = 'SELECT * FROM quotations WHERE stamp = '.$datetime->format('YmdHis');
			$res =  DatabaseHandler::GetRow($sql);

			return new Quotation($res['id'], $res['date'], $res['status'], $client);

		} catch (Exception $e) {
			
		}
	}

	public function setProject($projectId)
	{
		if ($this->status != 2) {
			try {
				$sql = 'UPDATE quotations SET project_id = '.$projectId.', status = 2 WHERE id = '.$this->id;
		 		DatabaseHandler::Execute($sql);
		 		$this->projectId = $projectId;
		 		$this->status = 2;
			} catch (Exception $e) {
				
			}
		}
		
	}

	public function setInvoiced()
	{
		if ($this->status != 3) {
			try {
				$sql = 'UPDATE quotations SET status = 3 WHERE id = '.$this->id;
		 		DatabaseHandler::Execute($sql);
		 		$this->status = 3;
			} catch (Exception $e) {
				
			}
		}
		
	}

	public static function GetQuotation($id)
	{
		try {
			$sql = 'SELECT * FROM quotations WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);
			if (!empty($res['date'])) {
				$client = Client::GetClient($res['client_id']);
				$quote = new Quotation($res['id'], $res['date'], $res['status'], $client);
				$quote->initializeQuote();
				if (!empty($res['project_id'])) {
					$quote->initProject($res['project_id']);
				}
				return $quote;
			}else{
				return null;
			}
			
		} catch (Exception $e) {
			return null;
		}
		
	}

	public static function GetProjectQuotations($id, $client)
	{
		try {
			$sql = 'SELECT * FROM quotations WHERE project_id = '.$id;
			$res =  DatabaseHandler::GetAll($sql);
			$quotes = [];
			foreach ($res as $item) {
				if (!empty($item['date'])) {
					$quote = new Quotation($item['id'], $item['date'], $item['status'], $client);
					$quote->initializeQuote();
					$quote->initProject($item['project_id']);
					$quotes[] = $quote;
				}else{
					
				}
			}
			
			return $quotes;
			
		} catch (Exception $e) {
			return null;
		}
		
	}

	public static function GetClientQuotations($clientid)
	{
		try {
			$client = Client::GetClient($clientid);
			$sql = 'SELECT * FROM quotations WHERE client_id = '.$clientid.' AND status = 1';
			$res =  DatabaseHandler::GetAll($sql);
			$quotes = [];
			foreach ($res as $item) {
				if (!empty($item['date'])) {
					$quote = new Quotation($item['id'], $item['date'], $item['status'], $client);
					$quote->initializeQuote();
					$quotes[] = $quote;
				}else{
					
				}
			}
			
			return $quotes;
			
		} catch (Exception $e) {
			return null;
		}
		
	}

	public static function GetGeneralQuotations($clientid)
	{
		try {
			$client = Client::GetClient($clientid);
			$sql = 'SELECT * FROM quotations WHERE client_id = '.$clientid.' AND status = 1 AND isnull(project_id)';
			$res =  DatabaseHandler::GetAll($sql);
			$quotes = [];
			foreach ($res as $item) {
				if (!empty($item['date'])) {
					$quote = new Quotation($item['id'], $item['date'], $item['status'], $client);
					$quote->initializeQuote();
					$quotes[] = $quote;
				}else{
					
				}
			}
			
			return $quotes;
			
		} catch (Exception $e) {
			return null;
		}
		
	}

	public static function Delete($id)
	{
		try {
			$sql = 'DELETE FROM quotations WHERE id = '.$id;			
			DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			
		}
	}

}

class Service
{
	
}

class OfficeService extends Service
{
	public $name;
	public $descr;
	public $unit;
	public $rate;

	function __construct($name, $task, $unit, $rate)
 	{
 		$this->name = $name;
 		$this->descr = $task;
 		$this->unit = $unit;
 		$this->rate = $rate;
 	}

	public static function Create($name, $descr, $rate)
	{
		//Called and stored in a session object
		try {

			$unit = WorkService::Get('SLP');
			//start here
			$sql = 'INSERT IGNORE INTO office_services (name, descr, unit_id, rate) VALUES ("'.$name.'", "'.$descr.'", '.$unit->unitId.', '.$rate.')';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql = 'SELECT * FROM office_services WHERE name = "'.$name.'" ';
			$res =  DatabaseHandler::GetRow($sql);

			return new OfficeService($res['name'], $res['descr'], $unit, $res['rate']);

		} catch (Exception $e) {
			
		}


	}

	public static function Get($name)
	{
		//Called and stored in a session object
		try {
	 		
	 		$sql = 'SELECT * FROM office_services WHERE name = "'.$name.'"';
			$res =  DatabaseHandler::GetRow($sql);
			$unit = WorkService::Get('SLP');//GetBySymbol -> $res['unit_id']

			return new OfficeService($res['name'], $res['descr'], $unit, $res['rate']);

		} catch (Exception $e) {
			
		}


	}

	public static function Delete()
    {
      try {
        $sql = 'DELETE FROM office_services WHERE name = "'.$name.'"';			
		DatabaseHandler::Execute($sql);
		return true;
      } catch (Exception $e) {
        return false;
      }

    }
}

class BillableService extends Service
{
	public $name;
	public $unit;

	function __construct($name, $unit)
 	{
 		$this->name = $name;
 		$this->unit = $unit;
 	}

	public static function Create($name)
	{
		//Called and stored in a session object
		try {

			$unit = WorkService::Get('BSV');
			//start here
			$sql = 'INSERT IGNORE INTO services (name, unit_id) VALUES ("'.$name.'", '.$unit->unitId.')';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql = 'SELECT * FROM services WHERE name = "'.$name.'" ';
			$res =  DatabaseHandler::GetRow($sql);

			return new BillableService($res['name'], $unit);

		} catch (Exception $e) {
			return false;
		}


	}

	public static function Get($name)
	{
		try {
	 		
	 		$sql = 'SELECT * FROM services WHERE name = "'.$name.'"';
			$res =  DatabaseHandler::GetRow($sql);
			$unit = WorkService::Get('BSV');//GetBySymbol -> $res['unit_id']

			return new BillableService($res['name'], $unit);

		} catch (Exception $e) {
			
		}
	}

	public static function GetAll()
	{
		try {
	 		
	 		$sql = 'SELECT * FROM services';
			$res =  DatabaseHandler::GetAll($sql);
			$unit = WorkService::Get('BSV');//GetBySymbol -> $res['unit_id']
			$services = [];
			foreach ($res as $service) {
				$services[] = new BillableService($service['name'], $unit);
			}
			return $services;

		} catch (Exception $e) {
			return false;
		}
	}

	public static function Delete($name)
    {
      try {
        $sql = 'DELETE FROM services WHERE name = "'.$name.'"';			
		DatabaseHandler::Execute($sql);
		return true;
      } catch (Exception $e) {
        return false;
      }

    }
}

class QuotationLine
{
	public $lineId;
	public $quoteId;
	public $itemId;
	public $itemName;
	public $itemDesc;
	public $quantity;
	public $unitPrice;//Money class - price per part
	public $tax;

	function __construct($quoteId, $itemName, $itemDesc, $quantity, $unitPrice, $tax)
	{
		$this->quoteId = $quoteId;
		//$this->itemId = $itemId;
		$this->itemName = $itemName;
		$this->itemDesc = $itemDesc;
		$this->quantity = intval($quantity);
		$this->unitPrice = floatval($unitPrice);
		$this->tax = floatval($tax);
		//$var = '37152548';number_format($var / 100, 2, ".", "") == 371525.48 ;
	}

	public function initId($id)
  	{
      	$this->lineId = $id;  		
  	}

	public static function Create($quoteId, $itemName, $itemDesc, $quantity, $unitPrice, $tax)
	{
		$lineItem = new QuotationLine($quoteId, $itemName, $itemDesc, $quantity, $unitPrice, $tax);		
		$lineItem->save();
		return $lineItem;
	}

	public static function GetQuoteItems($quoteId)
	{
		//check whether available and make necessary inventory deductions, then
		$lineItems = array();
		try {
			$sql = 'SELECT * FROM quotation_items WHERE quote_id = '.$quoteId.' AND status = 1';
			$res =  DatabaseHandler::GetAll($sql);

			foreach ($res as $item) {
				$lineItem = new QuotationLine($item['quote_id'], $item['item_name'], $item['item_desc'], $item['quantity'], $item['unit_price'], $item['tax']);
				$lineItem->initId($item['id']);
				$lineItems[] = $lineItem;
			}

			return $lineItems;
		} catch (Exception $e) {
			
		}
	}

	public function save()
  	{
      	try {
      		$datetime = new DateTime();
 			$stamp = $datetime->format('YmdHis');
      		$sql = 'INSERT INTO quotation_items (quote_id, item_name, item_desc, quantity, unit_price, tax, stamp) 
      		VALUES ('.$this->quoteId.', "'.$this->itemName.'", "'.$this->itemDesc.'", '.$this->quantity.', '.$this->unitPrice.', '.$this->tax.', '.$stamp.')';
	 		DatabaseHandler::Execute($sql);
	 		//get lineId???? and set to object

      	} catch (Exception $e) {
      		
      	}
  	}

  	public static function DiscardLine($id)
    {
      try {
      	$sql = 'UPDATE quotation_items SET status = 0 WHERE id = '.$id;
        DatabaseHandler::Execute($sql);
        //recalculate quotation value
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

class Voucher extends Artifact
{
	public $id;
	public $party;
	public $type;
	public $transactionId;
	public $amount;
	public $tendered;
	public $date;
	public $stamp;
	public $description;
	public $extras;

	function __construct($id, $txTypeName, $txId, $amount, $description, $date, $stamp)
	{		
		$this->id = $id;
		$this->type = $txTypeName;
		$this->transactionId = $txId;
		$this->amount = $amount;
		$this->description = $description;
		$this->date = $date;
		$this->stamp = $stamp;
	}

	public function persist(){
		try {

			$sql = 'INSERT INTO vouchers (voucher_id, tx_type, transaction_id, amount, description, datetime, stamp) 
			VALUES ('.$this->id.', "'.$this->type.'", '.$this->transactionId.', '.$this->amount.', "'.$this->description.'", "'.$this->date.'", '.$this->stamp.')';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql2 = 'SELECT * FROM vouchers WHERE transaction_id = '.$this->transactionId;
			$res =  DatabaseHandler::GetOne($sql2);

			$this->id = $res;

		} catch (Exception $e) {
			
		}
	}

	public function setClient($id){
		$this->party = Client::GetClient($id);
	}

	public function setExtras($extras){
		$this->extras = $extras;
	}

	private static function initialize($args){
		return new Voucher($args['voucher_id'], $args['tx_type'], $args['transaction_id'], $args['amount'], $args['description'], $args['date'], $args['stamp']);
	}

	public static function GetVoucher($id)
	{
		try {
	 		
	 		$sql = 'SELECT * FROM vouchers WHERE transaction_id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);

			$invoice = self::initialize($res);
			//$invoice->loadPayments();

		} catch (Exception $e) {
			
		}
	}

	public static function GetVouchers($type)
	{
		try {
	 		
	 		$sql = 'SELECT * FROM vouchers WHERE tx_type = "'.$type.'"';
			$res =  DatabaseHandler::GetAll($sql);

			$vouchers = [];

			foreach ($res as $inv) {
				$vouchers[] = self::initialize($inv);
			}
			
			return $vouchers;

		} catch (Exception $e) {
			
		}
	}

	public static function CreateInvoiceVoucher($invoice){
		$inv = new Voucher($invoice->id, $invoice->transactionType->name, $invoice->transactionId, $invoice->amount->amount, $invoice->description, $invoice->date, $invoice->stamp);
		$inv->persist();
		$inv->setClient($invoice->clientId);
		return $inv;
	}	

	public static function CreateReceiptVoucher($receipt){
		$rcpt = new Voucher($receipt->id, $receipt->transactionType->name, $receipt->transactionId, $receipt->amount->amount, $receipt->description, $receipt->date, $receipt->stamp);
		$rcpt->persist();
		$rcpt->setClient($receipt->clientId);
		return $rcpt;
	}

	public static function CreateClaimVoucher($claim){
		$voucher = new Voucher($claim->transactionId, $claim->transactionType->name, $claim->transactionId, $claim->amount->amount, $claim->description, $claim->date, $claim->stamp);
		return $voucher;
	}

	public static function PaymentVoucher($payment){
		
	}

	public static function GoodsReceivedVoucher($grn){
		
	}

	public static function PaySlipVoucher($payslip){
		
	}

	public static function ExpenseReimbursementVoucher($claim){
		
	}

}

class CreditInvoice extends TransactionType
{//A kind of process type or protocol - 5d - a kind of event????

	function __construct($clientId)
	{
		parent::__construct("Invoice");
		
		$this->drAccounts[] = Account::GetAccountByNo($clientId, 'clients', 'Debtors');
		$this->drRatios[] = 1;
		$this->crAccounts[] = Account::GetAccount('Sales', 'ledgers');
		$this->crRatios[] = 1;

		//this info should originate from the database, insert ignore protocol included
		//$this->code = self::GetCode('PayPal');
		//parent::__construct();
	}
}

class Invoice extends FinancialTransaction
{
	public $id;
	public $clientId;
	public $projectId;
	public $quotations = [];
	public $description;
	public $tax;
	public $discount;
	public $amt;
	public $total;
	public $status;

	function __construct($id, $clientId, $projectId, $description, $amount, $tax, $discount, $total, $status)
	{
		$this->id = $id;
		$this->clientId = $clientId;
		$this->projectId = intval($projectId);
		$this->tax = new Money(floatval($tax), Currency::Get('KES'));
		$this->discount = floatval($discount);
		$this->total = new Money(floatval($total), Currency::Get('KES'));
		$this->amt = new Money(floatval($amount), Currency::Get('KES'));
		$this->status = $status;
		$txtype = new CreditInvoice($clientId);
		parent::__construct(new Money(floatval($total), Currency::Get('KES')), $description, $txtype);
		$this->update();
	}

	public function update()
	{
		try {
	        $sql = 'UPDATE invoices SET datetime = "'.$this->date.'", stamp = '.$this->stamp.' WHERE id = '.$this->id;
	        DatabaseHandler::Execute($sql);
	        return true;
	    } catch (Exception $e) {
	        
	    }
	}

	public function post()
	{
		if ($this->prepare()) {
			$voucher = TransactionProcessor::ProcessInvoice($this);
			if ($voucher) {
				//payment has gone trough;
				foreach ($this->quotations as $quote) {
					$quote->setInvoiced();
				}

				if ($this->projectId != 0) {
					$project = Project::GetProject($this->projectId);
					$project->debit($this->total);
				}

				$this->status = 1;
				$this->updateStatus();
				
				$extras = new stdClass();
   				$extras->amount = $this->amt->amount;
   				$extras->tax = $this->tax->amount;
   				$extras->discount = $this->discount;
   				$extras->total = $this->total->amount;
   				$extras->quotations = $this->quotations;

				$voucher->setExtras($extras);
				return $voucher;
			}else{
				return false;
			}
		}
		
		//make the journal entry based on the invoicing TX
		//Cr - Sales
		//Dr - Debtors [A/C Receivable]
		//Dr - Taxes Collectable
	}

	private function prepare()
	{		
		//singleton processor for all payments with an static instance of a transaction processor
		//Transaction == $payment
		//payment is a subclass of transaction
		//since it processes payments, it generates receipts
		//sum(cr) + sum(dr) = 0

		foreach ($this->quotations as $quote) {
			if ($quote->status > 2) {
				return false;
			}
		}

		for ($i=0; $i < count($this->transactionType->drAccounts); $i++) { 
			$amount = new Money(floatval($this->amount->amount * $this->transactionType->drRatios[$i]), $this->amount->unit);
			$this->add(new AccountEntry($this, $this->transactionType->drAccounts[$i], $amount, $this->date, 'dr'));
		}

		for ($i=0; $i < count($this->transactionType->crAccounts); $i++) { 
			$amount = new Money(floatval($this->amount->amount * $this->transactionType->crRatios[$i]), $this->amount->unit);
			$this->add(new AccountEntry($this, $this->transactionType->crAccounts[$i], $amount, $this->date, 'cr'));
		}

		return true;

	}

	public function loadQuotes($quotes)
	{
		$quotes = explode(",", $quotes);
		foreach ($quotes as $qid) {
			$this->quotations[] = Quotation::GetQuotation($qid);
		}
	}

	private function updateStatus()
	{
		try {

			$sql = 'UPDATE invoices SET status = '.$this->status.' WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);	 		

		} catch (Exception $e) {
			
		}
	}

	private static function initialize($args){
		$invoice =  new Invoice($args['id'], $args['client_id'], $args['project_id'], $args['description'], $args['amount'], $args['tax'], $args['discount'], $args['total'], $args['status']);
		$invoice->loadQuotes($args['quotes']);
		return $invoice;
	}


	public static function RaiseInvoice($clientId, $purpose, $quotes, $amount, $tax, $discount, $total)
	{
		try {
			
			$datetime = new DateTime();
			#status 0 - awaiting payment, 1- partially paid, 2-overdue, 3-paid
			if ($purpose == "G") {
				$ref = "General Services";
				$pid = 0;
			}else{
				$prj = Project::GetProject(intval($purpose));
				$ref = $prj->name.' Project';
				$pid = intval($purpose);
			}

			$quotes = implode(',', $quotes);

			$sql = 'INSERT INTO invoices (client_id, quotes, project_id, amount, tax, discount, total, description, status) VALUES 
			('.$clientId.', "'.$quotes.'", '.$pid.', '.$amount.', '.$tax.', '.$discount.', '.$total.', "'.$ref.'", 0)';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql2 = 'SELECT * FROM invoices WHERE client_id = '.$clientId.' ORDER BY id DESC LIMIT 0,1';
			$res =  DatabaseHandler::GetRow($sql2);

			return self::initialize($res);

		} catch (Exception $e) {
			
		}
	}
}

class CreditReceipt extends TransactionType
{//A kind of process type or protocol - 5d - a kind of event????

	function __construct($ledgerId, $clientId)
	{
		parent::__construct("Receipt");
		
		$this->drAccounts[] = Account::GetLedger($ledgerId);
		$this->drRatios[] = 1;
		$this->crAccounts[] = Account::GetAccountByNo($clientId, 'clients', 'Debtors');
		$this->crRatios[] = 1;

		//this info should originate from the database, insert ignore protocol included
		//$this->code = self::GetCode('PayPal');
		//parent::__construct();
	}
}

class Receipt extends FinancialTransaction
{
	public $id;
	public $clientId;
	public $projectId;
	public $voucherNo;
	public $ledgerId;
	public $status;

	function __construct($id, $clientId, $projectId, $voucherNo, $amount, $ledgerId, $descr, $status)
	{
		$this->id = $id;
		$this->clientId = $clientId;
		$this->projectId = intval($projectId);
		$this->voucherNo = $voucherNo;
		$this->ledgerId = $ledgerId;
		$this->status = $status;
		$txtype = new CreditReceipt($ledgerId, $clientId);
		parent::__construct(new Money(floatval($amount), Currency::Get('KES')), $descr, $txtype);
		$this->update();
	}

	public function update()
	{
		try {
	        $sql = 'UPDATE receipts SET datetime = "'.$this->date.'", stamp = '.$this->stamp.' WHERE id = '.$this->id;
	        DatabaseHandler::Execute($sql);
	        return true;
	    } catch (Exception $e) {
	        return false;
	    }
	}

	public function submit()
	{
		if ($this->prepare()) {
			$voucher = TransactionProcessor::ProcessPayment($this);
			if ($voucher) {
				//payment has gone trough;
				
				if ($this->projectId != 0) {
					$project = Project::GetProject($this->projectId);
					$project->credit($this->amount);
				}
				$this->status = 1;
				$this->updateStatus();
				return $voucher;
			}else{
				return false;
			}
		}
		
		//make the journal entry based on the invoicing TX
		//Cr - Sales
		//Dr - Debtors [A/C Receivable]
		//Dr - Taxes Collectable
	}

	private function prepare()
	{		
		//


		for ($i=0; $i < count($this->transactionType->drAccounts); $i++) { 
			$amount = new Money(floatval($this->amount->amount * $this->transactionType->drRatios[$i]), $this->amount->unit);
			$this->add(new AccountEntry($this, $this->transactionType->drAccounts[$i], $amount, $this->date, 'dr'));
		}

		for ($i=0; $i < count($this->transactionType->crAccounts); $i++) { 
			$amount = new Money(floatval($this->amount->amount * $this->transactionType->crRatios[$i]), $this->amount->unit);
			$this->add(new AccountEntry($this, $this->transactionType->crAccounts[$i], $amount, $this->date, 'cr'));
		}

		return true;

	}

	private function updateStatus()
	{
		try {

			$sql = 'UPDATE receipts SET status = '.$this->status.' WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);	 		

		} catch (Exception $e) {
			return false;
		}
	}

	private static function initialize($args){
		$payment =  new Receipt($args['id'], $args['client_id'], $args['project_id'], $args['voucher_no'], $args['amount'], $args['ledger_id'], $args['description'], $args['status']);
		return $payment;
	}


	public static function ReceivePayment($clientId, $purpose, $ledgerId, $amount, $voucherno, $descr)
	{
		try {
			
			$datetime = new DateTime();
			if ($purpose == "G") {
				$ref = $descr;
				$purp = 0;
			}else{
				$prj = Project::GetProject(intval($purpose));
				$ref = $prj->name." - ".$descr;
				$purp = $purpose;
			}

			$sql = 'INSERT INTO receipts (client_id, project_id, voucher_no, amount, ledger_id, description, status) VALUES 
			('.$clientId.', '.$purp.', "'.$voucherno.'", '.$amount.', '.$ledgerId.', "'.$ref.'", 0)';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql2 = 'SELECT * FROM receipts WHERE client_id = '.$clientId.' ORDER BY id DESC LIMIT 0,1';
			$res =  DatabaseHandler::GetRow($sql2);

			return self::initialize($res);

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

class BalanceBF extends TransactionType
{
	function __construct($clientId)
	{
		parent::__construct("BalanceBF");
		
		$this->drAccounts[] = Account::GetAccountByNo($clientId, 'clients', 'Debtors');
		$this->drRatios[] = 1;
		$this->crAccounts[] = Account::GetAccount('Sales', 'ledgers');
		$this->crRatios[] = 1;
	}
}

class BalanceTransfer extends FinancialTransaction
{
	public $clientId;

	function __construct($clientId, $amount)
	{
		$this->clientId = $clientId;
		//$this->amount = $amount; - Money class
		//$this->description = "Balance brought forward";
		//$txtype = new BalanceBF($clientId);
		parent::__construct($amount, "Balance brought forward", new BalanceBF($clientId));
	}

	public function execute()
	{
		if ($this->prepare()) {
			if (TransactionProcessor::ProcessTransfer($this)) {
				return true;
			}else{
				return false;
			}
		}
	}

	private function prepare()
	{		
		for ($i=0; $i < count($this->transactionType->drAccounts); $i++) { 
			$amount = new Money(floatval($this->amount->amount * $this->transactionType->drRatios[$i]), $this->amount->unit);
			$this->add(new AccountEntry($this, $this->transactionType->drAccounts[$i], $amount, $this->date, 'dr'));
		}

		for ($i=0; $i < count($this->transactionType->crAccounts); $i++) { 
			$amount = new Money(floatval($this->amount->amount * $this->transactionType->crRatios[$i]), $this->amount->unit);
			$this->add(new AccountEntry($this, $this->transactionType->crAccounts[$i], $amount, $this->date, 'cr'));
		}

		return true;

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

class ProjectActivity
{
	public $id;
  	public $projectId;
  	public $service;
  	public $task;
  	public $instances;	
	public $requestDate;
	public $executionDate;
	public $stamp;
	public $status;

  	function __construct($id, $projectId, $service, $task, $instances, $requestDate, $executionDate = 0, $stamp, $status = 0)
	{
		$this->id = $id;
		$this->projectId = $projectId;		
		$this->service = $service;
		$this->task = $task;
		$this->instances = $instances;		
		$this->requestDate = $requestDate;
		$this->executionDate = $executionDate;
		$this->stamp = $stamp;
		$this->status = $status;
	}

	public function complete()
    {
      	try {
      		$datetime = new DateTime();
      		$sql = 'UPDATE project_activities SET date_executed = "'.$datetime->format('d/m/Y').'",status = 1 WHERE id = '.$this->id;
        	DatabaseHandler::Execute($sql);
        	$this->executionDate = $datetime->format('d/m/Y');
        	$this->status = 1;
      	} catch (Exception $e) {
        
      	}
    }

	public static function Create($projectId, $service, $task, $instances, $qlid)
    {
      try {
      	$datetime = new DateTime();
		$sql = 'INSERT IGNORE INTO project_activities (id, project_id, service, task, instances, date_requested, stamp, status) 
		VALUES ('.$qlid.', '.$projectId.', "'.$service.'", "'.$task.'", '.$instances.', "'.$datetime->format('d/m/Y').'", '.$datetime->format('YmdHis').', 0)';
		DatabaseHandler::Execute($sql);

		$sql = 'SELECT * FROM project_activities WHERE id = '.$qlid;
		$res =  DatabaseHandler::GetRow($sql);        
        return self::initialize($res);
      } catch (Exception $e) {
        
      }

    }

    public static function GetActivities($prid)
    {
      	try {
        	$sql = 'SELECT * FROM project_activities WHERE project_id = '.$prid;
			$res =  DatabaseHandler::GetAll($sql);
			$activities = array();
        	foreach ($res as $act) {
        		$activities[] = self::initialize($act);
        	}                
        	return $activities;

      	} catch (Exception $e) {
        
      	}

    }

    public static function GetActivity($id)
    {
      	try {
        	$sql = 'SELECT * FROM project_activities WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);             
        	return self::initialize($res);
      	} catch (Exception $e) {
        
      	}
    }

    private static function initialize($args)
  	{
     	$activity = new ProjectActivity($args['id'], $args['project_id'], $args['service'], $args['task'], $args['instances'], $args['date_requested'], $args['date_executed'], $args['stamp'], $args['status']);
      	return $activity;
  	}

  	public static function DiscardActivity($id)
    {
      	try {
      		$sql = 'UPDATE project_activities SET status = 2 WHERE id = '.$id;
        	DatabaseHandler::Execute($sql);
      	} catch (Exception $e) {
        
      	}
    }
}

class Project
{
  	public $id;
  	public $name;
  	public $location;
  	public $descr;
  	public $date;
	public $stamp;
	public $status;
	public $bal;
	public $client;
	public $quotations = [];
	public $activities = [];

  	function __construct($id, $name, $location, $descr, $date, $client, $stamp, $status = 0, $bal = 0)
	{
		$this->id = $id;
		$this->name = $name;		
		$this->location = $location;
		$this->descr = $descr;
		$this->date = $date;
		$this->client = $client;
		$this->stamp = $stamp;
		$this->status = $status;
		$this->bal = floatval($bal);
	}

    public function importQuote($quoteId)
	{
		$quote = Quotation::GetQuotation($quoteId);

		$this->quotations[] = $quote;
	}

	public function credit($amount)
	{
		try {
			$newbal = $this->bal - $amount->amount;
	      	$sql = 'UPDATE projects SET balance = '.floatval($newbal).' WHERE id = '.$this->id;
	        DatabaseHandler::Execute($sql);
	        $this->bal = $newbal;
	        return true;
	    } catch (Exception $e) {
	    	return false;	        
	    }
	}

	public function debit($amount)
	{
		try {
			$newbal = $this->bal + $amount->amount;
	      	$sql = 'UPDATE projects SET balance = '.floatval($newbal).' WHERE id = '.$this->id;
	        DatabaseHandler::Execute($sql);
	        $this->bal = $newbal;
	        return true;
	    } catch (Exception $e) {
	        return false;
	    }
	}

	public function getActivities()
	{
		return $this->activities;
	}

	public function removeActivity($id)
	{
		foreach ($this->activities as $key => $activity) {
			if ($activity->id == $id) {
				QuotationLine::DiscardLine($activity->qlid);
				ProjectActivity::DiscardActivity($id);
				unset($this->activities[$key]);
				break;
			}
		}
	}

	public function authorize()
	{
		$this->activities = [];
		foreach ($this->quotations as $quote) {
			$quote->setProject($this->id);

			foreach ($quote->lineItems as $item) {
				$this->activities[] = ProjectActivity::Create($this->id, $item->itemName, $item->itemDesc, $item->quantity, $item->lineId);
			}
		}
		return true;
	}

	public static function GetProject($id)
  	{      	
  		try {
  			$sql = 'SELECT * FROM projects WHERE id = '.$id;
	        $res =  DatabaseHandler::GetRow($sql);        
	        return self::initialize($res);
  		} catch (Exception $e) {
  			return false;
  		}
  	}

  	public static function GetClientProjects($clientId)
  	{      	
  		$sql = 'SELECT * FROM projects WHERE client_id = '.$clientId;
        $res =  DatabaseHandler::GetAll($sql);
        $projects = [];
        foreach ($res as $project) {
          $projects[] = self::initialize($project);
        }
        return $projects;
  	}

  	public static function GetAllProjects()
  	{      	
  		$sql = 'SELECT * FROM projects';
        $res =  DatabaseHandler::GetAll($sql);
        $projects = [];
        foreach ($res as $project) {
          $projects[] = self::initialize($project);
        }
        return $projects;
  	}

  	public static function GetProjectExpenses()
  	{      	
  		$sql = 'SELECT * FROM projects';
        $res =  DatabaseHandler::GetAll($sql);
        $projects = [];
        foreach ($res as $project) {
          $projects[] = self::initialize($project);
        }
        return $projects;
  	}

  	public static function CurrentProjects()
  	{      	
  		$sql = 'SELECT * FROM projects WHERE status = 1';
        // Execute the query and return the results
        $res =  DatabaseHandler::GetAll($sql);
        $projects = array();
        foreach ($res as $project) {
          $projects[] = self::initialize($project);
        }
        return $projects;
  	}

  	private static function initialize($args)
  	{
  		$client = Client::GetClient($args['client_id']);
     	$project = new Project($args['id'], $args['name'], $args['location'], $args['descr'], $args['date'], $client, $args['stamp'], $args['status'], $args['balance']);

      	$project->quotations = Quotation::GetProjectQuotations($args['id'], $client);
      	$project->activities = ProjectActivity::GetActivities($args['id']);
      	
      	return $project;
  	}

  	public static function Create($name, $location, $descr, $clientId)
    {
      try {
      	$datetime = new DateTime();
        $sql = 'INSERT IGNORE INTO projects (name, location, date, descr, client_id, stamp, status) 
        VALUES ("'.$name.'", "'.$location.'", "'.$datetime->format('d/m/Y').'", "'.$descr.'", '.$clientId.', '.$datetime->format('YmdHis').', 0)';
        DatabaseHandler::Execute($sql);

        $sql = 'SELECT * FROM projects WHERE stamp = '.$datetime->format('YmdHis');
        $res =  DatabaseHandler::GetRow($sql);        
        return self::initialize($res);
      } catch (Exception $e) {
        return false;
      }
    }

	public static function Update($id, $name, $location, $status, $desc)
	{
		try {
	      	$sql = 'UPDATE projects SET name = "'.$name.'", location = "'.$location.'", status = '.$status.', descr = "'.$desc.'" WHERE id = '.$id;
	        DatabaseHandler::Execute($sql);
	        return self::GetProject($id);
	    } catch (Exception $e) {
	        
	    }
	}

	public static function Delete($id)
	{
		try {
			$sql = 'DELETE FROM projects WHERE id = '.$id;			
			DatabaseHandler::Execute($sql);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}

class WorkReport
{
	public $id;
  	public $projectId;
  	public $activities = [];
  	public $location;
  	public $personell = [];
  	public $report;	
	public $expense_voucher;
	public $date;
	public $stamp;
	public $status;

  	function __construct($id, $projectId, $location, $report, $date, $stamp, $status = 0)
	{
		$this->id = $id;
		$this->projectId = $projectId;
		$this->location = $location;
		$this->report = $report;
		$this->date = $date;
		$this->stamp = $stamp;
		$this->status = $status;
	}

	public function setExpensesVoucher($voucher){
		try {
	        $sql = 'UPDATE work_reports SET voucher_id = '.$voucher->id.' WHERE id = '.$this->id;
	        DatabaseHandler::Execute($sql);
	        $this->expense_voucher = $voucher;
	        return true;
	    } catch (Exception $e) {
	        
	    }
	}

	public function load($activities, $personell){
		$activities = explode(',', $activities);
		$personell = explode(',', $personell);
		foreach ($activities as $actId) {
			$this->activities[] = ProjectActivity::GetActivity($actId);
		}

		/*foreach ($personell as $employee) {
			$this->personell[] = Employee::GetEmployee($employee);
		}*/
	}

	public function markActivities(){
		foreach ($this->activities as $activity) {
			$activity->complete();
		}
	}

	public static function Create($projectId, $activities, $location, $personell, $report, $charges)
    {
	    try {
	      	$datetime = new DateTime();
			$sql = 'INSERT IGNORE INTO work_reports (project_id, activities, location, personell, report, date, stamp, status) 
			VALUES ('.$projectId.', "'.implode(",", $activities).'", "'.$location.'", "'.implode(",", $personell).'", "'.$report.'", "'.$datetime->format('d/m/Y').'", '.$datetime->format('YmdHis').', 0)';
			DatabaseHandler::Execute($sql);

			$sql = 'SELECT * FROM work_reports WHERE stamp = '.$datetime->format('YmdHis');
			$res =  DatabaseHandler::GetRow($sql);
			$report = self::initialize($res);
			$report->markActivities();
			$voucher = ExpenseVoucher::Create($projectId, $report, $charges); 
	        $report->setExpensesVoucher($voucher);
	        return $report;
	    } catch (Exception $e) {
	        return false;
	    }

    }

    public static function GetReport($rid)
    {
      try {
        $sql = 'SELECT * FROM work_reports WHERE id = '.$rid;
		$res =  DatabaseHandler::GetRow($sql);               
        return self::initialize($res);

      } catch (Exception $e) {
        return false;
      }

    }

    public static function GetReports($prid)
    {
      try {
        $sql = 'SELECT * FROM work_reports WHERE project_id = '.$prid;
		$res =  DatabaseHandler::GetAll($sql);
		$activities = array();
        foreach ($res as $act) {
        	$activities[] = self::initialize($act);
        }                
        return $activities;

      } catch (Exception $e) {
        
      }

    }

    private static function initialize($args)
  	{
     	$activity = new WorkReport($args['id'], $args['project_id'], $args['location'], $args['report'], $args['date'], $args['stamp'], $args['status']);
      	$activity->load($args['activities'], $args['personell']);
      	return $activity;
  	}

  	public static function DeleteReport($id)
    {
      try {
      	$sql = 'DELETE FROM work_reports WHERE id = '.$id;
        DatabaseHandler::Execute($sql);
      } catch (Exception $e) {
        
      }
    }
}

class ExpenseItem
{
	public $id;
  	public $voucherId;
  	public $claimant;
  	public $description;
	public $ledger;
	public $claimed;
	public $adjusted;

  	function __construct($id, $voucherId, $claimantId, $description, $ledgerId, $claimed, $adjusted)
	{
		$this->id = $id;
		$this->claimant = Employee::GetEmployee($claimantId);
		$this->voucherId = $voucherId;
		$this->description = $description;
		$this->ledger = Ledger::GetLedger($ledgerId);
		$this->claimed = $claimed;
		$this->adjusted = $adjusted;
	}

	public function revoke(){
		//revoke
	}

	public static function Adjust($items){
		foreach ($items as $item) {
			try {
		        $sql = 'UPDATE expense_items SET adjusted = '.floatval($item['amount']).' WHERE id = '.intval($item['viid']);
		        DatabaseHandler::Execute($sql);
		    } catch (Exception $e) {
		        return false;
		        break;
		    }
		}
		return true;
	}

	public static function Create($vid, $claimantId, $descr, $ledgerId, $claimed)
    {
      try {
		$sql = 'INSERT INTO expense_items (voucher_id, claimant_id, descr, ledger_id, claimed, adjusted) 
		VALUES ('.$vid.', '.intval($claimantId).', "'.$descr.'", '.intval($ledgerId).', '.floatval($claimed).', '.floatval($claimed).')';
		DatabaseHandler::Execute($sql);

		$sql = 'SELECT * FROM expense_items WHERE voucher_id = '.$vid.' AND descr = "'.$descr.'"';
		$res =  DatabaseHandler::GetRow($sql); 
        return self::initialize($res);
        
      } catch (Exception $e) {
        
      }

    }

    public static function GetItems($vid)
    {
      try {
        $sql = 'SELECT * FROM expense_items WHERE voucher_id = '.$vid;
		$res =  DatabaseHandler::GetAll($sql);
		$vitems = array();
        foreach ($res as $item) {
        	$vitems[] = self::initialize($item);
        }                
        return $vitems;

      } catch (Exception $e) {
        
      }

    }

    private static function initialize($args)
  	{
     	$item = new ExpenseItem($args['id'], $args['voucher_id'], $args['claimant_id'], $args['descr'], $args['ledger_id'], $args['claimed'], $args['adjusted']);
      	return $item;
  	}

  	public static function DeleteItems($vid)
    {
      try {
      	$sql = 'DELETE FROM expense_items WHERE voucher_id = '.$vid;
        DatabaseHandler::Execute($sql);
      } catch (Exception $e) {
        
      }
    }
}

class ExpenseVoucher
{
	public $id;
  	public $projectId;
  	public $reportId;
  	public $transactionId;
	public $date;
	public $stamp;
	public $status;
	public $total;
	public $items = [];
	//IMPLEMENTATION
  	function __construct($id, $projectId, $reportId, $transactionId, $date, $stamp, $status = 0)
	{
		$this->id = $id;
		$this->projectId = $projectId;
		$this->reportId = $reportId;
		$this->transactionId = $transactionId;
		$this->date = $date;
		$this->stamp = $stamp;
		$this->status = $status;
	}

	public function loadItems(){
		$this->items = ExpenseItem::GetItems($this->id);
		$this->calculate();
	}

	public function addItem($vitem){
		$this->items[] = $vitem;
		$this->calculate();
	}

	public function calculate(){
		$total = 0;
		foreach ($this->items as $item) {
			$total = $total + $item->adjusted;
		}
		$this->total = $total;
	}

	public function authorize($txid){
		try {
		    $sql = 'UPDATE expense_vouchers SET status = 1, transaction_id = '.intval($txid).' WHERE id = '.$this->id;
		    DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
		    return false;
		}
	}
	//INTERFACE
	private static function initialize($args)
  	{
     	$voucher = new ExpenseVoucher($args['id'], $args['project_id'], $args['report_id'], $args['transaction_id'], $args['date'], $args['stamp'], $args['status']);
      	$voucher->loadItems();
      	return $voucher;
  	}

	public static function Create($projectId, $report, $charges)
    {
      try {
      	$datetime = new DateTime();
		$sql = 'INSERT IGNORE INTO expense_vouchers (project_id, report_id, date, stamp, status) 
		VALUES ('.$projectId.', '.$report->id.', "'.$datetime->format('d/m/Y').'", '.$datetime->format('YmdHis').', 0)';
		DatabaseHandler::Execute($sql);

		$sql = 'SELECT * FROM expense_vouchers WHERE stamp = '.$datetime->format('YmdHis');
		$res =  DatabaseHandler::GetRow($sql);        
        $voucher = new ExpenseVoucher($res['id'], $res['project_id'], $res['report_id'], 0, $res['date'], $res['stamp'], $res['status']);
        foreach ($charges as $charge) {
        	$voucher->addItem(ExpenseItem::Create($res['id'], $charge['claimant'], $charge['description'], $charge['category'], $charge['amount']));
        }
        return $voucher;
        
      } catch (Exception $e) {
        
      }

    }

    public static function GetVoucher($vid)
    {
      try {
        $sql = 'SELECT * FROM expense_vouchers WHERE id = '.$vid;
		$res =  DatabaseHandler::GetRow($sql);               
        return self::initialize($res);

      } catch (Exception $e) {
        return false;
      }

    }

    public static function GetProjectVouchers($prid)
    {
      try {
        $sql = 'SELECT * FROM expense_vouchers WHERE project_id = '.$prid;
		$res =  DatabaseHandler::GetAll($sql);
		$vouchers = array();
        foreach ($res as $act) {
        	$vouchers[] = self::initialize($act);
        }                
        return $vouchers;

      } catch (Exception $e) {
        
      }

    }

    public static function GetProjectClaims($prid)
    {
      try {
        $sql = 'SELECT * FROM expense_vouchers WHERE status = 0 AND project_id = '.$prid;
		$res =  DatabaseHandler::GetAll($sql);
		$vouchers = array();
        foreach ($res as $act) {
        	$vouchers[] = self::initialize($act);
        }                
        return $vouchers;

      } catch (Exception $e) {
        
      }

    }

    public static function GetUnsettledVouchers($prid)
    {
      try {
        $sql = 'SELECT * FROM expense_vouchers WHERE status = 0';
		$res =  DatabaseHandler::GetAll($sql);
		$vouchers = array();
        foreach ($res as $act) {
        	$vouchers[] = self::initialize($act);
        }                
        return $vouchers;

      } catch (Exception $e) {
        
      }

    }

  	public static function DeleteVoucher($id)
    {
      try {
      	$sql = 'DELETE FROM expense_vouchers WHERE id = '.$id;
        DatabaseHandler::Execute($sql);
      } catch (Exception $e) {
        
      }
    }
}

class DirectPosting extends TransactionType
{//A kind of process type or protocol - 5d - a kind of event????

	function __construct($entries, $amount, $classifier)
	{
		parent::__construct("Direct Posting - ".$classifier);
		
		foreach ($entries as $entry) {
			if ($entry['effect'] == "dr") {
				$this->drAccounts[] = Account::GetLedger($entry['lid']);
				$this->drRatios[] = floatval(floatval($entry['amount'])/floatval($amount));
			}elseif ($entry['effect'] == "cr") {
				$this->crAccounts[] = Account::GetLedger($entry['lid']);
				$this->crRatios[] = floatval(floatval($entry['amount'])/floatval($amount));
			}
		}
	}
}

class GeneralTransaction extends FinancialTransaction
{
	public $status;
	public $txentries = [];

	function __construct($entries, $amount, $description, $classifier)
	{
		//$this->status = $status;
		$this->txentries = $entries;
		$txtype = new DirectPosting($entries, $amount, $classifier);
		parent::__construct(new Money(floatval($amount), Currency::Get('KES')), $description, $txtype);
	}

	public function post()
	{
		if ($this->prepare()) {
			if (TransactionProcessor::ProcessTransaction($this)) {
				return true;
			}else{
				return false;
			}
		}
	}

	public function postprojectclaim($expvouch)
	{
		if ($this->prepare()) {
			$voucher = TransactionProcessor::ProcessClaim($this);
			if ($voucher){
				$voucher->setExtras($expvouch);
				return $voucher;
			}else{
				return false;
			}
		}
	}

	private function prepare()
	{
		foreach ($this->txentries as $entry) {
			if ($entry['effect'] == 'dr') {
				$amount = new Money(floatval($entry['amount']), $this->amount->unit);
				$this->add(new AccountEntry($this, Account::GetLedger($entry['lid']), $amount, $this->date, 'dr'));
			}else if ($entry['effect'] == 'cr') {
				$amount = new Money(floatval($entry['amount']), $this->amount->unit);
				$this->add(new AccountEntry($this, Account::GetLedger($entry['lid']), $amount, $this->date, 'cr'));
			}
		}

		return true;
	}

	public static function PostTransaction($entries, $amount, $descr)
	{
		return new GeneralTransaction($entries, $amount, $descr, "General Transaction");
	}

	public static function PostClaim($ledgerId, $amount, $items, $descr)
	{
		$entries = [];
		//Debit entries
		foreach ($items as $item) {
			$entry['lid'] = $item->ledger->id;
			$entry['effect'] = 'dr';
			$entry['amount'] = $item->adjusted;
			$entries[] = $entry;
		}
		//Credit entry
		$entry['lid'] = $ledgerId;
		$entry['effect'] = 'cr';
		$entry['amount'] = $amount;
		$entries[] = $entry;
		
		return new GeneralTransaction($entries, $amount, $descr, "Project Claim");
	}

	public static function PostExpense($crid, $drid, $amount, $descr)
	{
		$entries = [];
		//Debit entry
		$entry['lid'] = $drid;
		$entry['effect'] = 'dr';
		$entry['amount'] = $amount;
		$entries[] = $entry;

		//Credit entry
		$entry['lid'] = $crid;
		$entry['effect'] = 'cr';
		$entry['amount'] = $amount;
		$entries[] = $entry;
		
		return new GeneralTransaction($entries, $amount, $descr, "General Expenses");
	}

	public static function InjectCapital($crid, $drid, $amount, $descr)
	{
		$entries = [];
		//Debit entry
		$entry['lid'] = $drid;
		$entry['effect'] = 'dr';
		$entry['amount'] = $amount;
		$entries[] = $entry;

		//Credit entry
		$entry['lid'] = $crid;
		$entry['effect'] = 'cr';
		$entry['amount'] = $amount;
		$entries[] = $entry;
		
		return new GeneralTransaction($entries, $amount, $descr, "Capital Injection");
	}

	public static function PostBankTx($action, $account, $amount, $descr)
	{
		$entries = [];

		switch ($action) {
			case 'CashDeposit':
				//Debit entry				
				$entry['lid'] = $account;
				$entry['effect'] = 'dr';
				$entry['amount'] = $amount;
				$entries[] = $entry;

				//Credit entry
				$ledger = Ledger::GetLedgerByName('"Cash in Hand"');
				$entry['lid'] = $ledger->id;
				$entry['effect'] = 'cr';
				$entry['amount'] = $amount;
				$entries[] = $entry;
				break;

			case 'CashWithdrawal':
				//Debit entry
				$ledger = Ledger::GetLedgerByName('"Cash in Hand"');
				$entry['lid'] = $ledger->id;
				$entry['effect'] = 'dr';
				$entry['amount'] = $amount;
				$entries[] = $entry;

				//Credit entry
				$entry['lid'] = $account;
				$entry['effect'] = 'cr';
				$entry['amount'] = $amount;
				$entries[] = $entry;
				break;

			default:
				
				break;
		}
		
		return new GeneralTransaction($entries, $amount, $descr, "Bank Cash Transaction");
	}
}

class SupBalanceBF extends TransactionType
{
	function __construct($supplierId)
	{
		parent::__construct("BalanceBF");
		
		$this->crAccounts[] = Account::GetAccountByNo($supplierId, 'suppliers', 'Creditors');
		$this->crRatios[] = 1;
		$this->drAccounts[] = Account::GetAccount('Purchases', 'ledgers');
		$this->drRatios[] = 1;
	}
}

class SupplierBalanceTransfer extends FinancialTransaction
{
	public $supplierId;

	function __construct($supplierId, $amount)
	{
		$this->supplierId = $supplierId;
		//$this->amount = $amount; - Money class
		//$this->description = "Balance brought forward";
		//$txtype = new BalanceBF($supplierId);
		parent::__construct($amount, "Balance brought forward", new SupBalanceBF($supplierId));
	}

	public function execute()
	{
		if ($this->prepare()) {
			if (TransactionProcessor::ProcessTransfer($this)) {
				return true;
			}else{
				return false;
			}
		}
	}

	private function prepare()
	{		
		for ($i=0; $i < count($this->transactionType->drAccounts); $i++) { 
			$amount = new Money(floatval($this->amount->amount * $this->transactionType->drRatios[$i]), $this->amount->unit);
			$this->add(new AccountEntry($this, $this->transactionType->drAccounts[$i], $amount, $this->date, 'dr'));
		}

		for ($i=0; $i < count($this->transactionType->crAccounts); $i++) { 
			$amount = new Money(floatval($this->amount->amount * $this->transactionType->crRatios[$i]), $this->amount->unit);
			$this->add(new AccountEntry($this, $this->transactionType->crAccounts[$i], $amount, $this->date, 'cr'));
		}

		return true;

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
      //parent::__construct();
      if (!isset($args['id'])) {
        $args['id'] = 65824;//use random number, more especially a uuid
      }

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
        	$sql = 'SELECT * FROM views WHERE module_id = '.$mid;
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

    private static function initialize($args)
  	{
     	$view = new View($res['id'], $res['module_id'], $res['name'], $res['logo'], $res['link']);
      	return $view;
  	}
}

class Module
{
  	public $id;
  	public $name;
  	public $descr;
	public $views = [];

  	function __construct($id, $name, $descr)
	{
		$this->id = $id;
		$this->name = $name;		
		$this->descr = $descr;
		$this->views[] = View::GetModuleViews($this->id);
	}

  	private static function initialize($args)
  	{
     	$module = new Module($args['id'], $args['name'], $args['description']);      	
      	return $module;
  	}

  	public function addView($name, $logo, $link)
	{
		$this->views[] = new View::Create($this->id, $name, $logo, $link);
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

  	public static function Create($name, $descr)
    {
      try {
        $sql = 'INSERT INTO modules (name, description) 
        VALUES ("'.$name.'", "'.$descr.'")';
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

?>


