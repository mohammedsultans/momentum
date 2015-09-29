<?php

//require_once('Services.php');
//require_once('Quotation.php');
//require_once('SalesOrder.php');
//require_once('PurchaseOrder.php');
//require_once('Invoicing.php');
//require_once('Receipting.php');
//require_once('Vouchers.php');
//require_once('Expenses.php');// claims, purchases, bills, salaries, loan interests

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

class InvoiceLine
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

	public static function Create($invoiceId, $itemName, $itemDesc, $quantity, $unitPrice, $tax)
	{
		$lineItem = new InvoiceLine($invoiceId, $itemName, $itemDesc, $quantity, $unitPrice, $tax);		
		$lineItem->save();
		return $lineItem;
	}

	public static function GetLineItems($invoiceId)
	{
		//check whether available and make necessary inventory deductions, then
		$lineItems = array();
		try {
			$sql = 'SELECT * FROM quotation_items WHERE quote_id = '.$invoiceId.' AND status = 1';
			$res =  DatabaseHandler::GetAll($sql);

			foreach ($res as $item) {
				$lineItem = new InvoiceLine($item['quote_id'], $item['item_name'], $item['item_desc'], $item['quantity'], $item['unit_price'], $item['tax']);
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

class Invoice
{
	public $id;
	public $date;
	public $lineItems = array();
	public $items;
	public $taxamt;
	public $amount;
	public $discount;
	public $total;
	public $status;
	public $client;
	public $extras;

	function __construct($quoteId, $date, $status, $client)
	{
		$this->id = $quoteId;
		$this->date = $date;
		$this->status = $status;
		$this->client = $client;
	}

	public function initialize()
	{
		$this->lineItems = InvoiceLine::GetLineItems($this->id);
		$this->generate();
	}

	public function initRecipient($partyId)
	{
		$this->clientId = $partyId;
	}


	public function addToInvoice(InvoiceLine $lineItem)
	{
		array_push($this->lineItems, $lineItem);
		//$this->lineItems[] = $orderItem;
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
		

		try {
			//status: 0 - unauthorized, 1 - awaiting shipment, 3 - dispatched, 4 - delivered
			$sql = 'UPDATE invoices SET items = '.$items.', amount = '.$amount.', total = '.$total.', tax = '.$taxamt.' WHERE id = '.$this->id;
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
			$sql = 'DELETE FROM invoices WHERE id = '.$this->id;			
			DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			
		}
	}

	public static function CreateInvoice($client)
	{
		//Called and stored in a session object
		try {
			
			$datetime = new DateTime();
			$sql = 'INSERT INTO invoices (client_id, date, stamp, status) VALUES ("'.$client->id.'","'.$datetime->format('Y/m/d').'", '.$datetime->format('YmdHis').', 1)';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql = 'SELECT * FROM invoices WHERE stamp = '.$datetime->format('YmdHis');
			$res =  DatabaseHandler::GetRow($sql);

			return new Quotation($res['id'], $res['date'], $res['status'], $client);

		} catch (Exception $e) {
			
		}
	}

	public function setProject($projectId)
	{
		if ($this->status != 2) {
			try {
				$sql = 'UPDATE invoices SET project_id = '.$projectId.', status = 2 WHERE id = '.$this->id;
		 		DatabaseHandler::Execute($sql);
		 		$this->projectId = $projectId;
		 		$this->status = 2;
			} catch (Exception $e) {
				
			}
		}
		
	}

	public function payInvoice()
	{
				
	}

	public static function GetInvoice($id)
	{
		try {
			$sql = 'SELECT * FROM invoices WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);
			if (!empty($res['date'])) {
				$client = Client::GetClient($res['client_id']);
				$invoice = new Invoice($res['id'], $res['date'], $res['status'], $client);
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
				if (!empty($item['date'])) {
					$invoice = new Quotation($item['id'], $item['date'], $item['status'], $client);
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
				if (!empty($item['date'])) {
					$invoice = new Invoice($item['id'], $item['date'], $item['status'], $client);
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
			$invoice = [];
			foreach ($res as $item) {
				if (!empty($item['date'])) {
					$invoice = new Invoice($item['id'], $item['date'], $item['status'], $client);
					$invoice->initialize();
					$invoice[] = $invoice;
				}else{
					
				}
			}
			
			return $invoice;
			
		} catch (Exception $e) {
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
	public $user;

	function __construct($id, $txTypeName, $txId, $amount, $description, $date, $stamp)
	{		
		$this->id = $id;
		$this->type = $txTypeName;
		$this->transactionId = $txId;
		$this->amount = $amount;
		$this->description = $description;
		$this->date = $date;
		$this->stamp = $stamp; 
		$this->user = SessionManager::GetUsername(); 
	}

	public function persist(){
		try {

			$sql = 'INSERT INTO vouchers (voucher_id, tx_type, transaction_id, amount, description, datetime, stamp, cashier) 
			VALUES ('.$this->id.', "'.$this->type.'", '.$this->transactionId.', '.$this->amount.', "'.$this->description.'", "'.$this->date.'", '.$this->stamp.', "'.$this->user.'")';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql2 = 'SELECT * FROM vouchers WHERE transaction_id = '.$this->transactionId;
			$res =  DatabaseHandler::GetRow($sql2);

			$this->id = $res['voucher_id'];

		} catch (Exception $e) {
			Logger::Log(get_class($this), 'Exception', $e->getMessage());
		}
	}

	public function setClient($id){
		$this->party = Client::GetClient($id);
	}

	public function setExtras($extras){
		$this->extras = $extras;
	}

	private static function initialize($args){
		return new Voucher($args['voucher_id'], $args['tx_type'], $args['transaction_id'], $args['amount'], $args['description'], $args['date'], $args['stamp'], $args['cashier']);
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
			Logger::Log(get_class($this), 'Exception', $e->getMessage());
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

class InvoiceVoucher
{
	public $id;
	public $type;
	public $txid;
	public $party;
	public $client;
	public $date;
	public $quotations = [];
	public $description;
	public $tax;
	public $discount;
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
		$this->amount = floatval($amount);
		$this->status = $status;
		$this->description = $description;

		$quotes = explode(",", $quotes);
		foreach ($quotes as $qid) {
			$this->quotations[] = Quotation::GetQuotation($qid);
		}

		$extras = new stdClass();
   		$extras->amount = $this->amount;
   		$extras->tax = $this->tax->amount;
   		$extras->discount = $this->discount;
   		$extras->total = $this->total->amount;
   		$extras->quotations = $this->quotations;
   		$this->extras = $extras;

   		try {
			$sql = 'SELECT * FROM vouchers WHERE voucher_id = '.$id.' AND tx_type LIKE "%Invoice%"';
			$res =  DatabaseHandler::GetRow($sql);
			$this->txid = $res['transaction_id'];
			$this->user = $res['cashier'];
			$this->type = $res['tx_type'];;

			$sql = 'SELECT * FROM general_ledger_entries WHERE transaction_id = '.intval($this->txid).' AND account_no = '.intval($clientId);
			$res2 =  DatabaseHandler::GetRow($sql);
			$this->party->balance = new Money(floatval($res2['balance']), Currency::Get('KES'));
		} catch (Exception $e) {
			Logger::Log(get_class($this), 'Exception', $e->getMessage());
		}
	}

	private static function initialize($args){
		$invoice =  new InvoiceVoucher($args['id'], $args['client_id'], $args['datetime'], $args['description'], $args['amount'], $args['tax'], $args['discount'], $args['total'], $args['status'], $args['quotes']);
		return $invoice;
	}

	public static function GetInvoice($id)
	{
		try {
			$sql2 = 'SELECT * FROM invoices WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql2);
			return self::initialize($res);
		} catch (Exception $e) {
			Logger::Log('InvoiceVoucher', 'Exception', $e->getMessage());
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
				Logger::Log('InvoiceVoucher', 'Exception', 'Missing invoice voucher for transaction id:'.$txid);
				return false;
			}
			
		} catch (Exception $e) {
			Logger::Log('InvoiceVoucher', 'Exception', $e->getMessage());
		}
		
	}
}

class ReceiptVoucher
{
	public $id;
	public $type;
	public $txid;
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
			$this->txid = $res['transaction_id'];
			$this->user = $res['cashier'];
			$this->type = $res['tx_type'];

			$sql = 'SELECT * FROM general_ledger_entries WHERE transaction_id = '.intval($this->txid).' AND account_no = '.intval($clientId);
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
			$sql2 = 'SELECT * FROM receipts WHERE id = '.$res;
			$res2 =  DatabaseHandler::GetRow($sql2);
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
	public $txid;
	public $party;
	public $date;
	public $lineItems = [];
	public $description;
	public $amount;
	public $user;

	function __construct($quoteId, $date, $clientId, $amount, $tax, $total, $user)
	{
		$this->id = $quoteId;
		$this->txid = $quoteId;
		$this->date = $date;
		$this->type = 'Quotation';
		$this->party = Client::GetClient($clientId);
		$this->amount = floatval($amount);
		$this->tax = floatval($tax);
		$this->total = floatval($total);
		$this->description = 'Quotation for client';
		$this->user = $user;
		$this->lineItems = QuotationLine::GetQuoteItems($quoteId);
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

class TransactionVouchers extends Artifact
{
	public static function GetClientTransactions($cid, $category, $dates, $all)
	{
		if ($category == 1) {//Statement
			if ($all == 'true'){
				$sql = 'SELECT * FROM general_ledger_entries WHERE account_no = '.intval($cid).' ORDER BY id DESC';
			}else if($dates != ''){
				$split = explode(' - ', $dates);
		    	$d1 = explode('/', $split[0]);
		    	$d2 = explode('/', $split[1]);
		    	$lower = $d1[2].$d1[0].$d1[1].'000000' + 0;
		    	$upper = $d2[2].$d2[0].$d2[1].'999999' + 0;
		    	$sql = 'SELECT * FROM general_ledger_entries WHERE account_no = '.intval($cid).' AND stamp BETWEEN '.$lower.' AND '.$upper.' ORDER BY id DESC';
			}

			try {
				$res = DatabaseHandler::GetAll($sql);
				$vouchers = [];
				foreach ($res as $tx) {
					if ($tx['effect'] == 'cr') {
						$voucher = ReceiptVoucher::GetVoucher(intval($tx['transaction_id']));
						if ($voucher) {
							$vouchers[] = $voucher;
						}	
					}else{
						$voucher = InvoiceVoucher::GetVoucher(intval($tx['transaction_id']));
						if ($voucher) {
							$vouchers[] = $voucher;
						}						
					}
				}

				return $vouchers;
			} catch (Exception $e) {
				
			}
		}else{//Quotations
			if ($all == 'true'){
				$sql = 'SELECT * FROM quotations WHERE client_id = '.intval($cid).' ORDER BY id DESC';
			}else{
				$split = explode(' - ', $dates);
		    	$d1 = explode('/', $split[0]);
		    	$d2 = explode('/', $split[1]);
		    	$lower = $d1[2].$d1[0].$d1[1].'000000' + 0;
		    	$upper = $d2[2].$d2[0].$d2[1].'999999' + 0;
		    	$sql = 'SELECT * FROM quotations WHERE client_id = '.intval($cid).' AND stamp BETWEEN '.$lower.' AND '.$upper.' ORDER BY id DESC';
			}

			try {
				$res = DatabaseHandler::GetAll($sql);
				$vouchers = [];
				foreach ($res as $quote) {
					$vouchers[] = QuotationVoucher::initialize($quote);
				}

				return $vouchers;
			} catch (Exception $e) {
				
			}
		}
	}	
}

class SalesInvoice extends TransactionType
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

class QuotationInvoiceTX extends FinancialTransaction
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
		$txtype = new SalesInvoice($clientId, 'Quotation Invoice');
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
	        Logger::Log(get_class($this), 'Exception', $e->getMessage());
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
		$invoice =  new QuotationInvoiceTX($args['id'], $args['client_id'], $args['project_id'], $args['description'], $args['amount'], $args['tax'], $args['discount'], $args['total'], $args['status']);
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

class GeneralInvoiceTX extends FinancialTransaction
{
	public $id;
	public $clientId;
	public $invoice;
	public $description;
	public $tax;
	public $discount;
	public $amt;
	public $total;
	public $status;

	function __construct($id, $clientId, $description, $amount, $tax, $discount, $total, $status)
	{
		$this->id = $id;
		$this->clientId = $clientId;
		$this->tax = new Money(floatval($tax), Currency::Get('KES'));
		$this->discount = floatval($discount);
		$this->total = new Money(floatval($total), Currency::Get('KES'));
		$this->amt = new Money(floatval($amount), Currency::Get('KES'));
		$this->status = $status;
		$txtype = new SalesInvoice($clientId, 'General Invoice');
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

				$voucher->setExtras($extras);
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

	private function updateStatus()
	{
		try {

			$sql = 'UPDATE invoices SET status = '.$this->status.' WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);	 		

		} catch (Exception $e) {
			
		}
	}

	private static function initialize($args){
		$invoice =  new GeneralInvoiceTX($args['id'], $args['client_id'], $args['description'], $args['amount'], $args['tax'], $args['discount'], $args['total'], $args['status']);
		return $invoice;
	}


	public static function RaiseInvoice($clientId, $amount, $tax, $discount, $total)
	{
		//Defer to invoice class to persist details
		try {
			
			$datetime = new DateTime();
			#status 0 - awaiting payment, 1- partially paid, 2-overdue, 3-paid
			$ref = "General Invoice";
			$pid = 0;

			$sql = 'INSERT INTO invoices (client_id, amount, tax, discount, total, description, status) VALUES 
			('.$clientId.', '.$amount.', '.$tax.', '.$discount.', '.$total.', "'.$ref.'", 0)';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql2 = 'SELECT * FROM invoices WHERE client_id = '.$clientId.' ORDER BY id DESC LIMIT 0,1';
			$res =  DatabaseHandler::GetRow($sql2);

			return self::initialize($res);

		} catch (Exception $e) {
			
		}
	}
}

class CreditReceipt extends TransactionType
{

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

class SalesArrearsInvoiceTX extends FinancialTransaction
{
	public $clientId;

	function __construct($clientId, $amount)
	{
		$this->clientId = $clientId;
		//$this->amount = $amount; - Money class
		//$this->description = "Balance brought forward";
		//$txtype = new BalanceBF($clientId);
		$txtype = new SalesInvoice($clientId, 'Sales Balance B/F Invoice');
		parent::__construct($amount, "Sales balance brought forward", $txtype);
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

class ExpenseItem
{//Refactor to ClaimItem
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
{//Refactor to ClaimVoucher
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
{

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

class PurchaseInvoice extends TransactionType
{
	function __construct($supplierId, $name)
	{
		parent::__construct($name);
		
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
		$txtype = new PurchaseInvoice($supplierId, 'Purchases Balance B/F');
		parent::__construct($amount, "Purchases balance brought forward", $txtype);
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
?>