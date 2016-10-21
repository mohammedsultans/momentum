<?php

//require_once('Services.php');

class Supplier extends Party
{
  	public $person;
  	public $accounts = array();
  	public $balance;
  	//public $stockAccountNumber;
  	function __construct($id, $name, $person, $telephone, $email, $address, $bal)
  	{
  		$this->person = $person;
  		$type = new PartyType('Supplier');
  		$this->balance = new Money(floatval($bal), Currency::Get('KES'));
  		parent::__construct($type, $id, $name, $telephone, $email, $address);
  	}

  	public static function Update($id, $name, $person, $telephone, $email, $address)
  	{      	
  		try {
	        $sql = 'UPDATE suppliers SET name = "'.$name.'", person = "'.$person.'", telephone = "'.$telephone.'", email = "'.$email.'", address = "'.$address.'" WHERE id = '.$id;
	        DatabaseHandler::Execute($sql);
	        return true;
	    } catch (Exception $e) {
	        
	    }
  	}

  	public static function Create($name, $person, $telephone, $email, $address, $bal)
  	{      	
  		$type = new PartyType('Supplier');
		$supplier = new Supplier($type, $name, $person, $telephone, $email, $address, $bal);
		
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
        	$statement = TransactionVouchers::SupplierStatement($id, '', 'true');
        	if (!empty($statement) && count($statement) > 0 ) {
        		return false;
        	}else{
        		$sql = 'DELETE FROM suppliers WHERE id = '.intval($id);
        		$res =  DatabaseHandler::Execute($sql);
        		return true;
        	}
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

      $party = new Supplier($args['id'], $args['name'], $args['person'], $args['telephone'], $args['email'], $args['address'], $args['balance']);
      
      return $party;
    }

    private function save()
    {
      try {
        $sql = 'SELECT * FROM suppliers WHERE name = "'.$this->name.'"';
	    // Execute the query and return the results
	    $res =  DatabaseHandler::GetRow($sql);
	    if (!empty($res['id'])) {
	    	return false;
	    }else{
	    	$sql = 'INSERT INTO suppliers (type, name, person, telephone, address, email) 
	        VALUES ("'.$this->type->name.'", "'.$this->name.'", "'.$this->person.'", "'.$this->telephone.'", "'.$this->address.'", "'.$this->email.'")';
	        DatabaseHandler::Execute($sql);
	        if ($this->balance->amount != 0) {
	        	$sql = 'SELECT * FROM suppliers WHERE name = "'.$this->name.'" AND telephone = "'.$this->telephone.'"';
		        // Execute the query and return the results
		        $res =  DatabaseHandler::GetRow($sql);
	        	return $this->transferBalance($res['id'], $this->balance);
	        }else{
	        	return true;
	        }
	    }
      } catch (Exception $e) {
        return false;
      }

    }

    private function transferBalance($supplierId, $amount)
    {      
    	$transfer = PurchaseTX::TransferArrears($supplierId, $amount);
    	return $transfer->post();
    }
}

class SystemVendor extends Supplier
{
	//Refactor vendor to suppliers
  	public $website;
	public $contactPerson;

	function __construct($id, $name, $telephone, $email, $address, $city, $country, $website, $contact)
	{
		//$type = new PartyType('SystemVendor');
		//parent::__construct($type, $id, $name, $telephone, $email, $address);
		parent::__construct($id, $name, $contact, $telephone, $email, $address, 0);
		$this->city = $city;
		$this->country = $country;
		$this->website = $website;
		$this->contactPerson = $contact;//PartyType('Account Manager');
	}

	public static function GetVendor()
	{
		return new SystemVendor(1, 'QET Systems Ltd.', '0727596626', 'support@qet.co.ke', 'Kigio Plaza 3rd Fl, Box 7685-01000, Thika CBD', 'Thika', 'Kenya', 'www.qet.co.ke', 'Alex Mbaka');
		//return new SystemVendor(1, 'Fractal Systems Ltd.', '0727596626', 'info@fractalsystems.co.ke', 'Kigio Plaza 3rd Fl, Box 7685-01000, Thika CBD', 'Thika', 'Kenya', 'www.fractalsystems.co.ke', 'Alex Mbaka');
	}
}

class PurchaseOrderLine
{
	public $lineId;
	public $orderId;
	public $itemName;
	public $quantity;
	public $unitPrice;

	function __construct($orderId, $itemName, $quantity, $unitPrice)
	{
		$this->orderId = $orderId;
		$this->itemName = $itemName;
		$this->quantity = intval($quantity);
		$this->unitPrice = floatval($unitPrice);
		//$var = '37152548';number_format($var / 100, 2, ".", "") == 371525.48 ;
	}

	public function initId($id)
  	{
      	$this->lineId = $id;  		
  	}

	public static function Create($orderId, $itemName, $quantity, $unitPrice)
	{
		$lineItem = new PurchaseOrderLine($orderId, $itemName, $quantity, $unitPrice);		
		$lineItem->save();
		return $lineItem;
	}

	public static function GetOrderItems($orderId)
	{
		//check whether available and make necessary inventory deductions, then
		$lineItems = array();
		try {
			$sql = 'SELECT * FROM purchase_order_items WHERE order_id = '.$orderId.' AND status = 1';
			$res =  DatabaseHandler::GetAll($sql);

			foreach ($res as $item) {
				$lineItem = new PurchaseOrderLine($item['order_id'], $item['item_name'], $item['quantity'], $item['unit_price']);
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
      		$sql = 'INSERT INTO purchase_order_items (order_id, item_name, quantity, unit_price, stamp) 
      		VALUES ('.$this->orderId.', "'.$this->itemName.'", '.$this->quantity.', '.$this->unitPrice.', '.$stamp.')';
	 		DatabaseHandler::Execute($sql);
	 		//get lineId???? and set to object

      	} catch (Exception $e) {
      		
      	}
  	}

  	public static function DiscardLine($id)
    {
      try {
      	$sql = 'UPDATE purchase_order_items SET status = 0 WHERE id = '.$id;
        DatabaseHandler::Execute($sql);
        //recalculate quotation value
      } catch (Exception $e) {
        
      }

    }
}

class PurchaseOrder 
{
	public $id;
	public $date;
	public $lineItems = array();
	public $items;
	public $total;
	public $status;
	public $party;
	public $projectId;
	public $user;

	function __construct($orderId, $date, $status, $party, $user)
	{
		$this->id = $orderId;
		$this->date = $date;
		$this->status = $status;
		$this->party = $party;
		$this->user = $user;
	}

	public function initializeOrder()
	{
		$this->lineItems = PurchaseOrderLine::GetOrderItems($this->id);
		$this->generate();
	}

	public function initRecipient($partyId)
	{
		$this->partyId = $partyId;
	}

	public function initProject($projectId)
	{
		$this->projectId = $projectId;
	}


	public function addToOrder(PurchaseOrderLine $orderItem)
	{
		array_push($this->lineItems, $orderItem);
		//$this->lineItems[] = $orderItem;
	}

	public function removeFromOrder($lineId)
	{

	}

	public function generate()
	{
		$total = 0.00;
		$items = 0;

		foreach ($this->lineItems as $orderLine) {
			$lineItemAmount = ($orderLine->quantity * $orderLine->unitPrice);
			$total = $total + $lineItemAmount;
			$items = $items + $orderLine->quantity;
		}
		//$taxamt = $amount * $tax/100;
		//$total = $amount + $taxamt;
		

		try {
			//status: 0 - unauthorized, 1 - awaiting shipment, 3 - dispatched, 4 - delivered
			$sql = 'UPDATE purchase_orders SET items = '.$items.', total = '.$total.' WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);
	 		$this->total = $total;
			$this->items = $items;
			//$this->status = 1;
			return $this;
		} catch (Exception $e) {
			Logger::Log(get_class($this), 'Exception', $e->getMessage());
			return false;
		}
	}

	public function discard()
	{
		try {
			$sql = 'DELETE FROM purchase_orders WHERE id = '.$this->id;			
			DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			Logger::Log(get_class($this), 'Exception', $e->getMessage());
		}
	}	

	public function setProject($projectId)
	{
		if ($this->status != 2) {
			try {
				$sql = 'UPDATE purchase_orders SET project_id = '.$projectId.', status = 2 WHERE id = '.$this->id;
		 		DatabaseHandler::Execute($sql);
		 		$this->projectId = $projectId;
		 		$this->status = 2;
			} catch (Exception $e) {
				Logger::Log(get_class($this), 'Exception', $e->getMessage());
			}
		}		
	}

	public function setPurchased()
	{
		if ($this->status != 3) {
			try {
				$sql = 'UPDATE purchase_orders SET status = 3 WHERE id = '.$this->id;
		 		DatabaseHandler::Execute($sql);
		 		$this->status = 3;
			} catch (Exception $e) {
				Logger::Log(get_class($this), 'Exception', $e->getMessage());
			}
		}		
	}

	private static function initialize($args, $supplier){
		$order = new PurchaseOrder($args['id'], $args['date'], $args['status'], $supplier, $args['user']);
		$order->initializeOrder();
		return $order;
	}

	public static function CreateOrder($supplier, $date)
	{
		//Called and stored in a session object
		try {			
			$datetime = new DateTime();
			$sql = 'INSERT INTO purchase_orders (supplier_id, date, stamp, status, user) VALUES ("'.$supplier->id.'","'.$datetime->format('Y/m/d').'", '.$datetime->format('YmdHis').', 1, "'.SessionManager::GetUsername().'")';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql = 'SELECT * FROM purchase_orders WHERE stamp = '.$datetime->format('YmdHis');
			$res =  DatabaseHandler::GetRow($sql);

			return self::initialize($res, $supplier);

		} catch (Exception $e) {
			Logger::Log('PurchaseOrder', 'Exception', $e->getMessage());
		}
	}

	public static function GetOrder($id)
	{
		try {
			$sql = 'SELECT * FROM purchase_orders WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);
			if (!empty($res['date'])) {
				$supplier = Supplier::GetSupplier($res['supplier_id']);
				$order = self::initialize($res, $supplier);
				if (!empty($res['project_id'])) {
					$order->initProject($res['project_id']);
				}
				return $order;
			}else{
				Logger::Log('PurchaseOrder', 'Missing', 'Missing purchase order with id:'.$id);
				return null;
			}
			
		} catch (Exception $e) {
			Logger::Log('PurchaseOrder', 'Exception', $e->getMessage());
			return null;
		}
		
	}

	public static function GetProjectOrders($id, $supplier)
	{
		try {
			$sql = 'SELECT * FROM purchase_orders WHERE project_id = '.$id;
			$res =  DatabaseHandler::GetAll($sql);
			$orders = [];
			foreach ($res as $item) {
				if (!empty($item['date'])) {
					$order = self::initialize($item, $supplier);
					$order->initProject($item['project_id']);
					$orders[] = $order;
				}else{
					
				}
			}
			
			return $orders;
			
		} catch (Exception $e) {
			Logger::Log('Orders', 'Exception', $e->getMessage());
			return null;
		}
		
	}

	public static function GetSupplierOrders($supplierid)
	{
		try {
			$supplier = Supplier::GetSupplier($supplierid);
			$sql = 'SELECT * FROM purchase_orders WHERE supplier_id = '.$supplierid.' AND status = 1';
			$res =  DatabaseHandler::GetAll($sql);
			$orders = [];
			foreach ($res as $item) {
				if (!empty($item['date'])) {
					$orders[] = self::initialize($item, $supplier);
				}else{
					
				}
			}
			
			return $orders;
			
		} catch (Exception $e) {
			Logger::Log('PurchaseOrder', 'Exception', $e->getMessage());
			return null;
		}
		
	}

	public static function GetGeneralOrders($supplierid)
	{
		try {
			$supplier = Supplier::GetSupplier($supplierid);
			$sql = 'SELECT * FROM purchase_orders WHERE party_id = '.$supplierid.' AND status = 1 AND isnull(project_id)';
			$res =  DatabaseHandler::GetAll($sql);
			$orders = [];
			foreach ($res as $item) {
				if (!empty($item['date'])) {
					$orders[] = self::initialize($item, $supplier);
				}else{
					
				}
			}
			
			return $orders;
			
		} catch (Exception $e) {
			Logger::Log('PurchaseOrder', 'Exception', $e->getMessage());
			return null;
		}
		
	}

	public static function GetAllOrders($dates, $all)
	{
		if ($all == 'true'){
			$sql = 'SELECT * FROM purchase_orders';
		}else{
			$split = explode(' - ', $dates);
		    $d1 = explode('/', $split[0]);
		    $d2 = explode('/', $split[1]);
		    $lower = $d1[2].$d1[1].$d1[0].'000000' + 0;
		    $upper = $d2[2].$d2[1].$d2[0].'999999' + 0;
		    $sql = 'SELECT * FROM purchase_orders WHERE stamp BETWEEN '.$lower.' AND '.$upper.'';
		}

		try {

			$res =  DatabaseHandler::GetAll($sql);
			$orders = [];
			foreach ($res as $item) {
				if (!empty($item['date'])) {
					$supplier = Supplier::GetSupplier($item['supplier_id']);
					$orders[] = self::initialize($item, $supplier);
				}else{
					
				}
			}
			
			return $orders;
			
		} catch (Exception $e) {
			Logger::Log('Purchase Order', 'Exception', $e->getMessage());
			return null;
		}
		
	}

	public static function Delete($id)
	{
		try {
			$sql = 'DELETE FROM purchase_orders WHERE id = '.$id;			
			DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			Logger::Log('PurchaseOrder', 'Exception', $e->getMessage());
		}
	}
}

class PurchaseInvoiceLine
{
	public $lineId;
	public $invoiceId;
	public $itemId;
	public $itemName;
	public $quantity;
	public $unitPrice;//Money class - price per part
	public $tax;
	public $discount;
	public $ledgerId;

	function __construct($invoiceId, $itemName, $quantity, $unitPrice, $tax, $discount, $ledgerId)
	{
		$this->invoiceId = $invoiceId;
		$this->itemName = $itemName;
		$this->quantity = intval($quantity);
		$this->unitPrice = floatval($unitPrice);
		$this->tax = floatval($tax);
		$this->discount = floatval($discount);
		$this->ledgerId = $ledgerId;
		//$var = '37152548';number_format($var / 100, 2, ".", "") == 371525.48 ;
	}

	public function initId($id)
  	{
      	$this->lineId = $id;  		
  	}

	public static function Create($invoiceId, $itemName, $quantity, $unitPrice, $tax, $discount, $ledgerId)
	{
		$lineItem = new PurchaseInvoiceLine($invoiceId, $itemName, $quantity, $unitPrice, $tax, $discount, $ledgerId);		
		$lineItem->save();
		return $lineItem;
	}

	public static function GetLineItems($invoiceId)
	{
		//check whether available and make necessary inventory deductions, then
		$lineItems = array();
		try {
			$sql = 'SELECT * FROM purchase_invoice_items WHERE invoice_id = '.$invoiceId.' AND status = 1';
			$res =  DatabaseHandler::GetAll($sql);

			foreach ($res as $item) {
				$lineItem = new PurchaseInvoiceLine($item['invoice_id'], $item['item_name'], $item['quantity'], $item['unit_price'], $item['tax'], $item['disc'], $item['ledger_id']);
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
      		$sql = 'INSERT INTO purchase_invoice_items (invoice_id, item_name, quantity, unit_price, tax, disc, ledger_id, stamp) 
      		VALUES ('.$this->invoiceId.', "'.str_replace('"',"'",$this->itemName).'", '.$this->quantity.', '.$this->unitPrice.', '.$this->tax.', '.$this->discount.', '.$this->ledgerId.', '.$stamp.')';
	 		DatabaseHandler::Execute($sql);
	 		//get lineId???? and set to object

      	} catch (Exception $e) {
      		
      	}
  	}

  	public static function DiscardLine($id)
    {
      try {
      	$sql = 'UPDATE purchase_invoice_items SET status = 0 WHERE id = '.$id;
        DatabaseHandler::Execute($sql);
        //recalculate quotation value
      } catch (Exception $e) {
        
      }

    }
}

class PurchaseInvoice
{
	public $id;
	public $projectId;
	public $invno;
	public $description;
	public $date;
	public $lineItems = array();
	public $items;
	public $amount;
	public $taxamt;
	public $discamt;	
	public $total;
	public $status;
	public $supplierId;
	public $extras;
	public $orderIds;
	public $orders = [];
	public $balance;

	function __construct($invoiceId, $projectId, $orderIds, $invno, $description, $date, $status, $supplier)
	{
		$this->id = $invoiceId;
		$this->projectId = $projectId;
		$this->invno = $invno;
		$this->description = $description;		
		$this->date = $date;
		$this->status = $status;
		$this->supplierId = $supplier->id;
		$this->orderIds = $orderIds;
	}

	public function initialize($balance)
	{
		$this->lineItems = PurchaseInvoiceLine::GetLineItems($this->id);
		$this->calculate($balance);
	}

	public function addToInvoice(PurchaseInvoiceLine $lineItem)
	{
		array_push($this->lineItems, $lineItem);
		//$this->lineItems[] = $orderItem;
	}

	public function loadOrders()
	{
		$orders = explode(",", $this->orderIds);
		foreach ($orders as $oid) {
			$this->orders[] = PurchaseOrder::GetOrder($oid);
		}
	}

	public function removeFromInvoice($lineId)
	{

	}

	public function generate()
	{
		$this->calculate('full');

		try {
			//status: 0 - unauthorized, 1 - awaiting shipment, 3 - dispatched, 4 - delivered
			$sql = 'UPDATE purchase_invoices SET items = '.$this->items.', amount = '.$this->amount->amount.', tax = '.$this->taxamt->amount.', discount = '.$this->discamt->amount.', total = '.$this->total->amount.' , balance = '.$this->total->amount.' WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);
			//$this->status = 1;
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function calculate($balance)
	{
		$amount = 0.00;
		$taxamt = 0.00;
		$discamt = 0.00;
		$total = 0.00;
		$items = 0;

		foreach ($this->lineItems as $invoiceLine) {
			$lineItemAmount = ($invoiceLine->quantity * $invoiceLine->unitPrice);
			$amount = $amount + $lineItemAmount;
			$items = $items + $invoiceLine->quantity;
			$linetaxamt = ($lineItemAmount * ($invoiceLine->tax/100));
			$taxamt = $taxamt + $linetaxamt;
			$discamt = $discamt + (($lineItemAmount + $linetaxamt) * ($invoiceLine->discount/100));
		}
		//$taxamt = $amount * $tax/100;
		$total = $amount + $taxamt - $discamt;

		$this->amount = new Money(floatval($amount), Currency::Get('KES'));
		$this->taxamt = new Money(floatval($taxamt), Currency::Get('KES'));
		$this->discamt = new Money(floatval($discamt), Currency::Get('KES'));
	 	$this->total = new Money(floatval($total), Currency::Get('KES'));
	 	if ($balance == 'full') {
	 		$this->balance = new Money(floatval($total), Currency::Get('KES'));
	 	}else{
	 		$this->balance = new Money(floatval($balance), Currency::Get('KES'));
	 	}
	 	
		$this->items = $items;
	}

	public function updateStatus($status)
	{
		//Invoice posted successfully
		$this->status = $status;

		try {
			$sql = 'UPDATE purchase_invoices SET status = '.$this->status.' WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			
		}
	}

	public function credit($amount)
	{
		//Invoice posted successfully
		if ($this->balance->amount == floatval($amount)) {
			$this->status = 1;
		}else{
			$this->status = 2;
		}
		
		$newbal = $this->balance->amount - floatval($amount);
		$this->balance = new Money(floatval($newbal), Currency::Get('KES'));

		try {
			$sql = 'UPDATE purchase_invoices SET status = '.$this->status.', balance = '.$newbal.' WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			
		}
	}

	public function discard()
	{
		try {
			$sql = 'DELETE FROM purchase_invoices WHERE id = '.$this->id;			
			DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			
		}
	}

	public static function CreateInvoice($supplier, $pid, $orders, $invno, $descr, $date)
	{
		//Called and stored in a session object
		try {
			$datetime = new DateTime();
			$sql = 'INSERT INTO purchase_invoices (party_id, project_id, orders, invno, description, date, stamp, status) VALUES ("'.$supplier->id.'", '.intval($pid).', "'.$orders.'", "'.$invno.'", "'.$descr.'", "'.$date.'", '.$datetime->format('YmdHis').', 0)';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql = 'SELECT * FROM purchase_invoices WHERE stamp = '.$datetime->format('YmdHis');
			$res =  DatabaseHandler::GetRow($sql);

			return new PurchaseInvoice($res['id'], $res['project_id'], $res['orders'], $res['invno'], $res['description'], $res['date'], $res['status'], $supplier);

		} catch (Exception $e) {
			
		}
	}

	public static function GetInvoice($id)
	{
		try {
			$sql = 'SELECT * FROM purchase_invoices WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);
			if (!empty($res['date'])) {
				$party = Supplier::GetSupplier($res['party_id']);
				$invoice = new PurchaseInvoice($res['id'], $res['project_id'], $res['orders'], $res['invno'], $res['description'], $res['date'], $res['status'], $party);
				$invoice->initialize(floatval($res['balance']));
				/*if (!empty($res['project_id'])) {
					$invoice->initProject($res['project_id']);
				}*/
				return $invoice;
			}else{
				return null;
			}
			
		} catch (Exception $e) {
			return null;
		}		
	}

	public static function GetProjectPurchases($pid, $supplier)
	{
		try {
			$sql = 'SELECT * FROM purchase_invoices WHERE project_id = '.$pid;
			$res =  DatabaseHandler::GetAll($sql);
			$invoices = [];
			foreach ($res as $item) {
				if (!empty($item['date'])) {
					$invoice = new PurchaseInvoice($item['id'], $item['project_id'], $item['orders'], $item['invno'], $item['description'], $item['date'], $item['status'], $supplier);
					$invoice->initialize(floatval($item['balance']));
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

	public static function GetSupplierInvoices($supplierid)
	{
		try {
			$party = Supplier::GetSupplier($supplierid);
			$sql = 'SELECT * FROM purchase_invoices WHERE party_id = '.$supplierid.' AND status = 1';
			$res =  DatabaseHandler::GetAll($sql);
			$invoices = [];
			foreach ($res as $item) {
				if (!empty($item['date'])) {
					$invoice = new PurchaseInvoice($item['id'], $item['project_id'], $item['orders'], $item['invno'], $item['description'], $item['date'], $item['status'], $party);
					$invoice->initialize(floatval($item['balance']));
					$invoices[] = $invoice;
				}else{
					
				}
			}
			
			return $invoices;
			
		} catch (Exception $e) {
			return null;
		}		
	}

	public static function GetUnclearedInvoices($supplierid)
	{
		try {
			$party = Supplier::GetSupplier($supplierid);
			$sql = 'SELECT * FROM purchase_invoices WHERE party_id = '.$supplierid.' AND balance > 0 AND status != 0';
			$res =  DatabaseHandler::GetAll($sql);
			$invoices = [];
			foreach ($res as $item) {
				if (!empty($item['date'])) {
					$invoice = new PurchaseInvoice($item['id'], $item['project_id'], $item['orders'], $item['invno'], $item['description'], $item['date'], $item['status'], $party);
					$invoice->initialize(floatval($item['balance']));
					$invoices[] = $invoice;
				}else{
					
				}
			}

			
			$invbal = 0.00;
			foreach ($invoices as $grn) {
				$invbal += $grn->balance->amount;
			}

			$difference = $invbal - $party->balance->amount;
			if ($difference > 0) {
				$paidamt = 0.00;
				foreach ($invoices as $grn) {
					if(($difference - $paidamt) >= $grn->balance->amount){
						$paidamt = $paidamt + $grn->balance->amount;
						$grn->credit(floatval($grn->balance->amount));
						$credgrn .= $grn->id.',';
					}elseif (($difference - $paidamt) < $grn->balance->amount && ($difference - $paidamt) > 0.00) {
						$grn->credit(floatval(($difference - $paidamt)));
						$credgrn .= $grn->id.',';
						$paidamt = $paidamt + ($difference - $paidamt);
					}elseif ($difference == $paidamt) {
						break;
					}
				}

				$sql = 'SELECT * FROM purchase_invoices WHERE party_id = '.$supplierid.' AND balance > 0 AND status != 0';
				$res =  DatabaseHandler::GetAll($sql);
				$invoices = [];
				foreach ($res as $item) {
					if (!empty($item['date'])) {
						$invoice = new PurchaseInvoice($item['id'], $item['project_id'], $item['orders'], $item['invno'], $item['description'], $item['date'], $item['status'], $party);
						$invoice->initialize(floatval($item['balance']));
						$invoices[] = $invoice;
					}else{
						
					}
				}
			}
			
			return $invoices;
			
		} catch (Exception $e) {
			return null;
		}		
	}

	public static function GetGeneralInvoices($supplierid)
	{
		try {
			$party = Supplier::GetSupplier($supplierid);
			$sql = 'SELECT * FROM purchase_invoices WHERE party_id = '.$supplierid.' AND status = 1 AND isnull(project_id)';
			$res =  DatabaseHandler::GetAll($sql);
			$invoice = [];
			foreach ($res as $item) {
				if (!empty($item['date'])) {
					$invoice = new PurchaseInvoice($item['id'], $item['project_id'], $item['orders'], $item['invno'], $item['description'], $item['date'], $item['status'], $party);
					$invoice->initialize(floatval($item['balance']));
					$invoice[] = $invoice;
				}else{
					
				}
			}
			
			return $invoice;
			
		} catch (Exception $e) {
			return null;
		}		
	}

	public static function GetAllInvoices($dates, $all)
	{
		if ($all == 'true'){
			$sql = 'SELECT * FROM purchase_invoices';
		}else{
			$split = explode(' - ', $dates);
		    $d1 = explode('/', $split[0]);
		    $d2 = explode('/', $split[1]);
		    $lower = $d1[2].$d1[1].$d1[0].'000000' + 0;
		    $upper = $d2[2].$d2[1].$d2[0].'999999' + 0;
		    $sql = 'SELECT * FROM purchase_invoices WHERE stamp BETWEEN '.$lower.' AND '.$upper.'';
		}

		try {
			$res =  DatabaseHandler::GetAll($sql);

			return $res;
			
		} catch (Exception $e) {
			Logger::Log('Purchase Invoice', 'Exception', $e->getMessage());
			return null;
		}
		
	}

	public static function Delete($id)
	{
		try {
			$sql = 'DELETE FROM purchase_invoices WHERE id = '.$id;			
			DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			
		}
	}
}

class PurchaseVoucher
{
	public $id;
	public $type;
	public $transactionId;
	public $party;
	public $supplier;
	public $date;
	public $invno;
	public $orders = [];
	public $advices = [];
	public $description;
	public $tax;
	public $discount;
	public $amt;
	public $amount;
	public $total;
	public $balance;
	public $status;
	public $extras;
	public $user;

	function __construct($id, $supplierid, $date, $invno, $description, $amount, $tax, $discount, $total, $balance, $status, $orders)
	{
		$this->id = $id;		
		$this->supplier = Supplier::GetSupplier($supplierid);
		$this->party = $this->supplier;
		$this->date = $date;
		$this->invno = $invno;
		$this->tax = new Money(floatval($tax), Currency::Get('KES'));
		$this->discount = floatval($discount);
		$this->total = new Money(floatval($total), Currency::Get('KES'));
		$this->balance = new Money(floatval($balance), Currency::Get('KES'));
		$this->amt = new Money(floatval($amount), Currency::Get('KES'));
		$this->amount = floatval($total);
		$this->status = $status;
		$this->scope = $description;		

		if ($orders != "" || $orders != null) {
			$orders = explode(",", $orders);
			foreach ($orders as $oid) {
				$this->orders[] = PurchaseOrder::GetOrder($oid);
			}
		}

		$this->advices[] = PurchaseInvoice::GetInvoice($this->id);

		$this->description = '';
		foreach ($this->advices as $invoice) {
			foreach ($invoice->lineItems as $item) {
				$this->description .= $item->quantity.' x '.$item->itemName.', ';
			}
		}

		$this->description .= ' Invoice no: '.$invno;	

		$extras = new stdClass();
   		$extras->amount = $this->amt->amount;
   		$extras->tax = $this->tax->amount;
   		$extras->discount = $this->discount;
   		$extras->total = $this->total->amount;
   		$extras->balance = $this->balance->amount;
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

			$sql = 'SELECT * FROM general_ledger_entries WHERE transaction_id = '.intval($this->transactionId).' AND account_no = '.intval($supplierid);
			$res2 =  DatabaseHandler::GetRow($sql);
			$this->party->balance = new Money(floatval($res2['balance']), Currency::Get('KES'));
		} catch (Exception $e) {
			Logger::Log(get_class($this), 'Exception', $e->getMessage());
		}
	}

	private static function initialize($args){
		$invoice =  new PurchaseVoucher($args['id'], $args['party_id'], $args['date'], $args['invno'], $args['description'], $args['amount'], $args['tax'], $args['discount'], $args['total'], $args['balance'], $args['status'], $args['orders']);
		return $invoice;
	}

	public static function GetInvoice($id)
	{
		try {
			$sql2 = 'SELECT * FROM purchase_invoices WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql2);
			return self::initialize($res);
		} catch (Exception $e) {
			Logger::Log('PurchaseVoucher', 'Exception', $e->getMessage());
		}
		
	}

	public static function GetVoucher($txid)
	{
		try {
			$sql = 'SELECT * FROM vouchers WHERE transaction_id = '.$txid;
			$res =  DatabaseHandler::GetRow($sql);
			//echo json_encode($res);
			$sql2 = 'SELECT * FROM purchase_invoices WHERE id = '.intval($res['voucher_id']);
			$res2 =  DatabaseHandler::GetRow($sql2);
			//echo json_encode($res);
			if ($res2) {
				return self::initialize($res2);
			}else{
				Logger::Log('PurchaseVoucher', 'Exception', 'Missing invoice voucher for transaction id:'.$txid);
				return false;
			}
			
		} catch (Exception $e) {
			Logger::Log('PurchaseVoucher', 'Exception', $e->getMessage());
		}
		
	}
}

class PaymentVoucher
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

	function __construct($id, $supplierid, $date, $amount, $descr, $status)
	{
		$this->id = $id;		
		$this->party = Supplier::GetSupplier($supplierid);
		$this->date = $date;
		$this->amount = floatval($amount);
		$this->description = $descr;
		$this->status = $status;
		try {
			$sql = 'SELECT * FROM vouchers WHERE voucher_id = '.$id.' AND tx_type LIKE "%Payment%"';
			$res =  DatabaseHandler::GetRow($sql);
			$this->transactionId = $res['transaction_id'];
			$this->user = $res['cashier'];
			if (is_null($this->user)) {
				$this->user = SessionManager::GetUsername();
			}
			$this->type = $res['tx_type'];

			$sql = 'SELECT * FROM general_ledger_entries WHERE transaction_id = '.intval($this->transactionId).' AND account_no = '.intval($supplierid);
			$res2 =  DatabaseHandler::GetRow($sql);
			$this->party->balance = new Money(floatval($res2['balance']), Currency::Get('KES'));
		} catch (Exception $e) {
			Logger::Log(get_class($this), 'Exception', $e->getMessage());
		}		
	}

	private static function initialize($args){
		$payment =  new PaymentVoucher($args['id'], $args['party_id'], $args['datetime'], $args['amount'], $args['description'], $args['status']);
		return $payment;
	}

	public static function GetPayment($id)
	{
		try {
			$sql = 'SELECT * FROM payments WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);
			return self::initialize($res);
		} catch (Exception $e) {
			Logger::Log('PaymentVoucher', 'Exception', $e->getMessage());
		}		
	}

	public static function GetVoucher($txid)
	{
		try {
			$sql = 'SELECT voucher_id FROM vouchers WHERE transaction_id = '.$txid;
			$res =  DatabaseHandler::GetOne($sql);
			$res2;
			if (!empty($res)) {
				$sql2 = 'SELECT * FROM payments WHERE id = '.$res;
				$res2 =  DatabaseHandler::GetRow($sql2);
			}
			
			if ($res2) {
				return self::initialize($res2);
			}else{
				Logger::Log('PaymentVoucher', 'Missing', 'Missing payment voucher for transaction id:'.$txid);
				return false;
			}
		} catch (Exception $e) {
			Logger::Log('PaymentVoucher', 'Exception', $e->getMessage());
		}			
	}
}

class PurchaseOrderVoucher
{
	public $id;
	public $type;
	public $transactionId;
	public $party;
	public $date;
	public $lineItems = [];
	public $description;
	public $amount;
	public $total;
	public $user;

	function __construct($orderId, $date, $supplierid, $total, $user)
	{
		$this->id = $orderId;
		$this->transactionId = $orderId;
		$this->date = $date;
		$this->type = 'Purchase Order';
		//$this->description = 'Order for items';
		$this->party = Supplier::GetSupplier($supplierid);
		$this->amount = floatval($total);
		$this->total = floatval($total);
		if (is_null($user)) {
			$this->user = SessionManager::GetUsername();
		}else{
			$this->user = $user;
		}
		
		$this->lineItems = PurchaseOrderLine::GetOrderItems($orderId);
		$this->description = '';
		foreach ($this->lineItems as $item) {
			$this->description .= $item->quantity.' x '.$item->itemName.', ';
		}
	}	

	public static function initialize($args){
		$quote =  new PurchaseOrderVoucher($args['id'], $args['date'], $args['supplier_id'], $args['total'], $args['user']);
		return $quote;
	}

	public static function GetOrder($id)
	{
		$sql2 = 'SELECT * FROM purchase_orders WHERE id = '.$id;
		$res =  DatabaseHandler::GetRow($sql2);
		return self::initialize($res);
	}
}

class SupplierInvoice extends TransactionType
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

class PurchasesPayment extends TransactionType
{

	function __construct($ledgerId, $supplierId)
	{
		parent::__construct("Purchases Payment");
		
		$this->drAccounts[] = Account::GetAccountByNo($supplierId, 'suppliers', 'Creditors');
		$this->drRatios[] = 1;
		$this->crAccounts[] = Account::GetLedger($ledgerId);
		$this->crRatios[] = 1;

		//this info should originate from the database, insert ignore protocol included
		//$this->code = self::GetCode('PayPal');
		//parent::__construct();
	}
}

class PurchaseTX extends FinancialTransaction
{
	public $invoice;

	function __construct($invoice, $invoiceType)
	{
		$this->invoice = $invoice;
		$txtype = new SupplierInvoice($invoice->supplierId, $invoiceType);
		parent::__construct($invoice->total, $invoice->description, $txtype);
	}

	public function post()
	{
		if ($this->prepare()) {
			$voucher = TransactionProcessor::ProcessPurchaseTX($this);
			if ($voucher) {

				if ($this->invoice->orderIds != null && $this->invoice->orderIds != '') {
					$this->invoice->loadOrders();
					foreach ($this->invoice->orders as $order) {
						$order->setpurchased();
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
   				$extras->discount = $this->invoice->discamt->amount;
   				$extras->total = $this->invoice->total->amount;
   				$extras->balance = $this->invoice->balance->amount;

				//$voucher->setExtras($extras);
				return $voucher;
			}else{
				return false;
			}
		}
	}

	private function prepare()
	{	
		if ($this->transactionType->name == 'General Purchase Invoice' || $this->transactionType->name == 'Purchase Order Invoice') {
			$this->transactionType->drAccounts = [];
			$this->transactionType->drRatios = [];

			foreach ($this->invoice->lineItems as $invoiceLine) {
				$amount = ($invoiceLine->quantity * $invoiceLine->unitPrice);
				$taxamt = ($amount * ($invoiceLine->tax/100));
				$discamt = (($amount + $taxamt) * ($invoiceLine->discount/100));

				$lineTotal = $amount + $taxamt - $discamt;

				$this->transactionType->drAccounts[] = Account::GetLedger($invoiceLine->ledgerId);
				$this->transactionType->drRatios[] = floatval($lineTotal/$this->amount->amount);
				Logger::Log(get_class($this), 'Test', 'Credit: '.$this->amount->amount.', Ratio:'.floatval($lineTotal/$this->amount->amount));
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

	public static function RaiseOrderPurchase($supplierid, $invno, $date, $items)
	{
		$ords;
		foreach ($items as $item) {
		    $ords[$item['order']] = 1;
		}

		$orders = [];
		foreach ($ords as $key=>$oid) {
		    $orders[] = $key;
		    $order = PurchaseOrder::GetOrder($key);
			$order->setPurchased();		
		}

		$porders = implode(",", $orders);
		$descr = "Ordered Purchases. Order No(s): ".$porders;
		$supplier = Supplier::GetSupplier($supplierid);
		$pid = 0;	

		$invoice = PurchaseInvoice::CreateInvoice($supplier, $pid, $porders, $invno, $descr, $date);

		foreach ($items as $item) {
		    $invoice->addToInvoice(PurchaseInvoiceLine::Create($invoice->id, $item['item'], $item['qty'], $item['price'], $item['tax'], $item['disc'], $item['ledger']));
		}

		if ($invoice->generate()) {
			return new PurchaseTX($invoice, 'Purchase Order Invoice');
		}else{
			Logger::Log('PurchaseTX', 'Failed', 'Ordered purchase invoice transaction with id:'.$invoice->id.' and tx id:'.$this->transactionId.' could not be completed');
			return false;
		}		
	}

	public static function RaiseGeneralPurchase($supplierid, $invno, $date, $items)
	{
		$supplier = Supplier::GetSupplier($supplierid);
		
		/*if ($scope == "G") {
			$descr = "General Purchase";
			$pid = 0;
		}else{
			$prj = Project::GetProject(intval($scope));
			$descr = $prj->name.' Project';
			$pid = intval($scope);
		}*/
		$pid = 0;
		$orders = null;
		$descr = "General Purchases";
		$invoice = PurchaseInvoice::CreateInvoice($supplier, $pid, $orders, $invno, $descr, $date);

		foreach ($items as $item) {
		    $invoice->addToInvoice(PurchaseInvoiceLine::Create($invoice->id, $item['item'], $item['qty'], $item['price'], $item['tax'], $item['disc'], $item['ledger']));
		}

		if ($invoice->generate()) {
			return new PurchaseTX($invoice, 'General Purchase Invoice');
		}else{
			Logger::Log('PurchaseTX', 'Failed', 'General purchase invoice transaction with id:'.$invoice->id.' and tx id:'.$this->transactionId.' could not be completed');
			return false;
		}		
	}

	public static function TransferArrears($supplierid, $amount)
	{
		$supplier = Supplier::GetSupplier($supplierid);
		$descr = "Purchases Arrears B/F";
		$pid = 0;
		$discount = 0;
		$orders = null;
		$datetime = new DateTime();
		$ledger = Account::GetAccount('Purchases', 'ledgers');

		$invoice = PurchaseInvoice::CreateInvoice($supplier, $pid, $orders, 0, $descr, $datetime->format('d/m/Y'));

		$invoice->addToInvoice(PurchaseInvoiceLine::Create($invoice->id, 'Balances brought forward', 1, $amount->amount, 0, 0, $ledger->ledgerId));

		if ($invoice->generate()) {
			return new PurchaseTX($invoice, 'Purchases Arrears B/F Invoice');
		}else{
			Logger::Log('PurchaseTx', 'Failed', 'Purchases arrears B/F invoice transaction with id:'.$invoice->id.' and tx id:'.$this->transactionId.' could not be completed');
			return false;
		}		
	}
}

class GRNPaymentTX extends FinancialTransaction
{
	public $id;
	public $supplierId;
	public $grns;
	public $voucherNo;
	public $ledgerId;
	public $status;

	function __construct($id, $supplierId, $grns, $amount, $ledgerId, $mode, $voucherNo, $descr, $status, $payments)
	{
		$this->id = $id;
		$this->supplierId = $supplierId;
		$this->grns = $grns;		
		$this->ledgerId = $ledgerId;
		$this->mode = $mode;
		$this->voucherNo = $voucherNo;
		$this->payments = $payments;
		$this->status = $status;
		$txtype = new PurchasesPayment($ledgerId, $supplierId);
		parent::__construct(new Money(floatval($amount), Currency::Get('KES')), $descr, $txtype);
		$this->update();
	}

	public function update()
	{
		try {
	        $sql = 'UPDATE payments SET cashier = "'.SessionManager::GetUsername().'", datetime = "'.$this->date.'", stamp = '.$this->stamp.' WHERE id = '.$this->id;
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
				foreach ($this->payments as $key => $value) {
					$grn = PurchaseInvoice::GetInvoice($key);
					$grn->credit(floatval($value));
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

			$sql = 'UPDATE payments SET status = '.$this->status.' WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);	 		

		} catch (Exception $e) {
			return false;
		}
	}

	private static function initialize($args, $payments){
		$payment =  new PaymentTX($args['id'], $args['party_id'], $args['grns'], $args['amount'], $args['ledger_id'], $args['mode'], $args['voucher_no'], $args['description'], $args['status'], $payments);
		return $payment;
	}

	public static function MakePayment($supplierid, $amount, $ledgerId, $mode, $voucher, $payments, $descr)
	{
		try {
			$supplier = Supplier::GetSupplier($supplierid);

			/*$descr = "Items purchased. Reference GRN(s) - ";
			$grns = "";
			foreach ($payments as $key => $payment) {
				$descr .= "no: ".$key." amount: ".floatval($payment)."; ";
				$grns .= $key.",";
			}*/
			$descr = $supplier->name.'_'.$descr.'_'.$voucher;

			$sql = 'INSERT INTO payments (party_id, grns, amount, ledger_id, mode, voucher_no, description, status) VALUES 
			('.$supplierid.', "'.$grns.'", '.$amount.', '.$ledgerId.', "'.$mode.'", "'.$voucher.'", "'.$descr.'", 0)';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql2 = 'SELECT * FROM payments WHERE party_id = '.$supplierid.' ORDER BY id DESC LIMIT 0,1';
			$res =  DatabaseHandler::GetRow($sql2);

			return self::initialize($res, $payments);
		} catch (Exception $e) {
			
		}
	}
}

class PaymentTX extends FinancialTransaction//without grn
{
	public $id;
	public $supplierId;
	public $grns;
	public $voucherNo;
	public $ledgerId;
	public $status;

	function __construct($id, $supplierId, $grns, $amount, $ledgerId, $mode, $voucherNo, $descr, $status)
	{
		$this->id = $id;
		$this->supplierId = $supplierId;
		$this->grns = $grns;		
		$this->ledgerId = $ledgerId;
		$this->mode = $mode;
		$this->voucherNo = $voucherNo;
		//$this->payments = $payments;
		$this->status = $status;
		$txtype = new PurchasesPayment($ledgerId, $supplierId);
		parent::__construct(new Money(floatval($amount), Currency::Get('KES')), $descr, $txtype);
		$this->update();
	}

	public function update()
	{
		try {
	        $sql = 'UPDATE payments SET cashier = "'.SessionManager::GetUsername().'", datetime = "'.$this->date.'", stamp = '.$this->stamp.' WHERE id = '.$this->id;
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
				$supplier = Supplier::GetSupplier($this->supplierId);
				$grns = PurchaseInvoice::GetUnclearedInvoices($this->supplierId);

				if ($supplier->balance->amount > 0) {
					$amount = $this->amount->amount;
				} else {
					//if supplier has a prepayment (-ve ba)
					$amount = $this->amount->amount - $supplier->balance->amount;
				}
				
				$paidamt = floatval(0.00);

				$credgrn = '';

				foreach ($grns as $grn) {
					if(($amount - $paidamt) >= $grn->balance->amount){
						$paidamt = $paidamt + $grn->balance->amount;
						$grn->credit(floatval($grn->balance->amount));
						$credgrn .= $grn->id.',';
					}elseif (($amount - $paidamt) < $grn->balance->amount && ($amount - $paidamt) > 0.00) {
						$grn->credit(floatval(($amount - $paidamt)));
						$credgrn .= $grn->id.',';
						$paidamt = $paidamt + ($amount - $paidamt);
					}elseif ($amount == $paidamt) {
						break;
					}
				}

				$this->status = 1;
				$this->updateParticulars($credgrn);
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

	private function updateParticulars($grns)
	{
		try {
			$sql = 'UPDATE payments SET status = '.$this->status.', grns = "'.$grns.'" WHERE id = '.$this->id;
	 		DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			return false;
		}
	}

	private static function initialize($args){
		$payment =  new PaymentTX($args['id'], $args['party_id'], $args['grns'], $args['amount'], $args['ledger_id'], $args['mode'], $args['voucher_no'], $args['description'], $args['status']);
		return $payment;
	}

	public static function MakePayment($party, $scope, $supplierid, $amount, $ledgerId, $mode, $voucher, $descr)
	{
		try {
			$supplier = Supplier::GetSupplier($supplierid);

			$grns = "";
			/*foreach ($payments as $key => $payment) {
				$grns .= $key.",";
			}*/
			$descr .= ' ('.$voucher.')';

			$sql = 'INSERT INTO payments (party_id, grns, amount, ledger_id, mode, voucher_no, description, status) VALUES 
			('.$supplierid.', "'.$grns.'", '.$amount.', '.$ledgerId.', "'.$mode.'", "'.$voucher.'", "'.$descr.'", 0)';
	 		DatabaseHandler::Execute($sql);
	 		
	 		$sql2 = 'SELECT * FROM payments WHERE party_id = '.$supplierid.' ORDER BY id DESC LIMIT 0,1';
			$res =  DatabaseHandler::GetRow($sql2);

			$acc = Account::GetAccountByNo($supplierid, 'suppliers', 'Creditors');

			$expv = ExpenseVoucher::CreateSupplierProjectExpense($party, $scope, $amount, $acc->ledgerId, $voucher, $descr);

			if ($expv) {
				$tx = self::initialize($res); 
				$tx->expVoucher = $expv;
				return $tx;
			}else{
				return false;
			}

			return self::initialize($res);

		} catch (Exception $e) {
			
		}
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
		$txtype = new SupplierInvoice($supplierId, 'Purchases Balance B/F invoice');
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