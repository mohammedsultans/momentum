<?php

class ItemType //extends ConsumableType
{ 
    function __construct($id, $name, $description)
    {
        //parent::__construct($typeId, $typeName, $unit);
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    private static function initialize($args)
    {
        if (isset($args['name'])) {
            $object = new ItemType($args['id'], $args['name'], $args['description']);
            return $object;
        } else {
            return false;
        }
    }

    public static function Create($name)
    {
        //Called and stored in a session object
        try {
            //start here
            $sql = 'INSERT IGNORE INTO item_types (name) VALUES ("'.$name.'")';
            DatabaseHandler::Execute($sql);
            
            return self::FindByName($name);

        } catch (Exception $e) {
            
        }

    }

    public static function Update($id, $name)
    {
        try {
            //start here
            $sql = 'UPDATE item_types SET name = "'.$name.'" WHERE id = '.$id;
            DatabaseHandler::Execute($sql);
            
            return self::FindByName($name);

        } catch (Exception $e) {
            
        }
    }

    public static function FindObject($id)
    {
        //Called and stored in a session object
        try {
            $sql = 'SELECT * FROM item_types WHERE id = "'.$id.'"';
            $res =  DatabaseHandler::GetRow($sql);
            return self::initialize($res);
        } catch (Exception $e) {
            
        }

    }

    public static function FindByName($name)
    {
        //Called and stored in a session object
        try {
            $sql = 'SELECT * FROM item_types WHERE name = "'.$name.'"';
            $res =  DatabaseHandler::GetRow($sql);
            return self::initialize($res);
        } catch (Exception $e) {
            
        }

    }

    public static function FindAll()
    {
        //Called and stored in a session object
        try {
            $sql = 'SELECT * FROM item_types';
            $res =  DatabaseHandler::GetAll($sql);
            $item_types = array();
            foreach ($res as $item_type) {
                $item_types[] = self::initialize($item_type);
            }                
            return $item_types;
        } catch (Exception $e) {
            
        }

    }

    public static function Delete($id)
    {
      try {
        $sql = 'DELETE FROM item_types WHERE id = "'.$id.'"';          
        DatabaseHandler::Execute($sql);
        return true;
      } catch (Exception $e) {
        return false;
      }

    }
 
}

class ItemCategory //extends ConsumableType
{ 
    function __construct($id, $name, $description)
    {
        //parent::__construct($typeId, $typeName, $unit);
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    private static function initialize($args)
    {
        if (isset($args['name'])) {
            $object = new ItemCategory($args['id'], $args['name'], $args['description']);
            return $object;
        } else {
            return false;
        }
    }

    public static function Create($name)
    {
        //Called and stored in a session object
        try {
            //start here
            $sql = 'INSERT IGNORE INTO item_categories (name) VALUES ("'.$name.'")';
            DatabaseHandler::Execute($sql);
            
            return self::FindByName($name);

        } catch (Exception $e) {
            
        }

    }

    public static function Update($id, $name)
    {
        try {
            //start here
            $sql = 'UPDATE item_categories SET name = "'.$name.'" WHERE id = '.$id;
            DatabaseHandler::Execute($sql);
            
            return self::FindByName($name);

        } catch (Exception $e) {
            
        }
    }

    public static function FindObject($id)
    {
        //Called and stored in a session object
        try {
            $sql = 'SELECT * FROM item_categories WHERE id = "'.$id.'"';
            $res =  DatabaseHandler::GetRow($sql);
            return self::initialize($res);
        } catch (Exception $e) {
            
        }

    }

    public static function FindByName($name)
    {
        //Called and stored in a session object
        try {
            $sql = 'SELECT * FROM item_categories WHERE name = "'.$name.'"';
            $res =  DatabaseHandler::GetRow($sql);
            return self::initialize($res);
        } catch (Exception $e) {
            
        }

    }

    public static function FindAll()
    {
        //Called and stored in a session object
        try {
            $sql = 'SELECT * FROM item_categories';
            $res =  DatabaseHandler::GetAll($sql);
            $item_categories = array();
            foreach ($res as $item_type) {
                $item_categories[] = self::initialize($item_type);
            }                
            return $item_categories;
        } catch (Exception $e) {
            
        }

    }

    public static function Delete($id)
    {
      try {
        $sql = 'DELETE FROM item_categories WHERE id = "'.$id.'"';          
        DatabaseHandler::Execute($sql);
        return true;
      } catch (Exception $e) {
        return false;
      }

    }
 
}

class Item //extends ConsumableType
{ 
    function __construct($id, $type, $name, $scope, $margin, $vat, $category, $description, $ledger)
    {
        //parent::__construct($typeId, $typeName, $unit);
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        //Scope: 1 - Buyable, 2 - Sellable, 3 - Buyable & Sellable 
        $this->scope = $scope;
        $this->margin = $margin;
        $this->vat = $vat;
        $this->category = $category;
        $this->description = $description;
        $this->ledger = $ledger;
    }

    private static function initialize($args)
    {
        if (isset($args['name'])) {
            $object = new Item($args['id'], $args['type'], $args['name'], $args['scope'], $args['margin'], $args['vat'], $args['category_id'], $args['description'], $args['account_no']);
            return $object;
        } else {
            return false;
        }

    }

    public static function CreateStock($name, $margin, $scope, $vat, $category, $description)
    {
        try {

            if (!(self::FindByNameType($name, 'Stock') instanceof Item)) {
                 $sql = 'INSERT INTO items (type, name, margin, scope, vat, category_id, description) VALUES ("Stock", "'.$name.'", '.$margin.', '.$scope.', '.$vat.', '.$category.', "'.$description.'")';
                DatabaseHandler::Execute($sql);
                return self::FindByNameType($name, 'Stock');
            } else {
                return false;
            }

        } catch (Exception $e) {
            return false;
        }

    }

    public static function UpdateStock($id, $name, $margin, $scope, $vat, $category, $description)
    {
        try {
            //start here
            $sql = 'UPDATE items SET name = "'.$name.'", scope = '.$scope.', margin = '.$margin.', vat = '.$vat.', category_id = '.$category.', description = "'.$description.'" WHERE id = '.$id;
            DatabaseHandler::Execute($sql);
            
            return self::FindByNameType($name, 'Stock');

        } catch (Exception $e) {
            
        }
    }

    public static function CreateService($name, $scope, $vat, $category, $description)
    {
        try {

            if (!(self::FindByNameType($name, 'Service'))) {
                 $sql = 'INSERT INTO items (type, name, scope, vat, category_id, description) VALUES ("Service", "'.$name.'", '.$scope.', '.$vat.', '.$category.', "'.$description.'")';
                DatabaseHandler::Execute($sql);
                return self::FindByNameType($name, 'Service');
            } else {
                return false;
            }

        } catch (Exception $e) {
            return false;
        }

    }

    public static function UpdateService($id, $name, $scope, $vat, $category, $description)
    {
        try {
            //start here
            $sql = 'UPDATE items SET name = "'.$name.'", scope = '.$scope.', vat = '.$vat.', category_id = '.$category.', description = "'.$description.'" WHERE id = '.$id;
            DatabaseHandler::Execute($sql);
            
            return self::FindByNameType($name, 'Service');

        } catch (Exception $e) {
            
        }
    }

    public static function CreateAsset($name, $ledger, $category, $description)
    {
        try {

            if (!(self::FindByNameType($name, 'Asset'))) {
                 $sql = 'INSERT INTO items (type, name, scope, account_no, category_id, description) VALUES ("Asset", "'.$name.'", 3, '.$ledger.', '.$category.', "'.$description.'")';
                DatabaseHandler::Execute($sql);
                return self::FindByNameType($name, 'Asset');
            } else {
                return false;
            }

        } catch (Exception $e) {
            return false;
        }

    }

    public static function UpdateAsset($id, $name, $ledger, $category, $description)
    {
        try {
            //start here
            $sql = 'UPDATE items SET name = "'.$name.'", account_no = '.$ledger.', category_id = '.$category.', description = "'.$description.'" WHERE id = '.$id;
            DatabaseHandler::Execute($sql);
            
            return self::FindByNameType($name, 'Asset');

        } catch (Exception $e) {
            
        }
    }

    public static function FindObject($id)
    {
        //Called and stored in a session object
        try {
            $sql = 'SELECT * FROM items WHERE id = "'.$id.'"';
            $res =  DatabaseHandler::GetRow($sql);
            return self::initialize($res);
        } catch (Exception $e) {
            
        }

    }

    public static function FindByNameType($name, $type)
    {
        //Called and stored in a session object
        try {
            $sql = 'SELECT * FROM items WHERE name = "'.$name.'" AND type = "'.$type.'"';
            $res =  DatabaseHandler::GetRow($sql);
            return self::initialize($res);
        } catch (Exception $e) {
            
        }

    }

    public static function GetAll()
    {
        //Called and stored in a session object
        try {
            $sql = 'SELECT * FROM items';
            $res =  DatabaseHandler::GetAll($sql);
            $items = array();
            foreach ($res as $item_type) {
                $items[] = self::initialize($item_type);
            }                
            return $items;
        } catch (Exception $e) {
            
        }

    }

    public static function GetByType($type)
    {
        //Called and stored in a session object
        try {
            $sql = 'SELECT * FROM items WHERE type = "'.$type.'"';
            $res =  DatabaseHandler::GetAll($sql);
            $items = array();
            foreach ($res as $item_type) {
                $items[] = self::initialize($item_type);
            }                
            return $items;
        } catch (Exception $e) {
            
        }

    }

    public static function Delete($id)
    {
      try {
        $sql = 'DELETE FROM items WHERE id = "'.$id.'"';          
        DatabaseHandler::Execute($sql);
        return true;
      } catch (Exception $e) {
        return false;
      }

    }
 
}

class ProductEntry 
//extends ItemEntry
{
    
    public $itemId;
    public $batchNumber;
    public $refNumber;
    public $attributes = array();// feature => value
    public $identifier; //licence or serial or specific time allocation of person
    public $uniqueIdentifiers = array();
 
    public function __construct(Product $type, Quantity $quantity)
    {
        parent::__construct($type, $quantity);
    }

    public function save(){

    }
}

class Category
{
    
    public $categoryId; //serial number
    //public $batchNumber;
    public $name;
    public $itemIds = array();
 
    public function __construct(ItemType $type, Quantity $quantity)
    {
        parent::__construct($type, $quantity);
    }

    public static function getCategory($id)
    {
        

    }

    public static function TagItem($product, $categories)
    {
        
    }

    public function removeItem(Item $item)
    {
        $this->attributes[$name] = $value;

    }

    public function getProducts($searchKey = null)
    {
        $this->features[$key] = $value;
    }
}

class ProductTransactionType extends TransactionType
{
    public $txCode;
    public $postingRule;// - associated proposed action [source = destination inc. fees]
    public $sourceAccountTypes;
    public $destinationAccountTypes;

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
}

class ProductTransfer extends Transaction
{//5th dimension composite of account entries
    public function __construct(StockAccount $sourceAcc, StockAccount $destinationAcc, TransactionType $ttype, ResourceType $rtype, Quantity $quantity, $description)
    {
      parent::__construct($sourceAcc, $destinationAcc, $ttype, $rtype, $quantity, $description);
      //e.g supplier account, warehouse account, 'Goods Received Inwards', Samsung Fridge BF-X450, 14 items, 'delivery from supplier xxxx'
    }

    public function createEntry($account)
    {
        $item = new ProductItem($this->resourceType, $this->amount);
        return new InventoryEntry($this->transactionId, $account, $item);
    }
}

class InventoryEntry extends AccountEntry
{
    public $resourceId;
    public $stock_bal;
    public $catalog_bal;
    public $amount;//quantity
    public $description;
    public $txType;//1= deposit, 2 = withdrawal
    public $timestamp;


    function __construct($txId, $accountNo, $resource_id, $whenBooked, $whenCharged, $txType, $amount, $catalog_bal, $stock_bal, $description, $timestamp)
    {
        parent::__construct($txid, $accountNo, $whenBooked, $whenCharged);
        $this->resourceId = $resource_id;
        $this->stock_bal = $stock_bal;
        $this->catalog_bal = $catalog_bal;
        $this->amount = $amount;
        $this->txType = $txType;
        $this->description = $description;
        $this->timestamp = $timestamp;
        //create database entry
    }

    public static function CreateEntry($txId, Account $account, $txType, $amount, $description, $action, $whenBooked=null)
    {
        $datetime = new DateTime();
        $timestamp = $datetime->format('YmdHis');//('Y-m-d H:i:s');
        $whenCharged = $datetime->format('Y-m-d H:i:s');
        if (empty($whenBooked)) {
            $whenBooked = $whenCharged;
        }

        $resource_id = $account->item->itemId;

        if ($txType == 1) {//deposit
            //$account->deposit($amount);
            if ($action == 1) {//'proposed'
                $account->addAvailable($amount);
            }elseif ($action == 2) {//'implemented'
                $account->addActual($amount);
            }            
        }elseif ($txType == 2) {//withdrawal
            if ($action == 1) {
                $account->deductAvailable($amount);
            }elseif ($action == 2) {
                $account->deductActual($amount);
            }
        }

        $sql = 'INSERT INTO "'.$account::history.'" (account_no, transaction_id, when_charged, when_booked, timestamp, description, amount, transaction_type, action_type, catalog_bal, stock_bal) 
        VALUES ('.$account->account_no.', '.$txId.', "'.$whenCharged.'", "'.$whenBooked.'", '.$timestamp.', "'.$description.'", '.$amount.', '.$txType.', '.$action.', '.$account->catalog_bal.', '.$account->stock_bal.')';
        DatabaseHandler::Execute($sql);


        return new InventoryEntry($txId, $account->account_no, $account->item->itemId, $whenBooked, $whenCharged, $txType, $amount, $account->catalog_bal, $account->stock_bal, $description, $timestamp);
        //updateAccount - A/c Number

    }
}

class StockAccount extends HoldingAccount
{
    public static $ledgerName = 'Stock';
    public static $ledger = 'stock_accounts';
    public static $history = 'stock_accounts_history';
    public static $ledger_loaded = false;
    public static $ledgerId = 1;
    public static $ledger_bal = 0;
    public $account_no;
    public $account_name;
    public $item;
    public $packsize;
    public $featured;
    public $availability;
    public $catalog_bal;
    public $stock_bal;
    public $optimum_bal;
    public $low_bal;
    public $cost_price;
    public $retail_price;
    public $wholesale_price;
    public $taxcode;
    public $date_added;
    public $timestamp;
  
    public function __construct($account_id, Product $item, $packsize, $featured, $availability, $catalogbal, $stockbal, $optimumstock, $lowstock, $pprice, $rprice, $wprice, $tax, $dateadded, $timestamp)
    {
        $this->account_no = $account_id;
        $this->account_name = $item->name.' account';
        $this->item = $item;
        $this->packsize = $packsize;
        $this->featured = $featured;
        $this->availability = $availability;
        $this->catalog_bal = $catalogbal;
        $this->stock_bal = $stockbal;
        $this->optimum_bal = $optimumstock;
        $this->low_bal = $lowstock;
        $this->cost_price = $pprice;
        $this->retail_price = $rprice;
        $this->wholesale_price = $wprice;
        $this->taxcode = $tax;
        $this->date_added = $dateadded;
        $this->timestamp = $timestamp;
    }

    public static function LoadLedger($name = null, $classification = null)
    {
        //override this function with Ledgers::Create/Get
        $sql = 'SELECT * FROM accounting_ledgers WHERE name = "'.self::$ledgerName.'"';
        $ledger_account = DatabaseHandler::GetRow($sql);

        if (empty($ledger_account)) {
            $sql = 'INSERT INTO accounting_ledgers (name, classification, balance) VALUES ("'.self::$ledgerName.'", "Asset", 0)';
            DatabaseHandler::Execute($sql);
            $sql1 = 'SELECT * FROM accounting_ledgers WHERE name = "'.self::$ledgerName.'"';
            $ledger_account = DatabaseHandler::GetRow($sql1);            
        }

        self::$ledgerName = $ledger_account['name'];
        self::$ledgerId = $ledger_account['id'];
        self::$ledger_bal = $ledger_account['balance'];
        self::$ledger = strtolower($ledger_account['name']).'_accounts';
        self::$history = strtolower($ledger_account['name']).'_accounts_history';
        self::$ledger_loaded = true;
        
    }

    public static function CreateAccount($item, $packsize, $featured, $availability, $openstock, $optstock, $lowstock, $pprice, $rprice, $wprice, $tax)
    {
        if (!self::$ledger_loaded) {
           self::LoadLedger();
        }
        $datetime = new DateTime();
        $timestamp = $datetime->format('YmdHis');
        $acname = $item->name.' Account';
        $account;

        $sqlone = 'SELECT * FROM stock_accounts WHERE name = "'.$acname.'" AND resource_id = '.intval($item->itemId);
        $res = DatabaseHandler::GetRow($sqlone);

        if (empty($res)) {
            $today = new DateTime();
            $today = $today->format('Y-m-d');
            //$sql = 'UPDATE stock_accounts SET pack_size = '.$packsize.', featured = '.$featured.', availability = '.$availability.', available_bal = '.$openstock.', actual_bal = '.$openstock.', optimum_bal = '.$optstock.', low_bal = '.$lowstock.', timestamp = '.$timestamp.' WHERE resource_id = '.$item->itemId;
            $sql = 'INSERT INTO stock_accounts (name, resource_id, unit_id, stock_bal, catalog_bal, low_bal, optimum_bal, cost_price, retail_price, wholesale_price, vat_code, ledger_id, pack_size, featured, availability, date_added, tstamp) 
            VALUES ("'.$acname.'", '.intval($item->itemId).', '.intval($item->unit->unitId).', '.$openstock.', '.$openstock.', '.$lowstock.', '.$optstock.', '.$pprice.', '.$rprice.', '.$wprice.', '.$tax.', '.intval(self::$ledgerId).', '.$packsize.', '.$featured.', '.$availability.', "'.$today.'", "'.$timestamp.'")';
            DatabaseHandler::Execute($sql);
            //do something as an inventory objects
            $sql1 = 'SELECT account_id FROM stock_accounts ORDER BY tstamp DESC LIMIT 0,1';
            //replace with more flexible for distributed env
            $account_id = DatabaseHandler::GetOne($sql1);


            $availstock = $openstock;
            $actualstock = $openstock;
            return new StockAccount($account_id, $item, $packsize, $featured, $availability, $availstock, $actualstock, $optstock, $lowstock, $pprice, $rprice, $wprice, $tax, $today, $timestamp);
        }else{
            return new StockAccount($res['account_id'], $item, $res['pack_size'], $res['featured'], $res['availability'], $res['catalog_bal'], $res['stock_bal'], $res['optimum_bal'], $res['low_bal'], $res['cost_price'], $res['retail_price'], $res['wholesale_price'], $res['vat_code'], $res['date_added'], $res['tstamp']);
        }
    }

    public function updateInventoryDetails($packsize, $featured, $availability, $catalogbal, $stockbal, $optstock, $lowstock)
    {
        $sql = 'UPDATE stock_accounts SET pack_size = '.$packsize.', featured = '.$featured.', availability = '.$availability.', catalog_bal = '.$catalogbal.', stock_bal = '.$stockbal.', optimum_bal = '.$optstock.', low_bal = '.$lowstock.' WHERE resource_id = '.$this->item->itemId;
        DatabaseHandler::Execute($sql);
        //do something as an inventory objects
    }

    public function updatePricingDetails($pprice, $rprice, $wprice, $tax)
    {
        $sql = 'UPDATE stock_accounts SET cost_price = '.$pprice.', retail_price = '.$rprice.', wholesale_price = '.$wprice.', vat_code = '.$tax.' WHERE resource_id = '.$this->item->itemId;
        DatabaseHandler::Execute($sql);
        //do something as an inventory objects
    }

    /*public function processEntry(AccountEntry $entry)
    {//$entry == genereal resource allocation --- $entry->amount = Quantity[amount, unit]
        if ($this->item->itemId == $entry->resourceId) {
            # code...
        }
        $this->catalog_bal = $this->catalog_bal - $entry->quantity->amount;

        $sql = 'UPDATE stock_accounts SET  catalog_bal = '.$availstock.' WHERE account_id = '.$this->account_no;
        DatabaseHandler::Execute($sql);
        //do something as an inventory objects
    }*/

    public function addAvailable($amount)
    {//$entry == genereal resource allocation --- $entry->amount = Quantity[amount, unit]
        $this->catalog_bal = $this->catalog_bal + $amount;

        $sql = 'UPDATE stock_accounts SET catalog_bal = '.$this->catalog_bal.' WHERE account_id = '.$this->account_no;
        DatabaseHandler::Execute($sql);
        
        //return $amount; or balance
    }

    public function addActual($amount)
    {//$entry == genereal resource allocation --- $entry->amount = Quantity[amount, unit]
        $this->stock_bal = $this->stock_bal + $amount;

        $sql = 'UPDATE stock_accounts SET stock_bal = '.$this->stock_bal.' WHERE account_id = '.$this->account_no;
        DatabaseHandler::Execute($sql);
        
        //return $amount; or balance

    }

    public function deductAvailable($amount)
    {//$entry == genereal resource allocation --- $entry->amount = Quantity[amount, unit]
        $this->catalog_bal = $this->catalog_bal - $amount;

        $sql = 'UPDATE stock_accounts SET catalog_bal = '.$this->catalog_bal.' WHERE account_id = '.$this->account_no;
        DatabaseHandler::Execute($sql);
        
        //return $amount; or balance
    }

    public function deductActual($amount)
    {//$entry == genereal resource allocation --- $entry->amount = Quantity[amount, unit]
        $this->stock_bal = $this->stock_bal - $amount;

        $sql = 'UPDATE stock_accounts SET stock_bal = '.$this->stock_bal.' WHERE account_id = '.$this->account_no;
        DatabaseHandler::Execute($sql);
        
        //return $amount; or balance
    }  

    public static function GetProducts()
    {
        //$sql = 'UPDATE products SET pack_size = '.$packsize.', featured = '.$featured.', availability = '.$availability.', catalog_bal = '.$openstock.', stock_bal = '.$openstock.', optimum_bal = '.$optstock.', low_bal = '.$lowstock.' WHERE id = '.$item->itemId;
        $sql = 'SELECT * FROM products';
        $products = DatabaseHandler::GetAll($sql);
        //do something as an inventory objects

        return $products;
    }

    public static function GetStockItems()
    {
        //$sql = 'UPDATE products SET pack_size = '.$packsize.', featured = '.$featured.', availability = '.$availability.', catalog_bal = '.$openstock.', stock_bal = '.$openstock.', optimum_bal = '.$optstock.', low_bal = '.$lowstock.' WHERE id = '.$item->itemId;
        $stock_accounts = array();
        $sql = 'SELECT * FROM stock_accounts';
        $stocks = DatabaseHandler::GetAll($sql);

        foreach ($stocks as $stock) {
            $sql = 'SELECT * FROM products WHERE resource_id = '.$stock['resource_id'];
            $sqlf = 'SELECT * FROM features WHERE resource_id = '.$stock['resource_id'];
            
            $product = DatabaseHandler::GetRow($sql);
            $features =  DatabaseHandler::GetAll($sqlf);
            
            //$sqlf = 'SELECT * FROM item_features WHERE item_id = '.$product['id'];
            $typedata = ResourceType::GetTypeData($product['type_id']);
            $unit = Unit::GetUnitById($typedata['unit_id']);

            $item = new Product($product['resource_id'], $product['type_id'], $typedata['type'], $product['name'], $product['reference'], $unit, $product['description'], $features, $product['manufacturer'], $product['retail_price'], $product['wholesale_price'], $product['tax_code'], $product['img_url'], $product['plength'], $product['pwidth'], $product['pheight'], $product['pweight'], $product['pshape']);
            
            $stockAccount = new StockAccount($stock['account_id'], $item, $stock['pack_size'], $stock['featured'], $stock['availability'], $stock['catalog_bal'], $stock['stock_bal'], $stock['optimum_bal'], $stock['low_bal'], $stock['cost_price'], $stock['retail_price'], $stock['wholesale_price'], $stock['vat_code'], $stock['date_added'], $stock['tstamp']);
            
            $stock_accounts[] = $stockAccount;


        }
        //do something as an inventory objects

       return $stock_accounts;
    }

    public static function GetLatestItems($number)
    {
        //$sql = 'UPDATE products SET pack_size = '.$packsize.', featured = '.$featured.', availability = '.$availability.', catalog_bal = '.$openstock.', stock_bal = '.$openstock.', optimum_bal = '.$optstock.', low_bal = '.$lowstock.' WHERE id = '.$item->itemId;
        $stock_accounts = array();
        $sql = 'SELECT * FROM stock_accounts ORDER BY tstamp DESC LIMIT 0,'.intval($number);
        $stocks = DatabaseHandler::GetAll($sql);

        foreach ($stocks as $stock) {
            $sql = 'SELECT * FROM products WHERE resource_id = '.$stock['resource_id'];
            $sqlf = 'SELECT * FROM features WHERE resource_id = '.$stock['resource_id'];
            
            $product = DatabaseHandler::GetRow($sql);
            $features =  DatabaseHandler::GetAll($sqlf);
            
            //$sqlf = 'SELECT * FROM item_features WHERE item_id = '.$product['id'];
            $typedata = ResourceType::GetTypeData($product['type_id']);
            $unit = Unit::GetUnitById($typedata['unit_id']);

            $item = new Product($product['resource_id'], $product['type_id'], $typedata['type'], $product['name'], $product['reference'], $unit, $product['description'], $features, $product['manufacturer'], $product['retail_price'], $product['wholesale_price'], $product['tax_code'], $product['img_url'], $product['plength'], $product['pwidth'], $product['pheight'], $product['pweight'], $product['pshape']);
            
            $stockAccount = new StockAccount($stock['account_id'], $item, $stock['pack_size'], $stock['featured'], $stock['availability'], $stock['catalog_bal'], $stock['stock_bal'], $stock['optimum_bal'], $stock['low_bal'], $stock['cost_price'], $stock['retail_price'], $stock['wholesale_price'], $stock['vat_code'], $stock['date_added'], $stock['tstamp']);
            
            $stock_accounts[] = $stockAccount;


        }
        //do something as an inventory objects

       return $stock_accounts;
    }
}

class StockInventory 
{//extends summary account inplements account_ledger
    //An inventory links a product type with 
    public static $Inventory = array();//array of inventory_items
    public static $loaded = false;
    public $name;
  
    //should this account contain both stock balance and a monetary equivalent?
    //should provide link to receipt or invoice/delivery

    function __construct($name = null)
    {
        $this->name = $name || 'Stock Inventory';
        //parent::__construct($this->name);
        //autoload the catalog from the database with objects
    }

    private static function loadItems()
    {
        //1 = Good, 2 = Service [availability, level_of_service = Domestic, Corporate , rate per hour/day for single personell[sale price], features, images, provider, ]

        self::$Inventory[] = StockAccount::GetStockItems();

        self::$loaded = true;
        
    }
    //($name, $type, $ref, $desc, $cat, $manufacturer, $avail, $featured, $features, $pprice, $rprice, $wprice, $tax, $img, $openstock, $optstock, $lowstock, $pheight, $pwidth, $plength, $pweight, $shape, $pack, $images);
    public static function CreateItem($name, $type, $ref, $desc, $categories, $manufacturer, $avail, $featured, $features, $pprice, $rprice, $wprice, $tax, $img, $openstock, $optstock, $lowstock, $pheight, $pwidth, $plength, $pweight, $pshape, $pack, $images)
    {
        $product = Product::Create($name, $ref, $desc, $manufacturer, $features, $pprice, $rprice, $wprice, $tax, $img, $pheight, $pwidth, $plength, $pweight, $pshape, $images);

        $stockItem = StockAccount::CreateAccount($product, $pack, $featured, $avail, $openstock, $optstock, $lowstock, $pprice, $rprice, $wprice, $tax);
        //include store_name

        Category::TagItem($product, $categories);

        if (!self::$loaded) {
            self::loadItems();
        }
        
        self::$Inventory[] = $stockItem;
        
        return $stockItem;
    }

    public static function UpdateItem($id, $name, $ref, $desc, $categories, $manufacturer, $avail, $featured, $features, $pprice, $rprice, $wprice, $tax, $img, $openstock, $optstock, $lowstock, $pheight, $pwidth, $plength, $pweight, $pshape, $pack, $images)
    {
        /*$product = Product::Update($id, $name, $ref, $desc, $manufacturer, $features, $pprice, $rprice, $wprice, $tax, $img, $pheight, $pwidth, $plength, $pweight, $pshape, $images)

        $stockItem = StockAccount::UpdateRegistration($id, $product, $pack, $featured, $avail, $openstock, $optstock, $lowstock);
        //include store_name

        Category::UpdateTags($stockItem, $categories)

        if (!self::$loaded) {
            self::$loadItems();
        }
        
        self::$Inventory[] = $stockItem;
        
        return $stockItem;*/
    }

    public static function GetItem($itemId)
    {
        $item;
        //first_check_cache, if empty call loadItems(), if still empty return empty
        if (!self::$loaded) {
            self::loadItems();
        }

        foreach (self::$Inventory[0] as $stockItem) {
           if ($stockItem->item->itemId == $itemId) {
              $item = $stockItem;
           }
        }

        if (isset($item)) {
           return $item;
        }else{
            return 0;
        }
    }

    public static function GetInventory()
    {
        //first_check_cache, if empty call loadItems(), if still empty return empty
        if (!self::$loaded) {
            self::loadItems();
        }

        return self::$Inventory[0];
        /*$count = count(self::$Inventory[0]);
        //return $count;
        if (($page * 30) > $count) {
            if ($count >= 30) {
                return array_slice(self::$Inventory[0], $count - 31, $count - 1);
            }else{
                return array_slice(self::$Inventory[0], 0, $count);
            }
           
        }else{
            return array_slice(self::$Inventory[0], ($page - 1) * 30, ($page * 30) - 1);
        }*/
    }

    public static function GetFeatured($number = 3)
    {
        //first_check_cache, if empty call loadItems(), if still empty return empty
        if (!self::$loaded) {
            self::loadItems();
        }
        
        $result = array();
        $counter = 1;
        foreach (self::$Inventory[0] as $item) {
            
            if ($counter <= $number) {
                if (intval($item->featured) == 1) {
                   $result[] = $item;
                   $counter++;
                }
            }else{
                break;
            }
        }
        
        return $result;
    }

    public static function GetLatest($number = 12)
    {
        //first_check_cache, if empty call loadItems(), if still empty return empty        
        return StockAccount::GetLatestItems($number);
    }

    public function adjustStock(ItemType $type, Quantity $quantity)
    {
        //adjust the available balance and/or actual balance
        if (!self::$loaded) {
            self::loadItems();
        }
    }

    public function receiveReturnedGoods(Party $customer, InventoryEntry $entry)
    {//party: customer or sales agent
        //refactor to Transaction: [Transaction Type - Receive Returned Goods]
        //affectes a 2 financial accounts [accounts receivable/sales and customer account] 
        //and 2 holding accounts [this and goods issued account]
        if (!self::$loaded) {
            self::loadItems();
        }

        $customerAccount = self::$getAccount($customer->stockAccountNumber);
        $this->increaseStock($entry);
        $purchasesAccount->decreaseSales($entry);
    }

    public function issueGoods(Party $branch, InventoryEntry $entry)
    {// may be employee, customer, department, branch etc
        //refactor to Transaction: [Transaction Type - Receive Returned Goods]
        if (!self::$loaded) {
            self::loadItems();
        }
        //affectes a 2 holding accounts [this and customer account] and this holding account
        if ($this->verifyAvailability($entry)) {
            $this->decreaseStock($entry);
        }    
    }

    public function receivePurchasedGoods(Party $supplier, InventoryEntry $entry)
    {
        if (!self::$loaded) {
            self::loadItems();
        }
        //for each entry
        $this->balance = intval($this->balance) + $entry;
        $this->saveBalance();
    }    
}

class Stores
{//5th dimension
  public static $inventories = array();

  public function __construct($txId, Inventory $account, ProductItem $item, Quantity $amount)
    {
        //$item = new ProductItem();
        $eventId = $txId;
        parent::__construct($eventId, $account, $item);
    }
}

//sales account/accounts receivable - sales channel feature
?>