<?php

require_once 'Accounting.php';
/*
class ProductType extends ConsumableType
{   
    public $costPrice;
    public $retailPrice;
    public $wholesalePrice;
    public $status;
    public $description;
    public $display;//category/ department/ both/ none
    public $mainImage;
    public $attributeTypes; // array() -- class, color, material, options of the same type
    // [productTypeId, {attributeType, [{attributeName, value}*]}*]
    //public $category; watches/mens watches/mechanical watches -- phones/business phones/qwerty phone
    //all these are categories a product can fall into
    public $categories;
    public $taxCode;
    public $packageContents; //array() to string/ strexplode/ json object

    function __construct($typeName)
    {
        $unit = new Item();
        parent::__construct($typeName, $unit);
    }

    public static function create(ProductType $type, Unit $unit)
    {
        
    }

    public static function getProductByName($name) 
    {

    }

    public static function getProductById($id) 
    {

    }

}

class ProductItem extends Resource
{
    
    public $itemId; //serial number
    public $batchNumber;
    public $serialNumber;
    public $attributes = array();
 
    public function __construct(ProductType $type, Quantity $quantity)
    {
        parent::__construct($type, $quantity);
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

    public function save(){

    }
}
*/
class ProductEntry extends ConsumableResourceAllocation
{//5th dimension
  
  public function __construct($eventId, Inventory $account, ProductItem $item)
    {
        //$item = new ProductItem();
        parent::__construct($eventId, $account, $item);
    }
}
/*
class TransactionType extends Protocol
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
    public function __construct(Inventory $sourceAcc, Inventory $destinationAcc, TransactionType $ttype, ResourceType $rtype, Quantity $quantity, $description)
    {
      parent::__construct($sourceAcc, $destinationAcc, $ttype, $rtype, $quantity, $description);
      //e.g supplier account, warehouse account, 'Goods Received Inwards', Samsung Fridge BF-X450, 14 items, 'delivery from supplier xxxx'
    }

    public function createEntry($account)
    {
        $item = new ProductItem($this->resourceType, $this->amount);
        return new ProductEntry($this->transactionId, $account, $item);
    }
}


*/
//sales account/accounts receivable - sales channel feature
?>