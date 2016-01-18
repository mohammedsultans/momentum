<?php
require_once('Inventory.php');
//require_once('Services.php');
//require_once('Quotation.php');
//require_once('SalesOrder.php');
//require_once('PurchaseOrder.php');
//require_once('Invoicing.php');
//require_once('Receipting.php');
//require_once('Vouchers.php');
//require_once('Expenses.php');// claims, purchases, bills, salaries, loan interests
class Client extends Party
{
  	public $creditRating;
  	public $accounts = array();
  	public $balance;
  	public $details;
  	//public $stockAccountNumber;
  	function __construct($id, $name, $telephone, $email, $address, $bal, $details)
  	{
  		$type = new PartyType('Client');
  		$this->balance = new Money(floatval($bal), Currency::Get('KES'));
  		$this->details = $details;
  		parent::__construct($type, $id, $name, $telephone, $email, $address);
  	}

  	public static function Update($id, $name, $telephone, $email, $address, $details)
  	{      	
  		try {
	        $sql = 'UPDATE clients SET name = "'.$name.'", telephone = "'.$telephone.'", email = "'.$email.'", address = "'.$address.'", details = "'.$details.'" WHERE id = '.$id;
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

  	public static function Create($name, $telephone, $email, $address, $bal, $details)
  	{      	
  		$type = new PartyType('Client');
  		$client = new Client($type, $name, $telephone, $email, $address, $bal, $details);
  		
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
        	$statement = TransactionVouchers::ClientStatement($id, '', 'true');
        	if (!empty($statement) && count($statement) > 0 ) {
        		return false;
        	}else{
        		$sql = 'DELETE FROM clients WHERE id = '.intval($id);
        		$res =  DatabaseHandler::Execute($sql);
        		return true;
        	}
        	
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

      $party = new Client($args['id'], $args['name'], $args['telephone'], $args['email'], $args['address'], $args['balance'], $args['details']);
      
      return $party;
    }

    private function save()
    {
      //ClientStore::Save($this)
      //ClientStore::SaveProperty('name', $this->name)
      try {
        $sql = 'SELECT * FROM clients WHERE name = "'.$this->name.'" AND telephone = "'.$this->telephone.'"';
  	    // Execute the query and return the results
  	    $res =  DatabaseHandler::GetRow($sql);
  	    if (!empty($res['id'])) {
  	    	Logger::Log(get_class($this), 'Exists', 'A client with the name: '.$this->name.' and phone number:'.$this->telephone.' already exists');
  	    	return false;
  	    }else{
  	    	$sql = 'INSERT INTO clients (type, name, telephone, address, email, details) 
  	        VALUES ("'.$this->type->name.'", "'.$this->name.'", "'.$this->telephone.'", "'.$this->address.'", "'.$this->email.'", "'.$this->details.'")';
  	        DatabaseHandler::Execute($sql);
  	        if ($this->balance->amount != 0) {
  	        	$sql = 'SELECT * FROM clients WHERE name = "'.$this->name.'" AND telephone = "'.$this->telephone.'"';
  		        // Execute the query and return the results
  		        $res =  DatabaseHandler::GetRow($sql);
  	        	return $this->transferBalance($res['id'], $this->balance);
  	        }else{
  	        	return true;
  	        }
  	    }
      } catch (Exception $e) {
      	Logger::Log(get_class($this), 'Exception', $e->getMessage());
        return false;
      }

    }

    private function transferBalance($clientId, $amount)
    {      
    	$transfer = SalesTX::RaiseSalesArrearsInvoice($clientId, $amount);
    	return $transfer->post();
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

class SalesOrderLine//Matches LPO
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
		$lineItem = new SalesOrderLine($orderId, $itemId, $itemName, $quantity, $vat, $unitPrice, $unitCost, $discount);
		
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
				$lineItem = new SalesOrderLine($item['order_id'], $item['item_id'], $item['item_name'], $item['quantity'], $item['vat'], $item['unit_price'], $item['unit_cost'], $item['discount'], $item['status']);
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

class SalesOrder
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
		$this->lineItems = SalesOrderLine::GetOrderItems($this->id);
	}

	public function initRecipient($partyId)
	{
		$this->recepientId = $partyId;
	}


	public function addToOrder(SalesOrderLine $orderItem)
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
			$sql = 'UPDATE sales_orders SET items = '.$items.', amount = '.$amount.', vat = '.$vat.', discount = '.$discount.', freight = '.$freight.', status = 1 WHERE id = '.$this->id;
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
			$sql = 'UPDATE sales_orders SET recepient_id = '.$partyId.' WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);
	 		$this->initRecipient($partyId);
		} catch (Exception $e) {
			
		}
	}

	public static function GetOrder($id)
	{
		$sql = 'SELECT * FROM sales_orders WHERE id = '.$id;
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
			$sql = 'INSERT INTO sales_orders (date_received, stamp, status) VALUES ("'.$datetime->format('Y/m/d').'", '.$datetime->format('YmdHis').', 0)';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql = 'SELECT * FROM sales_orders WHERE stamp = '.$datetime->format('YmdHis');
			$res =  DatabaseHandler::GetRow($sql);

			return new Order($res['id'], $res['date_received'], $res['status']);

		} catch (Exception $e) {
			
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
	public $user;

	function __construct($quoteId, $date, $status, $client, $user)
	{
		$this->id = $quoteId;
		$this->date = $date;
		$this->status = $status;
		$this->client = $client;
		$this->user = $user;
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
			Logger::Log(get_class($this), 'Exception', $e->getMessage());
			return false;
		}
	}

	public function discard()
	{
		try {
			$sql = 'DELETE FROM quotations WHERE id = '.$this->id;			
			DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			Logger::Log(get_class($this), 'Exception', $e->getMessage());
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
				Logger::Log(get_class($this), 'Exception', $e->getMessage());
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
				Logger::Log(get_class($this), 'Exception', $e->getMessage());
			}
		}		
	}

	private static function initialize($args, $client){
		$quote = new Quotation($args['id'], $args['date'], $args['status'], $client, $args['user']);
		$quote->initializeQuote();
		return $quote;
	}

	public static function CreateQuotation($client)
	{
		//Called and stored in a session object
		try {			
			$datetime = new DateTime();
			$sql = 'INSERT INTO quotations (client_id, date, stamp, status, user) VALUES ("'.$client->id.'","'.$datetime->format('Y/m/d').'", '.$datetime->format('YmdHis').', 1, "'.SessionManager::GetUsername().'")';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql = 'SELECT * FROM quotations WHERE stamp = '.$datetime->format('YmdHis');
			$res =  DatabaseHandler::GetRow($sql);

			return self::initialize($res, $client);

		} catch (Exception $e) {
			Logger::Log('Quotation', 'Exception', $e->getMessage());
		}
	}

	public static function GetQuotation($id)
	{
		try {
			$sql = 'SELECT * FROM quotations WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);
			if (!empty($res['date'])) {
				$client = Client::GetClient($res['client_id']);
				$quote = self::initialize($res, $client);
				if (!empty($res['project_id'])) {
					$quote->initProject($res['project_id']);
				}
				return $quote;
			}else{
				Logger::Log('Quotation', 'Missing', 'Missing quotation with id:'.$id);
				return null;
			}
			
		} catch (Exception $e) {
			Logger::Log('Quotation', 'Exception', $e->getMessage());
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
					$quote = self::initialize($item, $client);
					$quote->initProject($item['project_id']);
					$quotes[] = $quote;
				}else{
					
				}
			}
			
			return $quotes;
			
		} catch (Exception $e) {
			Logger::Log('Quotation', 'Exception', $e->getMessage());
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
					$quotes[] = self::initialize($item, $client);
				}else{
					
				}
			}
			
			return $quotes;
			
		} catch (Exception $e) {
			Logger::Log('Quotation', 'Exception', $e->getMessage());
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
					$quotes[] = self::initialize($item, $client);
				}else{
					
				}
			}
			
			return $quotes;
			
		} catch (Exception $e) {
			Logger::Log('Quotation', 'Exception', $e->getMessage());
			return null;
		}
		
	}

	public static function GetAllQuotations($dates, $all)
	{
		if ($all == 'true'){
			$sql = 'SELECT * FROM quotations';
		}else{
			$split = explode(' - ', $dates);
		    $d1 = explode('/', $split[0]);
		    $d2 = explode('/', $split[1]);
		    $lower = $d1[2].$d1[1].$d1[0].'000000' + 0;
		    $upper = $d2[2].$d2[1].$d2[0].'999999' + 0;
		    $sql = 'SELECT * FROM quotations WHERE stamp BETWEEN '.$lower.' AND '.$upper.'';
		}

		try {
			$res =  DatabaseHandler::GetAll($sql);
			$quotes = [];
			foreach ($res as $item) {
				if (!empty($item['date'])) {
					$client = Client::GetClient($item['client_id']);
					$quote = self::initialize($item, $client);
					if (!empty($item['project_id'])) {
						$quote->initProject($item['project_id']);
					}
					$quotes[] = $quote;
				}else{
					
				}
			}
			
			return $quotes;
			
		} catch (Exception $e) {
			Logger::Log('Quotation', 'Exception', $e->getMessage());
			return null;
		}
		
	}

	public static function Delete($id)
	{
		try {
			$sql = 'DELETE FROM quotations WHERE id = '.$id;			
			DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			Logger::Log('Quotation', 'Exception', $e->getMessage());
		}
	}
}

class SalesInvoiceLine
{
	public $lineId;
	public $invoiceId;
	public $itemId;
	public $itemName;
	public $itemDesc;
	public $quantity;
	public $unitPrice;//Money class - price per part
	public $tax;

	function __construct($invoiceId, $itemName, $itemDesc, $quantity, $unitPrice, $tax)
	{
		$this->invoiceId = $invoiceId;
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

	public static function Create($invoiceId, $itemName, $itemDesc, $quantity, $unitPrice, $tax)
	{
		$lineItem = new SalesInvoiceLine($invoiceId, $itemName, $itemDesc, $quantity, $unitPrice, $tax);		
		$lineItem->save();
		return $lineItem;
	}

	public static function GetLineItems($invoiceId)
	{
		//check whether available and make necessary inventory deductions, then
		$lineItems = array();
		try {
			$sql = 'SELECT * FROM invoice_items WHERE invoice_id = '.$invoiceId.' AND status = 1';
			$res =  DatabaseHandler::GetAll($sql);

			foreach ($res as $item) {
				$lineItem = new SalesInvoiceLine($item['invoice_id'], $item['item_name'], $item['item_desc'], $item['quantity'], $item['unit_price'], $item['tax']);
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
      		$sql = 'INSERT INTO invoice_items (invoice_id, item_name, item_desc, quantity, unit_price, tax, stamp) 
      		VALUES ('.$this->invoiceId.', "'.$this->itemName.'", "'.$this->itemDesc.'", '.$this->quantity.', '.$this->unitPrice.', '.$this->tax.', '.$stamp.')';
	 		DatabaseHandler::Execute($sql);
	 		//get lineId???? and set to object

      	} catch (Exception $e) {
      		
      	}
  	}

  	public static function DiscardLine($id)
    {
      try {
      	$sql = 'UPDATE invoice_items SET status = 0 WHERE id = '.$id;
        DatabaseHandler::Execute($sql);
        //recalculate quotation value
      } catch (Exception $e) {
        
      }

    }
}

class SalesInvoice
{
	public $id;
	public $projectId;
	public $description;
	public $date;
	public $lineItems = array();
	public $items;
	public $taxamt;
	public $amount;
	public $discount;
	public $total;
	public $status;
	public $clientId;
	public $extras;
	public $quoteIds;
	public $quotations = [];

	function __construct($invoiceId, $projectId, $quotes, $description, $discount, $date, $status, $client)
	{
		$this->id = $invoiceId;
		$this->projectId = $projectId;
		$this->description = $description;
		$this->discount = $discount;
		$this->date = $date;
		$this->status = $status;
		$this->clientId = $client->id;
		$this->quoteIds = $quotes;
	}

	public function initialize()
	{
		$this->lineItems = SalesInvoiceLine::GetLineItems($this->id);
		$this->generate();
	}

	public function addToInvoice(SalesInvoiceLine $lineItem)
	{
		array_push($this->lineItems, $lineItem);
		//$this->lineItems[] = $orderItem;
	}

	public function loadQuotes()
	{
		$quotes = explode(",", $this->quoteIds);
		foreach ($quotes as $qid) {
			$this->quotations[] = Quotation::GetQuotation($qid);
		}
	}

	public function removeFromInvoice($lineId)
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

		if ($this->discount != 0) {
			$total = $total * floatval((100 - $this->discount)/100);
		}

		try {
			//status: 0 - unauthorized, 1 - awaiting shipment, 3 - dispatched, 4 - delivered
			$sql = 'UPDATE invoices SET items = '.$items.', amount = '.$amount.', total = '.$total.', tax = '.$taxamt.' WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);
	 		$this->amount = new Money(floatval($amount), Currency::Get('KES'));
			$this->taxamt = new Money(floatval($taxamt), Currency::Get('KES'));
	 		$this->total = new Money(floatval($total), Currency::Get('KES'));
			$this->items = $items;
			//$this->status = 1;
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function updateStatus($status)
	{
		//Invoice posted successfully
		$this->status = $status;

		try {
			$sql = 'UPDATE invoices SET status = '.$this->status.' WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			
		}
	}

	public function discard()
	{
		try {
			$sql = 'DELETE FROM invoices WHERE id = '.$this->id;			
			DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			
		}
	}

	public static function CreateInvoice($client, $projectId, $quotes, $descr, $discount)
	{
		//Called and stored in a session object
		try {
			$datetime = new DateTime();
			$sql = 'INSERT INTO invoices (client_id, project_id, quotes, description, discount, datetime, stamp, status) VALUES ("'.$client->id.'", '.intval($projectId).', "'.$quotes.'", "'.$descr.'", '.floatval($discount).', "'.$datetime->format('d/m/Y H:i a').'", '.$datetime->format('YmdHis').', 0)';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql = 'SELECT * FROM invoices WHERE stamp = '.$datetime->format('YmdHis');
			$res =  DatabaseHandler::GetRow($sql);

			return new SalesInvoice($res['id'], $res['project_id'], $res['quotes'], $res['description'], $res['discount'], $res['datetime'], $res['status'], $client);

		} catch (Exception $e) {
			
		}
	}

	public static function GetInvoice($id)
	{
		try {
			$sql = 'SELECT * FROM invoices WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);
			if (!empty($res['datetime'])) {
				$client = Client::GetClient($res['client_id']);
				$invoice = new SalesInvoice($res['id'], $res['project_id'], $res['quotes'], $res['description'], $res['discount'], $res['datetime'], $res['status'], $client);
				$invoice->initialize();
				if (!empty($res['project_id'])) {
					$invoice->initProject($res['project_id']);
				}
				return $invoice;
			}else{
				return null;
			}
			
		} catch (Exception $e) {
			return null;
		}		
	}

	public static function GetProjectInvoices($pid, $client)
	{
		try {
			$sql = 'SELECT * FROM invoices WHERE project_id = '.$pid;
			$res =  DatabaseHandler::GetAll($sql);
			$invoices = [];
			foreach ($res as $item) {
				if (!empty($item['datetime'])) {
					$invoice = new SalesInvoice($item['id'], $item['project_id'], $item['quotes'], $item['description'], $item['discount'], $item['datetime'], $item['status'], $client);
					$invoice->initialize();
					$invoice->initProject($item['project_id']);
					$invoices[] = $invoice;
				}else{
					
				}
			}
			
			return $invoices;
			
		} catch (Exception $e) {
			return null;
		}		
	}

	public static function GetClientInvoices($clientid)
	{
		try {
			$client = Client::GetClient($clientid);
			$sql = 'SELECT * FROM invoices WHERE client_id = '.$clientid.' AND status = 1';
			$res =  DatabaseHandler::GetAll($sql);
			$invoices = [];
			foreach ($res as $item) {
				if (!empty($item['datetime'])) {
					$invoice = new SalesInvoice($item['id'], $item['project_id'], $item['quotes'], $item['description'], $item['discount'], $item['datetime'], $item['status'], $client);
					$invoice->initialize();
					$invoices[] = $invoice;
				}else{
					
				}
			}
			
			return $invoices;
			
		} catch (Exception $e) {
			return null;
		}		
	}

	public static function GetGeneralInvoices($clientid)
	{
		try {
			$client = Client::GetClient($clientid);
			$sql = 'SELECT * FROM invoices WHERE client_id = '.$clientid.' AND status = 1 AND isnull(project_id)';
			$res =  DatabaseHandler::GetAll($sql);
			$invoices = [];
			foreach ($res as $item) {
				if (!empty($item['datetime'])) {
					$invoice = new SalesInvoice($item['id'], $item['project_id'], $item['quotes'], $item['description'], $item['discount'], $item['datetime'], $item['status'], $client);
					$invoice->initialize();
					$invoices[] = $invoice;
				}
			}
			
			return $invoices;
			
		} catch (Exception $e) {
			return null;
		}		
	}

	public static function GetAllInvoices($dates, $all)
	{
		if ($all == 'true'){
			$sql = 'SELECT * FROM invoices';
		}else{
			$split = explode(' - ', $dates);
		    $d1 = explode('/', $split[0]);
		    $d2 = explode('/', $split[1]);
		    $lower = $d1[2].$d1[1].$d1[0].'000000' + 0;
		    $upper = $d2[2].$d2[1].$d2[0].'999999' + 0;
		    $sql = 'SELECT * FROM invoices WHERE stamp BETWEEN '.$lower.' AND '.$upper.'';
		}

		try {
			$res =  DatabaseHandler::GetAll($sql);

			return $res;
			
		} catch (Exception $e) {
			Logger::Log('Invoice', 'Exception', $e->getMessage());
			return null;
		}
		
	}

	public static function Delete($id)
	{
		try {
			$sql = 'DELETE FROM invoices WHERE id = '.$id;			
			DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			
		}
	}
}

class SalesVoucher
{
	public $id;
	public $type;
	public $transactionId;
	public $party;
	public $client;
	public $date;
	public $advices = [];
	public $description;
	public $tax;
	public $discount;
	public $amt;
	public $amount;
	public $total;
	public $status;
	public $extras;
	public $user;

	function __construct($id, $clientId, $date, $description, $amount, $tax, $discount, $total, $status, $quotes)
	{
		$this->id = $id;		
		$this->client = Client::GetClient($clientId);
		$this->party = $this->client;
		$this->date = $date;
		$this->tax = new Money(floatval($tax), Currency::Get('KES'));
		$this->discount = floatval($discount);
		$this->total = new Money(floatval($total), Currency::Get('KES'));
		$this->amt = new Money(floatval($amount), Currency::Get('KES'));
		$this->amount = floatval($total);
		$this->status = $status;
		$this->scope = $description;

		if (intval($quotes) != 0) {
			$quotes = explode(",", $quotes);
			foreach ($quotes as $qid) {
				$this->advices[] = Quotation::GetQuotation($qid);
			}
		}else{
			$this->advices[] = SalesInvoice::GetInvoice($this->id);
		}

		$this->description = '';
		foreach ($this->advices as $invoice) {
			foreach ($invoice->lineItems as $item) {
				$this->description .= $item->quantity.' x '.$item->itemName.' ('.$item->itemDesc.'), ';
			}
		}
		

		$extras = new stdClass();
   		$extras->amount = $this->amt->amount;
   		$extras->tax = $this->tax->amount;
   		$extras->discount = $this->discount;
   		$extras->total = $this->total->amount;
   		$extras->advices = $this->advices;
   		$this->extras = $extras;

   		try {
			$sql = 'SELECT * FROM vouchers WHERE voucher_id = '.$id.' AND tx_type LIKE "%Invoice%"';
			$res =  DatabaseHandler::GetRow($sql);
			$this->transactionId = $res['transaction_id'];
			$this->user = $res['cashier'];
			if (is_null($this->user)) {
				$this->user = SessionManager::GetUsername();
			}
			$this->type = $res['tx_type'];;

			$sql = 'SELECT * FROM general_ledger_entries WHERE transaction_id = '.intval($this->transactionId).' AND account_no = '.intval($clientId);
			$res2 =  DatabaseHandler::GetRow($sql);
			$this->party->balance = new Money(floatval($res2['balance']), Currency::Get('KES'));
		} catch (Exception $e) {
			Logger::Log(get_class($this), 'Exception', $e->getMessage());
		}
	}

	private static function initialize($args){
		$invoice =  new SalesVoucher($args['id'], $args['client_id'], $args['datetime'], $args['description'], $args['amount'], $args['tax'], $args['discount'], $args['total'], $args['status'], $args['quotes']);
		return $invoice;
	}

	public static function GetInvoice($id)
	{
		try {
			$sql2 = 'SELECT * FROM invoices WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql2);
			return self::initialize($res);
		} catch (Exception $e) {
			Logger::Log('SalesVoucher', 'Exception', $e->getMessage());
		}
		
	}

	public static function GetVoucher($txid)
	{
		try {
			$sql = 'SELECT * FROM vouchers WHERE transaction_id = '.$txid;
			$res =  DatabaseHandler::GetRow($sql);
			//echo json_encode($res);
			$sql2 = 'SELECT * FROM invoices WHERE id = '.intval($res['voucher_id']);
			$res2 =  DatabaseHandler::GetRow($sql2);
			//echo json_encode($res);
			if ($res2) {
				return self::initialize($res2);
			}else{
				Logger::Log('SalesVoucher', 'Exception', 'Missing invoice voucher for transaction id:'.$txid);
				return false;
			}
			
		} catch (Exception $e) {
			Logger::Log('SalesVoucher', 'Exception', $e->getMessage());
		}
		
	}
}

class ReceiptVoucher
{
	public $id;
	public $type;
	public $transactionId;
	public $party;
	public $date;
	public $description;
	public $amount;
	public $status;
	public $user;

	function __construct($id, $clientId, $date, $amount, $descr, $status)
	{
		$this->id = $id;		
		$this->party = Client::GetClient($clientId);
		$this->date = $date;
		$this->amount = floatval($amount);
		$this->description = $descr;
		$this->status = $status;
		try {
			$sql = 'SELECT * FROM vouchers WHERE voucher_id = '.$id.' AND tx_type LIKE "%Receipt%"';
			$res =  DatabaseHandler::GetRow($sql);
			$this->transactionId = $res['transaction_id'];
			$this->user = $res['cashier'];
			if (is_null($this->user)) {
				$this->user = SessionManager::GetUsername();
			}
			$this->type = $res['tx_type'];

			$sql = 'SELECT * FROM general_ledger_entries WHERE transaction_id = '.intval($this->transactionId).' AND account_no = '.intval($clientId);
			$res2 =  DatabaseHandler::GetRow($sql);
			$this->party->balance = new Money(floatval($res2['balance']), Currency::Get('KES'));
		} catch (Exception $e) {
			Logger::Log(get_class($this), 'Exception', $e->getMessage());
		}		
	}

	private static function initialize($args){
		$receipt =  new ReceiptVoucher($args['id'], $args['client_id'], $args['datetime'], $args['amount'], $args['description'], $args['status']);
		return $receipt;
	}

	public static function GetReceipt($id)
	{
		try {
			$sql = 'SELECT * FROM receipts WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);
			return self::initialize($res);
		} catch (Exception $e) {
			Logger::Log('ReceiptVoucher', 'Exception', $e->getMessage());
		}		
	}

	public static function GetVoucher($txid)
	{
		try {
			$sql = 'SELECT voucher_id FROM vouchers WHERE transaction_id = '.$txid;
			$res =  DatabaseHandler::GetOne($sql);
			$res2;
			if (!empty($res)) {
				$sql2 = 'SELECT * FROM receipts WHERE id = '.$res;
				$res2 =  DatabaseHandler::GetRow($sql2);
			}
			
			if ($res2) {
				return self::initialize($res2);
			}else{
				Logger::Log('ReceiptVoucher', 'Missing', 'Missing receipt voucher for transaction id:'.$txid);
				return false;
			}
		} catch (Exception $e) {
			Logger::Log('ReceiptVoucher', 'Exception', $e->getMessage());
		}			
	}
}

class QuotationVoucher
{
	public $id;
	public $type;
	public $transactionId;
	public $party;
	public $date;
	public $lineItems = [];
	public $description;
	public $amount;
	public $user;

	function __construct($quoteId, $date, $clientId, $amount, $tax, $total, $user)
	{
		$this->id = $quoteId;
		$this->transactionId = $quoteId;
		$this->date = $date;
		$this->type = 'Quotation';
		$this->party = Client::GetClient($clientId);
		$this->amount = floatval($amount);
		$this->tax = floatval($tax);
		$this->total = floatval($total);
		//$this->description = 'Quotation for '.$this->party->name;
		if (is_null($user)) {
			$this->user = SessionManager::GetUsername();
		}else{
			$this->user = $user;
		}
		
		$this->lineItems = QuotationLine::GetQuoteItems($quoteId);

		$this->description = '';
		foreach ($this->lineItems as $item) {
			$this->description .= $item->quantity.' x '.$item->itemName.' ('.$item->itemDesc.'), ';
		}
	}	

	public static function initialize($args){
		$quote =  new QuotationVoucher($args['id'], $args['date'], $args['client_id'], $args['amount'], $args['tax'], $args['total'], $args['user']);
		return $quote;
	}

	public static function GetQuotation($id)
	{
		$sql2 = 'SELECT * FROM quotations WHERE id = '.$id;
		$res =  DatabaseHandler::GetRow($sql2);
		return self::initialize($res);
	}
}

class ClientInvoice extends TransactionType
{

	function __construct($clientId, $name)
	{
		parent::__construct($name);
		
		$this->drAccounts[] = Account::GetAccountByNo($clientId, 'clients', 'Debtors');
		$this->drRatios[] = 1;
		$this->crAccounts[] = Account::GetAccount('Sales', 'ledgers');
		$this->crRatios[] = 1;
	}
}

class SalesTX extends FinancialTransaction
{
	public $invoice;

	function __construct($invoice, $invoiceType)
	{
		$this->invoice = $invoice;
		$txtype = new ClientInvoice($invoice->clientId, $invoiceType);
		parent::__construct($invoice->total, $invoice->description, $txtype);
	}

	public function post()
	{
		if ($this->prepare()) {
			$voucher = TransactionProcessor::ProcessSalesTX($this);
			if ($voucher) {

				if ($this->invoice->quoteIds != null && $this->invoice->quoteIds != '') {
					$this->invoice->loadQuotes();
					foreach ($this->invoice->quotations as $quote) {
						$quote->setInvoiced();
					}
				}
				
				if ($this->invoice->projectId != 0) {
					$project = Project::GetProject($this->invoice->projectId);
					$project->debit($this->amount);
				}
				//TX successful
				$this->invoice->updateStatus(1);
				
				$extras = new stdClass();
   				$extras->amount = $this->invoice->amount->amount;
   				$extras->tax = $this->invoice->taxamt->amount;
   				$extras->discount = $this->invoice->discount;
   				$extras->total = $this->invoice->total->amount;

				//$voucher->setExtras($extras);
				return $voucher;
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

	public static function RaiseQuotationInvoice($clientId, $scope, $quotes, $discount)
	{
		$client = Client::GetClient($clientId);

		if ($scope == "G") {
			$descr = "General Services";
			$pid = 0;
		}else{
			$prj = Project::GetProject(intval($scope));
			$descr = $prj->name.' Project';
			$pid = intval($scope);
		}

		$qids = implode(",", $quotes);

		$invoice = SalesInvoice::CreateInvoice($client, $pid, $qids, $descr, $discount);		

		foreach ($quotes as $qid) {
			$quotation = Quotation::GetQuotation($qid);
			foreach ($quotation->lineItems as $item) {
		    	$invoice->addToInvoice(SalesInvoiceLine::Create($invoice->id, $item->itemName, $item->itemDesc, $item->quantity, $item->unitPrice, $item->tax));
			}			
		}

		if ($invoice->generate()) {
			return new SalesTX($invoice, 'Quotation Invoice');
		}else{
			Logger::Log('SalesTX', 'Failed', 'Quotation invoice transaction with id:'.$invoice->id.' and tx id:'.$this->transactionId.' could not be completed');
			return false;
		}		
	}

	public static function RaiseGeneralInvoice($clientId, $scope, $items, $discount)
	{
		$client = Client::GetClient($clientId);

		if ($scope == "G") {
			$descr = "General Services";
			$pid = 0;
		}else{
			$prj = Project::GetProject(intval($scope));
			$descr = $prj->name.' Project';
			$pid = intval($scope);
		}

		$quotes = null;

		$invoice = SalesInvoice::CreateInvoice($client, $pid, $quotes, $descr, $discount);

		foreach ($items as $item) {
		    $invoice->addToInvoice(SalesInvoiceLine::Create($invoice->id, $item['service'], $item['task'], $item['qty'], $item['price'], $item['tax']));
		}

		if ($invoice->generate()) {
			return new SalesTX($invoice, 'General Invoice');
		}else{
			Logger::Log('SalesTX', 'Failed', 'General invoice transaction with id:'.$invoice->id.' and tx id:'.$this->transactionId.' could not be completed');
			return false;
		}		
	}

	public static function RaiseSalesArrearsInvoice($clientId, $amount)
	{
		$client = Client::GetClient($clientId);
		$descr = "Sales balance B/F";
		$pid = 0;
		$discount = 0;
		$quotes = null;

		$invoice = SalesInvoice::CreateInvoice($client, $pid, $quotes, $descr, $discount);

		$invoice->addToInvoice(SalesInvoiceLine::Create($invoice->id, 'Balances brought forward', 'System migration', 1, $amount->amount, 0));

		if ($invoice->generate()) {
			return new SalesTX($invoice, 'Sales Balance B/F Invoice');
		}else{
			Logger::Log('SalesTX', 'Failed', 'Sales balance B/F invoice transaction with id:'.$invoice->id.' and tx id:'.$this->transactionId.' could not be completed');
			return false;
		}		
	}
}

class SalesReceipt extends TransactionType
{

	function __construct($ledgerId, $clientId)
	{
		parent::__construct("Sales Receipt");
		
		$this->drAccounts[] = Account::GetLedger($ledgerId);
		$this->drRatios[] = 1;
		$this->crAccounts[] = Account::GetAccountByNo($clientId, 'clients', 'Debtors');
		$this->crRatios[] = 1;

		//this info should originate from the database, insert ignore protocol included
		//$this->code = self::GetCode('PayPal');
		//parent::__construct();
	}
}

class ReceiptTX extends FinancialTransaction
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
		$txtype = new SalesReceipt($ledgerId, $clientId);
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
			$voucher = TransactionProcessor::ProcessReceipt($this);
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
		$payment =  new ReceiptTX($args['id'], $args['client_id'], $args['project_id'], $args['voucher_no'], $args['amount'], $args['ledger_id'], $args['description'], $args['status']);
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

?>