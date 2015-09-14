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

/*class LiabilityType extends ResourceType
{
 	public $name;
 	
 	function __construct($name)
 	{
 		parent::__construct($name);
 		$this->name = $name;
 	}
}


class EquityType extends ResourceType
{
 	public $name;
 	
 	function __construct($name)
 	{
 		parent::__construct($name);
 		$this->name = $name;
 	}
}*/

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

/*class Weight extends Unit
{	
	function __construct()
	{
		return Unit::Create('Weight', 'Kilogram', 'Kg');
	}
}

class Capacity extends Unit
{	
	function __construct()
	{
		return Unit::Create('Capacity', 'Litres', 'L');
	}
}

class Volume extends Unit
{	
	function __construct()
	{
		return Unit::Create('Space', 'Cubic Meter', 'cu. m');
	}
}*/

class Quantity
{
	public $amount;
	public $unit;

	function __construct($amount, Unit $unit)
	{
		$this->amount = intval($amount);
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
	//public $postingRule;// - associated proposed action [source = destination inc. fees]
	//public $drAccount;
	//public $crAccount;
	function __construct()
	{
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

class Transaction extends Action
{
	public $transactionId;
	public $transactionType;// defines: Protocol/PaymentMethod/Transaction Type/Posting Rules	$this->transactionType->protocol
	public $wasPosted;
	public $description;
	public $date;
	public $stamp;
	public $entries = [];

	function __construct(TransactionType $ttype, $description)
	{
		//parent::__construct();
		$datetime = new DateTime();
		$this->date = $datetime->format('Y/m/d H:ia');
		$this->stamp = $datetime->format('YmdHis');
		$this->transactionType = $ttype;
		//Assert that cr and dr resource types are the same i.e both are KES Currency - dealt with in transaction type creation	
		//$this->resourceType = $rtype;
		$this->description = $description;
	}

	public function add(AccountEntry $entry)
	{

	}

	public function commit()
	{
		if ($this->transactionType->drAccount) {
			# code...
		}
		//verify account and entry is of the same resource type;
		//enter the 
	}

	public function reverse()
	{

	}
}

class TransactionProcessor// extends SystemAgent with TP role
{
	
	function __construct()
	{

	}
}

class Artifact extends FourthDimension
{

}


class AccountType extends Artifact
{// say - account type == ledger name [stock, sales, shares, accounts payable]
	public $accountTypeCode;//ledgerId
	public $accountTypeName;//ledgerName
	public $unit;//currency

	function __construct($ledgerId, $ledgerName, Unit $unit)
	{
		$this->accountTypeCode = $ledgerId;
		$this->accountTypeName = $ledgerName;
		$this->unit = $unit;//currency - KES
		//add to accounts/journals ledger
		//retrieve generated account number - using what key?
	}

	public static function GetAccountType($code)
	{
	    //update with accountNumber as key;
	}

	public static function CreateAccountType($name, $unit)
	{
	    


	    return new AccountType($name, $unit);
	}
}


//check out: Financial accounts by M. Fowler
class Account extends Artifact
{
	public $accountNo;
	public $accountName;
	public $accountType;//provides ledger info and currency
	public $entries = array();
	public $balance;//Quantity
	//public $actualBalance;//Quantity
	//public $availableBalance;//Quantity
	

	function __construct()//$name, AccountType $type
	{
		$this->accountName = $name;
		$this->accountType = $type;
		//add to accounts/journals ledger
		//retrieve generated account number - using what key?
	}

	public static function LoadLedger($name = null, $classification = null)
    {
        /*/override this function with Ledgers::Create/Get
        $sql = 'SELECT * FROM accounting_ledgers WHERE name = "'.self::ledgerName.'"';
        $ledger_account = DatabaseHandler::GetRow($sql);

        if (!isset($ledger_account)) {
            $sql = 'INSERT INTO accounting_ledgers (name, classification, balance) VALUES ("'.self::ledgerName.'", "Asset", 0)';
            DatabaseHandler::Execute($sql);
            $sql1 = 'SELECT * FROM accounting_ledgers WHERE name = "'.self::ledgerName.'"';
            $ledger_account = DatabaseHandler::GetRow($sql1);            
        }

        self::ledgerName = $ledger_account['name'];
        self::ledgerId = $ledger_account['id'];
        self::ledger_bal = $ledger_account['balance'];
        self::ledger = strtolower($ledger_account['name']).'_accounts';
        self::history = strtolower($ledger_account['name']).'_accounts_history';
        self::ledger_loaded = true;*/
        
    }

	public function initializeBalance(Quantity $balance)
	{
	    $this->balance = $balance->amount;
	    //update with accountNumber as key;
	}

	private function updateBalance()
	{
	    //update with accountNumber as key;
	}

	public function credit(ResourceEntry $resourceEntry)//credit
	{
		$this->balance = intval($this->balance) + intval($resourceEntry->resource->amount);
        $this->updateBalance();
	}

	public function debit(ResourceEntry $resourceEntry)//debit
	{
		$this->balance = intval($this->balance) - intval(($resourceEntry->resource)->amount);
        $this->updateBalance();
	}

	public function getStatement($limit = 5)
	{
	    $this->balance = $balance->amount;
	    //update with accountNumber as key;
	}
}

//Inventory - consumables, stock, customer
class HoldingAccount extends Account
{

	function __construct()
	{
	}

}

//Fixed/Temporal Assets - furniture, machines, buildings, employees.
class AssetAccount extends Account
{
	function __construct($name, AssetType $type, Unit $unit)
	{
		$actype = new AccountType($type, $unit);
		parent::__construct($name, $actype);
	}

}

class EntryType
{
	public $entryId;
	public $type;//credit or debit
	Public $effect;//additive or deductive + or -
	public $transaction_id;

	function __construct($type)
	{
		$this->type = $type;
	}

	public function type()
	{
		return $this->type;
	}

	public function effect()
	{
		return $this->effect;
	}
}

class FinancialEntryType extends EntryType
{
	function __construct($type)
	{
		parent::__construct($type);
	}

}

class ConsumableEntryType extends EntryType
{
	function __construct($type)
	{
		parent::__construct($type);
	}

}

class TemporalEntryType extends EntryType
{
	function __construct($type)
	{
		parent::__construct($type);
	}

}


class Credit extends FinancialEntryType
{
	function __construct()
	{
		parent::__construct('credit');
	}

}

class Debit extends FinancialEntryType
{
	function __construct()
	{
		parent::__construct('debit');
	}

}

class StockIncrease extends ConsumableEntryType
{
	function __construct()
	{
		parent::__construct('stock increase');
	}

}

class StockDecrease extends ConsumableEntryType
{
	function __construct()
	{
		parent::__construct('stock decrease');
	}

}

class LogSession extends TemporalEntryType
{
	function __construct()
	{
		parent::__construct('session period log');
	}

}

class AccountEntry extends Artifact
{
	public $transaction;
	public $account;
	public $whenBooked;//datetime - timestamp
	public $whenCharged;//datetime
	public $amount;
	public $effect;//credit - cr, debit - dr


	function __construct($transaction, $account, $amount, $whenBooked, $effect)
	{
		//parent::__construct();
		$this->transaction = $transaction;
		$this->account = $account;
		$this->whenBooked = $whenBooked;
		$this->amount = $amount;
		$this->effect = $effect;
		//$this->whenCharged = $whenCharged;
		//create database entry
	}

	public function post()
	{
		$this->account->addEntry($this);
	}

	/*public static function CreateEntry($txId, Account $account, $resource_id, $whenBooked, $txType, $amount, $description)
	{
		$datetime = new DateTime();
		$timestamp = $datetime->format('YmdHis');//('Y-m-d H:i:s');
		$whenCharged = $datetime->format('Y-m-d H:i:s');
		$account::ledger;//accounts_table
		$account::history;//account_history

		$sql = 'INSERT INTO "'.$account::history.'" (type_id, type_name, name, reference, description, retail_price, wholesale_price, tax_code, img_url, manufacturer) 
        VALUES ('.$typedata['id'].', "'.$typedata['type'].'", "'.$name.'", "'.$ref.'", "'.$desc.'", "'.$rprice.'", "'.$wprice.'", "'.$tax.'", "'.$img.'", "'.$manufacturer.'")';
        DatabaseHandler::Execute($sql);

		//updateAccount - A/c Number

	}

	public function book()
	{
		$timestamp = new DateTime();
		$this->whenBooked = $timestamp->format('YmdHis');//('Y-m-d H:i:s');
		//updateAccount - A/c Number

	}

	public function charge()
	{
		$timestamp = new DateTime();
		if (empty($this->whenBooked)) {
			$this->whenBooked = $timestamp->format('YmdHis');
		}
		
		$this->whenCharged = $timestamp->format('YmdHis');//('Y-m-d H:i:s');
	}

	public function bookAndCharge()
	{
		$timestamp = new DateTime();
		$this->whenBooked = $timestamp->format('YmdHis');
		$this->whenCharged = $timestamp->format('YmdHis');//('Y-m-d H:i:s');
		//update database
	}*/
}

//A resource entry qualifies as a resource allocation
class ResourceAllocation extends AccountEntry
{
 
  	function __construct($eventId, Account $account, Resource $item, Quantity $quantity)
  	{
     	parent::__construct($eventId, $account, $item);
  	}
}

class GeneralResourceAllocation extends ResourceAllocation
{ 
  	public function __construct($eventId, Account $account, Resource $item, Quantity $quantity)
  	{
     	parent::__construct($eventId, $account, $item);
  	}
}

class SpecificResourceAllocation extends ResourceAllocation
{
  	public $identifiers;//unique identifier e.g serial numbers, names of persons or batch numbers

  	public function __construct($eventId, Account $account, Resource $item, Quantity $quantity)
  	{
     	parent::__construct($eventId, $account, $item);
  	}
}

class ConsumableResourceAllocation extends SpecificResourceAllocation
{
  	public function __construct($eventId, Account $account, ConsumableType $type, Quantity $quantity)
  	{
     	parent::__construct($eventId, $account, $item);
  	}
}

abstract class TimeRecord
{
	
}

class TimePoint extends TimeRecord
{
	public $timestamp;
	
	function __construct($timestamp = datetime)
	{
		$this->timestamp = $timestamp;
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


class TemporalResourceAllocation extends SpecificResourceAllocation
{
  	public $timePeriod;//Time record - specific

  	public function __construct($eventId, $account, AssetType $type, Quantity $quantity)
  	{
     	parent::__construct($eventId, $account, $item);
  	}

  	public function bookResource(TimePoint $startTime, TimePoint $endTime)
	{
		$this->$timePeriod = new TimePeriod($startTime, $endTime);
	}

	public function durationOfUse()
	{
		return new Quantity(($this->timePeriod->endTime() - $this->timePeriod->startTime()), Unit::getUnitByName('second'));
	}
}

?>