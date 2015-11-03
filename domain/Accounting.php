 <?php
require_once 'FourthDimension.php';
require_once 'Protocol.php';
require_once 'Event.php';

class ResourceType extends FourthDimension
{
 	public $typeId; //db autoincrement
 	public $type;
  	public $unit;
 	
 	function __construct($typeId, $typeName, Unit $unit)
 	{
 		$this->typeId = $typeId;
 		$this->type = $typeName;
 		$this->unit = $unit;
 		
 	}

 	public static function FetchType($typeName, $unit)
 	{
 		$sql1 = 'SELECT * FROM resource_types WHERE type = "'.$typeName.'" AND unit_id = '.intval($unit->unitId);
 		$res = DatabaseHandler::GetRow($sql1);

 		if (empty($res)) {
 			$sql = 'INSERT INTO resource_types (type, unit_id) VALUES ("'.$typeName.'", '.intval($unit->unitId).')';
 			DatabaseHandler::Execute($sql);
 			$res = DatabaseHandler::GetRow($sql1);
 		}
 		
 		return $res;
 	}

 	public static function GetTypeData($typeid)
 	{
 		$sql = 'SELECT * FROM resource_types WHERE id = '.$typeid;
 		$res = DatabaseHandler::GetRow($sql); 		
 		return $res;
 	}
}

class ConsumableType extends ResourceType
{
 	function __construct($typeId, $typeName, Unit $unit)
 	{
 		parent::__construct($typeId, $typeName, $unit);
 	}
}

class AssetType extends ResourceType
{
 	function __construct($typeId, $typeName, Unit $unit)
 	{
 		parent::__construct($typeId, $typeName, $unit);
 	}
}

class ConvertionRatio
{
	public $name;
	public $number;
	public $fromUnit;
	public $toUnit;
	public static $cache;

	function __construct(Unit $fromUnit, Unit $toUnit, $ratio)
	{
		$this->name = $name;
		$this->number = $ratio;
		$this->fromUnit = $fromUnit;
		$this->toUnit = $toUnit;
	}

	public function create()
	{

	}

	public function update()
	{

	}

	public function delete()
	{

	}

	public static function clearRegistry() {

	}

	public static function getConversionRatio($name)
	{

	}

	public static function initialize($args)
	{

	}
}

class Unit
{
	public $unitId;
	public $phenomena;
	public $symbol;
	public $name;
	public $isSIUnit = false;//boolean
	//public ratio_to_SIunit

	//e.g new Unit('Time', 'Seconds', 's'), new Unit('Time', 'Minutes', 'm') ... etc.
	//e.g new Unit('Enumerable', 'Integer', ''), new Unit('Currency', 'Money', 'KES') ... etc.
	//e.g new Unit('Labour', 'Service', 'Satisfaction'), new Unit('Currency', 'Money', 'KES') ... etc.
	public function __construct($id, $phenomena, $name, $symbol = null)
	{
		$this->unitId = $id;
		$this->phenomena = $phenomena;
		$this->name = $name;
		$this->symbol = $symbol;
	}

	public function makeSIUnit()
	{
		$this->isSIUnit = true;
	}

	public function save()
	{
		
	}

	public function convertTo($name)
	{
		$phenomena = self::getUnitByName($name);
		if ($this->phenomena != $phenomena->phenomena) {
			return false; //Error: Cannot convert between different phenomena
		} else {
			# code...
		}
		
	}

	public static function GetUnitById($id)
	{
		$sql = 'SELECT * FROM units WHERE id = '.$id;
 		$res = DatabaseHandler::GetRow($sql);
		return new Unit($res['id'], $res['phenomena'], $res['name'], $res['symbol']);
	}

	public static function getUnitBySIUnit()
	{

	}
}

class WorkService extends Unit
{
	
	function __construct($id, $phenomena, $name, $symbol)
	{
		parent::__construct($id, $phenomena, $name, $symbol);
		//SLP - Service Level Performance
	}

	public static function getCurrencyBySymbol($symbol)
	{

	}

	public static function Get($symbol)
	{
		$phenomena = 'Labour';
		$name = 'Service';
		$sql2 = 'SELECT * FROM units WHERE name = "'.$name.'" AND phenomena = "'.$phenomena.'" AND symbol = "'.$symbol.'"';
 		$res = DatabaseHandler::GetRow($sql2);

 		if (empty($res)) {
 			$sql = 'INSERT IGNORE INTO units (name, phenomena, symbol) VALUES ("'.$name.'", "'.$phenomena.'", "'.$symbol.'")';
 			DatabaseHandler::Execute($sql);
 			$res = DatabaseHandler::GetRow($sql2);
 		}
 		
		return new WorkService($res['id'], $res['phenomena'], $res['name'], $res['symbol']);
	}
}

class Currency extends Unit
{
	
	function __construct($id, $phenomena, $name, $symbol)
	{
		parent::__construct($id, $phenomena, $name, $symbol);
		//return self::Create('Money', $name, $symbol);
	}

	public static function getCurrencyBySymbol($symbol)
	{

	}

	public static function Get($symbol)
	{
		$phenomena = 'Legal Tender';
		$name = 'Currency';
		$sql2 = 'SELECT * FROM units WHERE name = "'.$name.'" AND phenomena = "'.$phenomena.'" AND symbol = "'.$symbol.'"';
 		$res = DatabaseHandler::GetRow($sql2);

 		if (empty($res)) {
 			$sql = 'INSERT INTO units (name, phenomena, symbol) VALUES ("'.$name.'", "'.$phenomena.'", "'.$symbol.'")';
 			DatabaseHandler::Execute($sql);
 			$res = DatabaseHandler::GetRow($sql2);
 		}
 		
		return new Currency($res['id'], $res['phenomena'], $res['name'], $res['symbol']);
	}
}

class Enumerable extends Unit
{
	function __construct($id, $phenomena, $name, $symbol = null)
	{
		//parent::Create($phenomena, $name, $symbol);
		parent::__construct($id, $phenomena, $name, $symbol);
		//Good or service/virtual product
	}

	public static function Create($name)
	{
		$phenomena = 'Number';

		$sql2 = 'SELECT * FROM units WHERE name = "'.$name.'" AND phenomena = "'.$phenomena.'"';
 		$res = DatabaseHandler::GetRow($sql2);

 		if (empty($res)) {
 			$sql = 'INSERT INTO units (name, phenomena, symbol) VALUES ("'.$name.'", "'.$phenomena.'", "'.$symbol.'")';
 			DatabaseHandler::Execute($sql);
 			$res = DatabaseHandler::GetRow($sql2);
 		}
 		
		return new Enumerable($res['id'], $res['phenomena'], $res['name'], $res['symbol']);
	}
}

class Quantity
{
	public $amount;
	public $unit;

	function __construct($amount, Unit $unit)
	{
		$this->amount = floatval($amount);
		$this->unit = $unit;
	}

	public function amount()
	{
		return $this->amount;
	}

	public function unit()
	{
		return $this->unit;
	}
}

class Money extends Quantity
{
	
	function __construct($amount, Currency $currency)
	{
		parent::__construct($amount, $currency);
	}
}

class Goods extends Quantity
{
	
	function __construct($amount, Enumerable $Unit)
	{
		parent::__construct($amount, $unit);
	}
}

//$timeQty = new Quantity($timeInSeconds, Unit.getUnitByName('seconds'));
//$timeQty2 = new Quantity($timeInMinutes, Unit.getUnitByName('seconds'));
 
class Resource extends FourthDimension
{
  	public $type;
  	public $quantity;
 
  	public function __construct(ResourceType $type, Quantity $quantity)
  	{
     	//array_push($this->type, $type);
     	$this->type = $type;
     	$this->quantity = $quantity;
  	}	

  	public function quantity()
  	{
      	return $this->quantity;
  	}

  	public function amount()
  	{
      	return $this->amount;
  	}

  	function __set($propName, $propValue)
  	{
  		$this->$propName = $propValue;
  	}

  	public function __destruct()
  	{
     	//echo 'The class "', __CLASS__, '" was destroyed.<br />';
  	} 
}

class TransactionType extends Protocol
{
	public $code;
	public $name;
	public $drAccounts = [];
	public $crAccounts = [];
	public $drRatios = [];
	public $crRatios = [];	

	function __construct($name)
	{
		$this->name = $name;
		parent::__construct();
	}

	public static function create($paymentMethod)
	{

	}

	public function verifyPreconditions()
	{
		
	}

	public function makePayment()
	{
		
	}

	public static function GetCode($name)
	{
		
	}
}
//Subclass into single transaction and batch transaction
class Transaction extends Action
{
	//A transaction involves two or more actions/events
	public $transactionId;
	public $transactionType;// defines: Protocol/PaymentMethod/Transaction Type/Posting Rules	$this->transactionType->protocol
	public $posted;
	public $description;
	public $date;
	public $stamp;
	public $entries = [];
	public $amount;

	function __construct(Money $amount, $description)
	{
		//parent::__construct();
		$datetime = new DateTime();
		$this->date = $datetime->format('d/m/Y H:i a');
		$this->stamp = $datetime->format('YmdHis');
		$this->amount = $amount;
		$this->description = $description;
		$this->posted = false;

		try {
			//Check for existing tx - refactor to micro/nano second timestamp [not applicable for batch transactions]
			$sqlx = 'SELECT * FROM transactions WHERE stamp > '.($this->stamp - 2).' AND description = "'.$description.'"  ORDER BY stamp DESC LIMIT 0,1';
			$resx = DatabaseHandler::GetRow($sqlx);

			if ($resx['stamp']) {
				Logger::Log(get_class($this), 'Missing', 'Blocked double entry. Similar transaction id:'.$resx['id'].' with timestamp '.$resx['stamp'].' exists');
	 			//throw new Exception('Blocked double entry. Similar transaction id:'.$resx['id'].' with timestamp '.$resx['stamp'].' exists '.json_encode($this));
	 		}else{
	 			//Initialize transaction
				$sql = 'INSERT INTO transactions (type, amount, datetime, stamp, status, description) VALUES ("'.$this->transactionType->name.'", '.floatval($amount->amount).', "'.$this->date.'", '.$this->stamp.', 0, "'.$this->description.'")';
		 		DatabaseHandler::Execute($sql);	 		

		      	$sql2 = 'SELECT * FROM transactions WHERE stamp = '.$this->stamp.' AND description = "'.$description.'" ORDER BY stamp DESC LIMIT 0,1';
				// Execute the query and return the results
				$res =  DatabaseHandler::GetRow($sql2);
				$this->transactionId = $res['id'];
	 		}
	 		
		} catch (Exception $e) {
			Logger::Log(get_class($this), 'Exception', $e->getMessage());
		}
	}

	public function add(AccountEntry $entry)
	{
		if (!$this->posted) {
			$this->entries[] = $entry;
		}
		
	}

	public function commit()
	{
		if (!$this->posted) {
			$cr = 0.00; 
			$dr = 0.00;
			foreach ($this->entries as $entry) {
				if ($entry->effect == 'cr') {
					$cr = $cr + $entry->amount->amount;

				}else if ($entry->effect == 'dr') {
					$dr = $dr + $entry->amount->amount;
					Logger::Log(get_class($this), 'Test', 'Debit '.$entry->account->accountName.': '.$entry->amount->amount);
				}
			}

			if (($cr - $dr) == 0.00) {
				foreach ($this->entries as $entry) {
					$entry->post();
				}
				
				try {
					$sql = 'UPDATE transactions SET status = 1, entries = '.count($this->entries).', user = "'.SessionManager::GetUsername().'" WHERE id = '.$this->transactionId;
				 	DatabaseHandler::Execute($sql);
				    $this->posted = true;
				    Logger::Log(get_class($this), 'Ok', 'Transaction id:'.$this->transactionId.' posted by '.SessionManager::GetUsername());
					return true;
				} catch (Exception $e) {
						
				}
			}else{
				//throw new Exception("The entries are not conservative. Probable system leak!");
				Logger::Log(get_class($this), 'Exception', 'The entries are not conservative. Probable system leak! CR: '.$cr.', DR: '.$dr);
				return false;
			}

		}else{
			Logger::Log(get_class($this), 'Exception', 'Trying to commit an already posted transaction');
			return false;
		}

		
		//verify account and entry is of the same resource type;
		//enter the 
	}

	public function rollback()
	{

	}
}

class TransactionProcessor// extends SystemAgent with TP role - Actor/Agent of the system - Reference Object
{
	public static $currentTransaction;
	public static $status;//0 - busy, 1 - ready
	public static $transactionQueue = [];//
	public static $processors = [];//multi-threading/multi-pricessing foundation -- straight forward in node.js

	function __construct()
	{
		self::$status = 1;
	}


	public static function ProcessInvoice($invoice)
	{
		//if busy - add to queue, if ready - add to currentTransaction then prepare and commit
		if ($invoice->commit()) {
			return Voucher::CreateInvoiceVoucher($invoice);
		}else{
			Logger::Log('TransactionProcessor', 'Failed', 'Invoice transaction with id:'.$invoice->id.' and tx id:'.$invoice->transactionId.' could not be commited');
			return false;
		}
	}

	public static function ProcessSalesTX($invoicetx)
	{
		//if busy - add to queue, if ready - add to currentTransaction then prepare and commit
		if ($invoicetx->commit()) {
			return Voucher::CreateSalesTxVoucher($invoicetx);
		}else{
			Logger::Log('TransactionProcessor', 'Failed', $invoicetx->txtype->name.' transaction with id:'.$invoicetx->invoice->id.' and tx id:'.$invoicetx->transactionId.' could not be commited');
			return false;
		}
	}

	public static function ProcessPurchaseTX($invoicetx)
	{
		//if busy - add to queue, if ready - add to currentTransaction then prepare and commit
		if ($invoicetx->commit()) {
			return Voucher::CreatePurchaseTxVoucher($invoicetx);
		}else{
			Logger::Log('TransactionProcessor', 'Failed', $invoicetx->transactionType->name.' transaction with id:'.$invoicetx->invoice->id.' and tx id:'.$invoicetx->transactionId.' could not be commited');
			return false;
		}
	}

	public static function ProcessTransfer($transfer)
	{
		//if busy - add to queue, if ready - add to currentTransaction then prepare and commit
		if ($transfer->commit()) {
			return true;
		}else{
			Logger::Log('TransactionProcessor', 'Failed', 'Transfer transaction with tx id:'.$transfer->transactionId.' could not be commited');
			return false;
		}
	}


	
	public static function ProcessReceipt($receipt)
	{
		if ($receipt->commit()) {
			return Voucher::CreateReceiptVoucher($receipt);
		}else{
			Logger::Log('TransactionProcessor', 'Failed', 'Receipt transaction with id:'.$receipt->id.' and tx id:'.$receipt->transactionId.' could not be commited');
			return false;
		}	
	}

	public static function ProcessPayment($payment)
	{
		if ($payment->commit()) {
			return Voucher::CreatePaymentVoucher($payment);
		}else{
			Logger::Log('TransactionProcessor', 'Failed', 'Payment transaction with id:'.$payment->id.' and tx id:'.$payment->transactionId.' could not be commited');
			return false;
		}	
	}

	public static function ProcessTransaction($transaction)
	{
		if ($transaction->commit()) {
			//return Voucher::CreateTransactionVoucher($transaction);
			return true;
		}else{
			Logger::Log('TransactionProcessor', 'Failed', 'General transaction with tx id:'.$transaction->transactionId.' could not be commited');
			return false;
		}	
	}

	public static function ProcessClaim($claim)
	{
		if ($claim->commit()) {
			//return Voucher::CreateTransactionVoucher($claim);
			return Voucher::CreateClaimVoucher($claim);
		}else{
			Logger::Log('TransactionProcessor', 'Failed', 'Claim transaction with tx id:'.$claim->transactionId.' could not be commited');
			return false;
		}	
	}
}

class FinancialTransaction extends Transaction
{
	function __construct(Money $amount, $description, TransactionType $txtype)
	{		
		//$ttype === posting protocol/rule
		$this->transactionType = $txtype;
		parent::__construct($amount, $description);
	}
}

class Artifact extends FourthDimension
{

}

class AccountType extends Artifact
{// say - account type == ledger name [stock, sales, shares, accounts payable]
	public $typeCode;//ledgerId
	public $typeName;//ledgerName
	public $class;//asset, liability, equity
	public $category;//current asset, current liability, longterm liability, fixed asset, drawings, suspense
	public $memo;//bank, collections, 
	public $unit;//currency
	public $level;//hierarchy position
	public $parent;//hierachy level parent

	function __construct($ledgerId, $ledgerName, $class, Unit $unit)
	{
		$this->typeCode = $ledgerId;
		$this->typeName = $ledgerName;
		$this->class = $class;
		$this->unit = $unit;//currency - KES
		//add to accounts/journals ledger
		//retrieve generated account number - using what key?
	}

	public static function GetAccountType($id)
	{
	    //update with accountNumber as key;
	}

	public static function CreateAccountType($name, $unit)
	{
		return new AccountType($name, $unit);
	}
}

class AccountEntry extends Artifact
{
	public $transaction;
	public $account;
	public $whenBooked;
	public $whenCharged;
	public $amount;
	public $effect;
	public $transactionRatio;


	function __construct($transaction, $account, $amount, $whenBooked, $effect)
	{
		$this->transaction = $transaction;
		$this->account = $account;
		$this->whenBooked = $whenBooked;
		$this->amount = $amount;
		$this->effect = $effect;
		$this->transactionRatio = floatval(floatval($amount->amount)/floatval($transaction->amount->amount));
	}

	public function post()
	{
		$this->whenCharged = $this->transaction->date;
		$this->account->addEntry($this);
	}

	public static function FindEntries($param, $value)
	{
		$query = '';

	    switch (intval($param)) {
	    	case 1:
	    		//Transaction number
	    		$query = ' WHERE transaction_id = '.intval($value);
	    		break;

	    	case 2:
	    		//Description
	    		$query = ' WHERE description LIKE "%'.$value.'%"';
	    		break;

	    	case 3:
	    		//Date
	    		$split = explode('/', $value);
	    		$lower = $split[2].''.$split[0].''.$split[1].'000000' + 0;
	    		$upper = $split[2].$split[0].$split[1].'999999' + 0;
	    		$query = ' WHERE stamp BETWEEN '.$lower.' AND '.$upper;
	    		break;

	    	case 4:
	    		//Date Range
	    		$split = explode(' - ', $value);
	    		$d1 = explode('/', $split[0]);
	    		$d2 = explode('/', $split[1]);
	    		$lower = $d1[2].$d1[0].$d1[1].'000000' + 0;
	    		$upper = $d2[2].$d2[0].$d2[1].'999999' + 0;
	    		$query = ' WHERE stamp BETWEEN '.$lower.' AND '.$upper;
	    		break;

	    	case 5:
	    		//Ledger
	    		$query = ' WHERE ledger_id = '.intval($value);
	    		break;

	    	case 6:
	    		//Transaction number
	    		$query = ' WHERE amount = '.floatval($value);
	    		break;
	    	
	    	default:
	    		
	    		break;
	    }
	    $query .= ' ORDER BY id DESC';
	    try {
			$sql = 'SELECT * FROM general_ledger_entries'.$query;
			$res =  DatabaseHandler::GetAll($sql);
			$entries = [];
			foreach ($res as $item) {
				$entry = new stdClass();
   				$entry->txid = $item['transaction_id'];
   				$entry->date = $item['when_charged'];
   				$entry->ledger = $item['ledger_name'];
   				$entry->effect = $item['effect'];
   				$entry->amount = $item['amount'];
   				$entry->description = $item['description'];
				$entries[] = $entry;
			}
			return $entries;
		} catch (Exception $e) {
			return false;
		}
	}
}
//check out: Financial accounts by M. Fowler
class Account extends Artifact
{
	public $accountNo;
	public $ledgerId;
	public $ledgerName;
	public $accountName;
	public $accountType;//provides ledger info and currency
	public $entries = array();
	public $balance;//Quantity
	public $ledgerBal;
	public $updateTable;
	//public $actualBalance;//Quantity
	//public $availableBalance;//Quantity
	

	function __construct($id, $ledgerId, $ledgerName, $acname, $table, $balance = null, $ledgerBal, $type = null)//
	{
		$this->accountNo = $id;
		$this->ledgerId = $ledgerId;
		$this->ledgerName = $ledgerName;
		$this->ledgerBal = new Money(floatval($ledgerBal), Currency::Get('KES'));
		$this->accountName = $acname;
		$this->accountType = $type;
		if (empty($balance)) {
			$this->balance = new Money('0.00', $type->unit);
		}else{
			$this->balance = new Money(floatval($balance), Currency::Get('KES'));
		}

		$this->updateTable = $table;
		//add to accounts/journals ledger
		//retrieve generated account number - using what key?
	}

	public function initializeBalance(Quantity $balance)
	{
	    $this->balance->amount = $balance->amount;
	    $this->persistBalance();
	}

	public function addEntry($entry)
	{
	    if ($entry->amount->unit == Currency::Get('KES')) {
	    	$this->entries[] = $entry;//add entries
	    	if ($entry->effect == 'cr') {
	    		$this->credit($entry);
	    	}else if ($entry->effect == 'dr') {
	    		$this->debit($entry);
	    	}

	    	try {
				$status = 1;
				$sql = 'INSERT INTO general_ledger_entries (transaction_id, account_no, account_name, ledger_id, ledger_name, effect, amount, trans_ratio, description, when_booked, when_charged, balance, ledger_bal, stamp, status) 
				VALUES ('.$entry->transaction->transactionId.', '.$this->accountNo.', "'.$this->accountName.'", '.$this->ledgerId.', "'.$this->ledgerName.'", "'.$entry->effect.'", '.floatval($entry->amount->amount).', '.floatval($entry->transactionRatio).', "'.$entry->transaction->description.'", "'.$entry->whenBooked.'", "'.$entry->whenCharged.'", '.$this->balance->amount.', '.$this->ledgerBal->amount.', '.$entry->transaction->stamp.', '.$status.')';
			 	DatabaseHandler::Execute($sql);

			 	$sql2 = 'UPDATE ledgers SET balance = '.$this->ledgerBal->amount.' WHERE id = '.$this->ledgerId;
			 	DatabaseHandler::Execute($sql2);

			 	if ($this->ledgerId != $this->accountNo) {
			 		$this->persistBalance();
			 	}
			} catch (Exception $e) {
					
			}

			
	    }
	}

	private function credit(AccountEntry $entry)
	{
		switch ($this->accountType) {
			case 'Asset':
				$this->balance->amount = floatval($this->balance->amount) - floatval($entry->amount->amount);
				$this->ledgerBal->amount = floatval($this->ledgerBal->amount) - floatval($entry->amount->amount);
				break;

			case 'Liability':
				$this->balance->amount = floatval($this->balance->amount) + floatval($entry->amount->amount);
				$this->ledgerBal->amount = floatval($this->ledgerBal->amount) + floatval($entry->amount->amount);
				break;

			case 'Equity':
				$this->balance->amount = floatval($this->balance->amount) + floatval($entry->amount->amount);
				$this->ledgerBal->amount = floatval($this->ledgerBal->amount) + floatval($entry->amount->amount);
				break;

			case 'Expense':
				$this->balance->amount = floatval($this->balance->amount) - floatval($entry->amount->amount);
				$this->ledgerBal->amount = floatval($this->ledgerBal->amount) - floatval($entry->amount->amount);
				break;

			case 'Revenue':
				$this->balance->amount = floatval($this->balance->amount) + floatval($entry->amount->amount);
				$this->ledgerBal->amount = floatval($this->ledgerBal->amount) + floatval($entry->amount->amount);
				break;
			
			default:
				break;
		}        
	}

	private function debit(AccountEntry $entry)
	{
		switch ($this->accountType) {
			case 'Asset':
				$this->balance->amount = floatval($this->balance->amount) + floatval($entry->amount->amount);
				$this->ledgerBal->amount = floatval($this->ledgerBal->amount) + floatval($entry->amount->amount);
				break;

			case 'Liability':
				$this->balance->amount = floatval($this->balance->amount) - floatval($entry->amount->amount);
				$this->ledgerBal->amount = floatval($this->ledgerBal->amount) - floatval($entry->amount->amount);
				break;

			case 'Equity':
				$this->balance->amount = floatval($this->balance->amount) - floatval($entry->amount->amount);
				$this->ledgerBal->amount = floatval($this->ledgerBal->amount) - floatval($entry->amount->amount);
				break;
			
			case 'Expense':
				$this->balance->amount = floatval($this->balance->amount) + floatval($entry->amount->amount);
				$this->ledgerBal->amount = floatval($this->ledgerBal->amount) + floatval($entry->amount->amount);
				break;

			case 'Revenue':
				$this->balance->amount = floatval($this->balance->amount) - floatval($entry->amount->amount);
				$this->ledgerBal->amount = floatval($this->ledgerBal->amount) - floatval($entry->amount->amount);
				break;
			default:
				break;
		}
	}

	public function getStatement($limit = 5)
	{
	    
	}

	private function persistBalance()
	{
	    try {
			$sql = 'UPDATE '.$this->updateTable.' SET balance = '.$this->balance->amount.' WHERE id = '.$this->accountNo;
			DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			
		}
	}

	public static function CreateLedger($name, $type, $group, $category, $subacc = null)
	{
	    try {
	    	$datetime = new DateTime();
			$sql = 'INSERT INTO ledgers (name, type, class, category, parent, status, date) VALUES ("'.$name.'", "'.$type.'", "'.$group.'", "'.$category.', '.$subacc.', 1, '.$datetime->format('Y/m/d').')';
			DatabaseHandler::Execute($sql);

			$sql = 'SELECT * FROM ledgers WHERE name = "'.$name.'"';
			$res =  DatabaseHandler::GetRow($sql);
			if (empty($res)) {
				return false;
			}
			return new Account($res['id'], $res['id'], $res['name'], $res['name'], $table, $res['balance'], $res['balance'], $res['type']);
		} catch (Exception $e) {
			return false;
		}
	}

	public static function GetLedgers()
	{
	    
	    try {
			$sql = 'SELECT * FROM ledgers';
			$res =  DatabaseHandler::GetAll($sql);
			$ledgers = [];
			foreach ($res as $ledger) {
				$ledgers[] = new Account($res['id'], $res['id'], $res['name'], $res['name'], $table, $res['balance'], $res['balance'], $res['type']);
				//$ledgers[] = new Ledger($res['id'], $res['name'], $res['type'], $res['group'], $res['category'], $res['parent'], $res['balance']);
			}
			return $ledgers;
		} catch (Exception $e) {
			return false;
		}
	}


	public static function GetLedger($id)
	{
	    
	    try {
			$sql = 'SELECT * FROM ledgers WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);
			if (empty($res)) {
				return false;
			}
			return new Account($res['id'], $res['id'], $res['name'], $res['name'], 'ledgers', $res['balance'], $res['balance'], $res['type']);
		} catch (Exception $e) {
			return false;
		}
	}

	public static function GetAccount($name, $table = 'ledgers')
	{
	    
	    try {
			//$sql = 'INSERT INTO transactions (amount, datetime, stamp, status) VALUES ('.floatval($amount->amount).', "'.$this->date.'", '.$this->stamp.', 0)';
			//DatabaseHandler::Execute($sql);

			$sql = 'SELECT * FROM '.$table.' WHERE name = "'.$name.'"';
			$res =  DatabaseHandler::GetRow($sql);
			if (empty($res)) {
				return false;
			}
			return new Account($res['id'], $res['id'], $res['name'], $res['name'], $table, $res['balance'], $res['balance'], $res['type']);
		} catch (Exception $e) {
			return false;
		}
	}

	public static function GetAccountByNo($number, $table, $ledgerName)
	{
	    try {
			$sql = 'SELECT * FROM '.$table.' WHERE id = "'.$number.'"';
			$res =  DatabaseHandler::GetRow($sql);
			$sql2 = 'SELECT * FROM ledgers WHERE name = "'.$ledgerName.'"';
			$res2 =  DatabaseHandler::GetRow($sql2);
			//If ledger is child account, get all parent ledgers so that their balances may be updated
			return new Account($res['id'], $res2['id'], $res2['name'], $res['name'], $table, $res['balance'], $res2['balance'], $res2['type']);
		} catch (Exception $e) {
			
		}
	}
}

//Inventory - consumables, stock, customer
class HoldingAccount extends Artifact
{

	function __construct()
	{
	}
}

abstract class TimeRecord
{
	
}

class TimePoint extends TimeRecord
{
	public $datetime;
	public $timestamp;
	
	function __construct($datetime = null)
	{
		$date = new DateTime();
		$this->datetime = $date;
		$this->timestamp = $date->format('YmdHis');
	}
}

class TimePeriod extends TimeRecord
{
	public $start;
	public $end;
	
	function __construct(TimePoint $startTime = NULL, TimePoint $endTime = NULL)
	{
		$this->start = $startTime;
		$this->end = $endTime;
	}

	public function start(TimePoint $startTime)
	{
		$this->start = $startTime;
	}

	public function end(TimePoint $endTime)
	{
		$this->end = $endTime;
	}

	public function startTime()
	{
		return $this->start;
	}

	public function endTime()
	{
		return $this->end;
	}
}

class Ledger extends Artifact
{
	public $id;
	public $name;
	public $type;
	public $group;
	public $category;
	public $parent;
	public $balance;

	function __construct($id, $name, $type, $group, $category, $parent = 0, $balance)
	{
		$this->id = $id;
		$this->name = $name;
		$this->type = $type;
		$this->group = $group;
		$this->category = $category;
		$this->parent = $parent;
		if ($balance == 0) {
			$this->balance = new Money('0.00', Currency::Get('KES'));
		}else{
			$this->balance = new Money(floatval($balance), Currency::Get('KES'));
		}
	}

	public static function CreateLedger($name, $type, $group, $category, $subacc = 0)
	{
	    try {
	    	$datetime = new DateTime();
	    	$date = $datetime->format('d/m/Y');

	    	if ($group == 'n') {
	    		$group = "";
	    	}

	    	$sql = 'SELECT * FROM ledgers WHERE name = "'.$name.'"';
			$res =  DatabaseHandler::GetRow($sql);
			if (empty($res)) {
				$sql = 'INSERT INTO ledgers (name, type, class, category, parent, status, date) VALUES ("'.$name.'", "'.$type.'", "'.$group.'", "'.$category.'", '.intval($subacc).', 1, "'.$date.'")';
				DatabaseHandler::Execute($sql);
			}else{
				return false;
			}			

			$sql = 'SELECT * FROM ledgers WHERE name = "'.$name.'"';
			$res =  DatabaseHandler::GetRow($sql);
			if (empty($res)) {
				return false;
			}
			return new Ledger($res['id'], $res['name'], $res['type'], $res['class'], $res['category'], $res['parent'], $res['balance']);
		} catch (Exception $e) {
			return false;
		}
	}

	public static function GetLedgers()
	{	    
	    try {
			$sql = 'SELECT * FROM ledgers';
			$res =  DatabaseHandler::GetAll($sql);
			$ledgers = [];
			foreach ($res as $ledger) {
				$ledgers[] = new Ledger($ledger['id'], $ledger['name'], $ledger['type'], $ledger['class'], $ledger['category'], $ledger['parent'], $ledger['balance']);
			}
			return $ledgers;
		} catch (Exception $e) {
			return false;
		}
	}

	public static function GetPurchaseLedgers()
	{	    
	    try {
			$sql = 'SELECT * FROM ledgers WHERE (type = "Asset" OR type = "Expense") AND category != "Bank"';
			$res =  DatabaseHandler::GetAll($sql);
			$ledgers = [];
			foreach ($res as $ledger) {
				$ledgers[] = new Ledger($ledger['id'], $ledger['name'], $ledger['type'], $ledger['class'], $ledger['category'], $ledger['parent'], $ledger['balance']);
			}
			return $ledgers;
		} catch (Exception $e) {
			return false;
		}
	}

	public static function GetLedgerType($type)
	{	    
	    try {
			$sql = 'SELECT * FROM ledgers WHERE type = '.$type;
			$res =  DatabaseHandler::GetAll($sql);
			$ledgers = [];
			foreach ($res as $ledger) {
				$ledgers[] = new Ledger($ledger['id'], $ledger['name'], $ledger['type'], $ledger['class'], $ledger['category'], $ledger['parent'], $ledger['balance']);
			}
			return $ledgers;
		} catch (Exception $e) {
			return false;
		}
	}

	public static function GetLedger($id)
	{	    
	    try {
			$sql = 'SELECT * FROM ledgers WHERE id = '.$id;
			$res =  DatabaseHandler::GetRow($sql);
			if (empty($res)) {
				return false;
			}
			return new Ledger($res['id'], $res['name'], $res['type'], $res['class'], $res['category'], $res['parent'], $res['balance']);
		} catch (Exception $e) {
			return false;
		}
	}

	public static function GetLedgerByName($name)
	{	    
	    try {
	    	$name = (string)$name;//strval($name)
			$sql = 'SELECT * FROM ledgers WHERE name = '.$name;
			$res =  DatabaseHandler::GetRow($sql);
			if (empty($res)) {
				return false;
			}
			return new Ledger($res['id'], $res['name'], $res['type'], $res['class'], $res['category'], $res['parent'], $res['balance']);
		} catch (Exception $e) {
			return false;
		}
	}

	public static function GetBanks()
	{	    
	    try {
			$sql = 'SELECT * FROM ledgers WHERE category = "Bank"';
			$res =  DatabaseHandler::GetAll($sql);
			$ledgers = [];
			foreach ($res as $ledger) {
				$ledgers[] = new Ledger($ledger['id'], $ledger['name'], $ledger['type'], $ledger['class'], $ledger['category'], $ledger['parent'], $ledger['balance']);
			}
			return $ledgers;
		} catch (Exception $e) {
			return false;
		}
	}

	public static function Delete($id)
    {
        try {
        	$sql = 'DELETE FROM ledgers WHERE id = '.intval($id);
        	$res =  DatabaseHandler::Execute($sql);
        	return true;
        } catch (Exception $e) {
        	return false;
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

	public function setSupplier($id){
		$this->party = Supplier::GetSupplier($id);
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

	public static function CreateSalesTxVoucher($tx){
		$inv = new Voucher($tx->invoice->id, $tx->transactionType->name, $tx->transactionId, $tx->amount->amount, $tx->description, $tx->date, $tx->stamp);
		$inv->persist();		
		return SalesVoucher::GetInvoice($tx->invoice->id);
	}

	public static function CreatePurchaseTxVoucher($tx){
		$inv = new Voucher($tx->invoice->id, $tx->transactionType->name, $tx->transactionId, $tx->amount->amount, $tx->description, $tx->date, $tx->stamp);
		$inv->persist();		
		return PurchaseVoucher::GetInvoice($tx->invoice->id);
	}

	public static function CreateReceiptVoucher($receipt){
		$rcpt = new Voucher($receipt->id, $receipt->transactionType->name, $receipt->transactionId, $receipt->amount->amount, $receipt->description, $receipt->date, $receipt->stamp);
		$rcpt->persist();
		$rcpt->setClient($receipt->clientId);
		return $rcpt;
	}

	public static function CreatePaymentVoucher($payment){
		$pmnt = new Voucher($payment->id, $payment->transactionType->name, $payment->transactionId, $payment->amount->amount, $payment->description, $payment->date, $payment->stamp);
		$pmnt->persist();
		$pmnt->setSupplier($payment->supplierId);
		return $pmnt;
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

class TransactionVouchers extends Artifact
{
	public static function GetClientTransactions($cid, $category, $dates, $all)
	{
		if ($category == 1) {//Statement
			if ($all == 'true'){
				$sql = 'SELECT * FROM general_ledger_entries WHERE account_no = '.intval($cid).' AND ledger_name = "Debtors" ORDER BY id DESC';
			}else if($dates != ''){
				$split = explode(' - ', $dates);
		    	$d1 = explode('/', $split[0]);
		    	$d2 = explode('/', $split[1]);
		    	$lower = $d1[2].$d1[0].$d1[1].'000000' + 0;
		    	$upper = $d2[2].$d2[0].$d2[1].'999999' + 0;
		    	$sql = 'SELECT * FROM general_ledger_entries WHERE account_no = '.intval($cid).' AND ledger_name = "Debtors" AND stamp BETWEEN '.$lower.' AND '.$upper.' ORDER BY id DESC';
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
						$voucher = SalesVoucher::GetVoucher(intval($tx['transaction_id']));
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

	public static function ClientStatement($cid, $dates, $all)
	{
		if ($all == 'true'){
			$sql = 'SELECT * FROM general_ledger_entries WHERE account_no = '.intval($cid).' AND ledger_name = "Debtors" ORDER BY id ASC';
		}else if($dates != ''){
			$split = explode(' - ', $dates);
		    $d1 = explode('/', $split[0]);
		    $d2 = explode('/', $split[1]);
		    $lower = $d1[2].$d1[0].$d1[1].'000000' + 0;
		    $upper = $d2[2].$d2[0].$d2[1].'999999' + 0;
		    $sql = 'SELECT * FROM general_ledger_entries WHERE account_no = '.intval($cid).' AND ledger_name = "Debtors" AND stamp BETWEEN '.$lower.' AND '.$upper.' ORDER BY id ASC';
		}

		try {
			$result = DatabaseHandler::GetAll($sql);
			foreach ($result as &$tx) {
				$sql2 = 'SELECT type FROM transactions WHERE id = '.intval($tx['transaction_id']);
				$res =  DatabaseHandler::GetOne($sql2);
				$tx['type'] = $res;
			}
			return $result;
		} catch (Exception $e) {
				
		}
	}

	public static function GetSupplierTransactions($sid, $category, $dates, $all)
	{
		if ($category == 1) {//Statement
			if ($all == 'true'){
				$sql = 'SELECT * FROM general_ledger_entries WHERE account_no = '.intval($sid).' AND ledger_name = "Creditors" ORDER BY id DESC';
			}else if($dates != ''){
				$split = explode(' - ', $dates);
		    	$d1 = explode('/', $split[0]);
		    	$d2 = explode('/', $split[1]);
		    	$lower = $d1[2].$d1[0].$d1[1].'000000' + 0;
		    	$upper = $d2[2].$d2[0].$d2[1].'999999' + 0;
		    	$sql = 'SELECT * FROM general_ledger_entries WHERE account_no = '.intval($sid).' AND ledger_name = "Creditors" AND stamp BETWEEN '.$lower.' AND '.$upper.' ORDER BY id DESC';
			}

			try {
				$res = DatabaseHandler::GetAll($sql);
				$vouchers = [];
				foreach ($res as $tx) {
					if ($tx['effect'] == 'dr') {
						$voucher = PaymentVoucher::GetVoucher(intval($tx['transaction_id']));
						if ($voucher) {
							$vouchers[] = $voucher;
						}	
					}else{
						$voucher = PurchaseVoucher::GetVoucher(intval($tx['transaction_id']));
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
				$sql = 'SELECT * FROM purchase_orders WHERE supplier_id = '.intval($sid).' ORDER BY id DESC';
			}else{
				$split = explode(' - ', $dates);
		    	$d1 = explode('/', $split[0]);
		    	$d2 = explode('/', $split[1]);
		    	$lower = $d1[2].$d1[0].$d1[1].'000000' + 0;
		    	$upper = $d2[2].$d2[0].$d2[1].'999999' + 0;
		    	$sql = 'SELECT * FROM purchase_orders WHERE supplier_id = '.intval($sid).' AND stamp BETWEEN '.$lower.' AND '.$upper.' ORDER BY id DESC';
			}

			try {
				$res = DatabaseHandler::GetAll($sql);
				$vouchers = [];
				foreach ($res as $order) {
					$vouchers[] = PurchaseOrderVoucher::initialize($order);
				}

				return $vouchers;
			} catch (Exception $e) {
				
			}
		}
	}

	public static function SupplierStatement($sid, $dates, $all)
	{
		if ($all == 'true'){
			$sql = 'SELECT * FROM general_ledger_entries WHERE account_no = '.intval($sid).' AND ledger_name = "Creditors" ORDER BY id ASC';
		}else if($dates != ''){
			$split = explode(' - ', $dates);
		    $d1 = explode('/', $split[0]);
		    $d2 = explode('/', $split[1]);
		    $lower = $d1[2].$d1[0].$d1[1].'000000' + 0;
		    $upper = $d2[2].$d2[0].$d2[1].'999999' + 0;
		    $sql = 'SELECT * FROM general_ledger_entries WHERE account_no = '.intval($sid).' AND ledger_name = "Creditors" AND stamp BETWEEN '.$lower.' AND '.$upper.' ORDER BY id ASC';
		}

		try {
			$result = DatabaseHandler::GetAll($sql);
			foreach ($result as &$tx) {
				$sql2 = 'SELECT type FROM transactions WHERE id = '.intval($tx['transaction_id']);
				$res =  DatabaseHandler::GetOne($sql2);
				$tx['type'] = $res;
			}
			return $result;
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

?>