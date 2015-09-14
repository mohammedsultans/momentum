<?php
require_once DATA_DIR . 'error_handler.php';
ErrorHandler::SetHandler();
require_once DATA_DIR . 'database_handler.php';

require_once 'Accounting.php';

class ItemType extends ConsumableType
{ 
    function __construct($typeId, $typeName, $unit)
    {
        parent::__construct($typeId, $typeName, $unit);
    }
 
}

class ProductImage
{
    public static $images = array();
    public static $allowableAttributes = array();
    public $imageId;
    public $imageName;
    public $imageUrl;
    public $features = array(); //push array('attribute' => 'value')

    function __construct($imageId, $imageName, $imageUrl, $features)
    {
       $this->imageId = $imageId;
       $this->imageName = $imageName;
       $this->imageUrl = $imageUrl;
       $this->features = $features;
    }

    public static function FetchImages($itemId)
    {
        $sql = 'SELECT * FROM images WHERE resource_id = '.$itemId.' GROUP BY image_url';
        $images = DatabaseHandler::GetAll($sql);
        foreach ($images as $image) {            

            $sql = 'SELECT * FROM images WHERE image_url = "'.$image['image_url'].'" AND resource_id = '.$itemId;
            $data = DatabaseHandler::GetAll($sql);
            
            $features = array();
            
            foreach ($data as $dat) {
                $features[] = array($dat['tag'] => $dat['value'] );
            }

            $imageObj = new ProductImage($image['image_id'], $image['image_title'], $image['image_url'], $features);

            self::$images[] = $imageObj;
        }

       return self::$images;
    }
}

class Product extends ItemType
{    
    public $itemId;
    public $name;
    public $reference;
    public $description;   
    public $features = array();//feature object: feature id, attribute - face material, wrist material, movement, color, 
    public $retail_price;
    public $wholesale_price;
    public $taxcode;
    public $image;    
    public $manufacturer;
    public $images = array();//image object - item_id, image_id, image-url, attribute, value

    //Shipping info
    //public $handling = array();// -- upgrade
    //public $sensitivity = array(); // Array('heat' => [min_val, max_val], 'heat' => [min_val, max_val]) -- upgrade
    //public $pdimensions = array(); // l,w,h
    //public $pweight;
   // public $pshape;

    public function __construct($itemId, $typeId, $typeName, $name, $ref, $unit, $desc, $features, $manufacturer, $rprice, $wprice, $tax, $img, $plength, $pwidth, $pheight, $pweight, $pshape)
    {
        parent::__construct($typeId, $typeName, $unit);
        $this->itemId = $itemId;
        $this->name = $name;
        $this->reference = $ref;
        $this->description = $desc;
        $this->retail_price = $rprice;
        $this->wholesale_price = $wprice;
        $this->taxcode = $tax;
        $this->image = $img;
        $this->manufacturer = $manufacturer;

        $this->setFeatures($features);
        $this->setImages();
        $this->setShippingDetails($plength, $pwidth, $pheight, $pweight, $pshape);
    }

    private function setFeatures($features)
    {
        //for each loop
        foreach ($features as $feature) {
            $this->features[] = $feature;
        }
    }

    private function setImages()
    {       //for each loop{
        $this->images = ProductImage::FetchImages($this->itemId);
    }
    
    public static function Create($name, $ref, $desc, $manufacturer, $features, $pprice, $rprice, $wprice, $tax, $img, $pheight, $pwidth, $plength, $pweight, $pshape, $images)
    {
        $unit = Enumerable::Create('Item');//temporal, individual, 
        $typedata = ResourceType::FetchType("Good", $unit);

        $sqlone = 'SELECT * FROM products WHERE name = "'.$name.'" AND reference = "'.$ref.'"';
        $res = DatabaseHandler::GetRow($sqlone);

        if (empty($res)) {
            //create product
            $sql = 'INSERT INTO products (type_id, type_name, name, reference, description, retail_price, wholesale_price, tax_code, img_url, manufacturer) 
            VALUES ('.$typedata['id'].', "'.$typedata['type'].'", "'.$name.'", "'.$ref.'", "'.$desc.'", "'.$rprice.'", "'.$wprice.'", "'.$tax.'", "'.$img.'", "'.$manufacturer.'")';
            DatabaseHandler::Execute($sql);

            $sqlone = 'SELECT resource_id FROM products WHERE name = "'.$name.'" AND reference = "'.$ref.'"';
            $res = DatabaseHandler::GetRow($sqlone);

            //create product features
            foreach ($features as $feature) {
                $sql = 'INSERT INTO features (resource_id, attribute) VALUES ('.$res['resource_id'].', "'.$feature.'")';
                DatabaseHandler::Execute($sql);
            }

            $sqltwo = 'SELECT * FROM features WHERE resource_id = '.$res['resource_id'];
            $features = DatabaseHandler::GetAll($sqltwo);

            //create product images
            foreach ($images as $image) {
                foreach ($image['features'] as $feature) {
                    $sql = 'INSERT INTO images (resource_id, image_title, image_url, tag, value) VALUES ('.$res['resource_id'].', "'.$image['imgtitle'].'", "'.$image['imgurl'].'", "'.$feature['feature'].'", "'.$feature['value'].'")';
                    DatabaseHandler::Execute($sql);
                }
                
            }

            //$sqltwo = 'SELECT * FROM images WHERE resource_id = '.$res['resource_id'];
            //$images = ProductImage::FetchImages($res['resource_id']);
        }    

        $product = new Product($res['resource_id'], $typedata['id'], $typedata['type'], $name, $ref, $unit, $desc, $features, $manufacturer, $rprice, $wprice, $tax, $img, $plength, $pwidth, $pheight, $pweight, $pshape);

        return $product;
        

        // $product->setShippingDetails($res['id'], $plength, $pwidth, $pheight, $pweight, $pshape);
        /*/after creation it returns the product object to be used in the inventory, shipping and category classes
        $couriers, $shippingmethod = [$road, $air, $sea, $space] //shipping class - dispatch and delivery
        $cat //category class
        $avail, $featured, $openstock, $optstock, $lowstock //inventory class
        $pprice // determined by supplier and varies with batch -- same product different batches and prices will lead to different valuation of stock
        */
    }

    public static function GetProductByReference($ref) 
    {

    }

    public static function GetProductById($id) 
    {

    }

    public function addFeature($name, $value)
    {
        #check if is in the list of allowable attributes

        //name - key => value
        //image - 'blue' => blue_watch.jpg
        //appearance - strap => 'leather'
        //appearance - color => 'red'
        
        //$party = array($key=>$value);
        //$this->attributes[$name][count($this->attributes)] = $value;
        $this->attributes[$name] = $value;

    }

    public function getFeature($key)
    {
        $this->features[$key] = $value;
    }

    public function setShippingDetails($plength, $pwidth, $pheight, $pweight, $pshape)
    {
        $sql = 'UPDATE products SET plength = '.$plength.', pwidth = '.$pwidth.', pheight = '.$pheight.', pweight = '.$pweight.', pshape = "'.$pshape.'" WHERE resource_id = '.$this->itemId;
        DatabaseHandler::Execute($sql);
    }
}

class Item extends Resource
{
    public $attributes = array();
 
    public function __construct(ItemType $type, Quantity $quantity)
    {
        parent::__construct($type, $quantity);
    }

    public function save(){

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