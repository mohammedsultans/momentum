<?php
session_start(); 
require_once('/../domain/LSOfficeDomain.php');

class CRMTest extends PHPUnit_Framework_TestCase
{
    private $modid;
    public function setUp(){ }
    public function tearDown(){ }

    public static function setUpBeforeClass()
    {
        
    }

    public static function tearDownAfterClass()
    {
        
    }


    public function testCreateCategory() 
    {
        ItemCategory::Create('Test Category');
        $cat = ItemCategory::FindByName('Test Category');
        $this->assertInstanceOf('ItemCategory', $cat);
        $this->assertTrue($cat->name == 'Test Category');
    }

    public function testUpdateCategory() 
    {
        $cat = ItemCategory::FindByName('Test Category');
        ItemCategory::Update($cat->id, 'Test Category 2');
        $cat = ItemCategory::FindObject($cat->id);
        $this->assertInstanceOf('ItemCategory', $cat);
        $this->assertTrue($cat->name == 'Test Category 2');
    }

    public function testDeleteCategory() 
    {
        $cat = ItemCategory::FindByName('Test Category 2');
        ItemCategory::Delete($cat->id);
        $cat = ItemCategory::FindObject($cat->id);
        $this->assertTrue($cat == null);
    }

    public function testCreateStock() 
    {
    	//API - Item::CreateStock($name, $margin, $vat, $category);
    	//Test Validation
        $item = Item::CreateStock('Test Stock Name', 50, 3, 0, 10, '');
        $this->assertInstanceOf('Item', $item);
        $this->assertTrue(filter_var($item->id, FILTER_VALIDATE_INT) == true);
        $this->assertTrue($item->name == 'Test Stock Name');
        $this->assertTrue($item->margin == 50);
        $this->assertTrue($item->scope == 3);
        $this->assertTrue($item->vat == 0);
        $this->assertTrue($item->category == 10);
        $this->assertTrue($item->description == '');
    }

    public function testUpdateStock()
    {
        $item = Item::FindByNameType('Test Stock Name', 'Stock');
        Item::UpdateStock($item->id, 'Test Stock Name 2', 55, 2, 16.2, 12, 'description');
        $newitem = Item::FindObject($item->id);
        $this->assertInstanceOf('Item', $newitem);
        $this->assertTrue($newitem->id == $item->id);
        $this->assertTrue($newitem->name == 'Test Stock Name 2');
        $this->assertTrue($newitem->margin == 55);
        $this->assertTrue($newitem->scope == 2);
        $this->assertTrue($newitem->vat == 16.2);
        $this->assertTrue($newitem->category == 12);
        $this->assertTrue($newitem->description == 'description');
    }

    public function testCreateService() 
    {
        $item = Item::CreateService('Test Service Name', 2, 0, 10, '');
        $this->assertInstanceOf('Item', $item);
        $this->assertTrue(filter_var($item->id, FILTER_VALIDATE_INT) == true);
        $this->assertTrue($item->name == 'Test Service Name');
        $this->assertTrue($item->scope == 2);
        $this->assertTrue($item->vat == 0);
        $this->assertTrue($item->category == 10);
        $this->assertTrue($item->description == '');
    }

    public function testUpdateService() 
    {
        $item = Item::FindByNameType('Test Service Name', 'Service');
        Item::UpdateService($item->id, 'Test Service Name 2', 3, 16.2, 12, 'description');
        $newitem = Item::FindObject($item->id);
        $this->assertInstanceOf('Item', $newitem);
        $this->assertTrue($newitem->id == $item->id);
        $this->assertTrue($newitem->name == 'Test Service Name 2');
        $this->assertTrue($newitem->scope == 3);
        $this->assertTrue($newitem->vat == 16.2);
        $this->assertTrue($newitem->category == 12);
        $this->assertTrue($newitem->description == 'description');
    }

    public function testCreateAsset() 
    {
        $item = Item::CreateAsset('Test Asset Name', 120, 10, '');
        $this->assertInstanceOf('Item', $item);
        $this->assertTrue(filter_var($item->id, FILTER_VALIDATE_INT) == true);
        $this->assertTrue($item->name == 'Test Asset Name');
        $this->assertTrue($item->category == 10);
        $this->assertTrue($item->scope == 3);
        $this->assertTrue($item->description == '');
        $this->assertTrue($item->ledger == 120);
    }

    public function testUpdateAsset() 
    {
        $item = Item::FindByNameType('Test Asset Name', 'Asset');
        Item::UpdateAsset($item->id, 'Test Asset Name 2', 121, 12, 'description');
        $newitem = Item::FindObject($item->id);
        $this->assertInstanceOf('Item', $newitem);
        $this->assertTrue($newitem->id == $item->id);
        $this->assertTrue($newitem->name == 'Test Asset Name 2');
        $this->assertTrue($newitem->category == 12);
        $this->assertTrue($newitem->scope == 3);
        $this->assertTrue($newitem->description == 'description');
        $this->assertTrue($newitem->ledger == 121);
    }

    public function testDeleteItem() 
    {
        $items = Item::GetAll();
        $stock = Item::FindByNameType('Test Stock Name 2', 'Stock');
        $service = Item::FindByNameType('Test Service Name 2', 'Service');
        $asset = Item::FindByNameType('Test Asset Name 2', 'Asset');
        $this->assertTrue(Item::Delete($stock->id) == true);
        $this->assertTrue(Item::Delete($service->id) == true);
        $this->assertTrue(Item::Delete($asset->id) == true);
        $stock = Item::FindObject($stock->id);
        $service = Item::FindObject($service->id);
        $asset = Item::FindObject($asset->id);
        $this->assertTrue($stock == null);
        $this->assertTrue($service == null);
        $this->assertTrue($asset == null);
        $items_again = Item::GetAll();
        $this->assertTrue(count($items) == (count($items_again) + 3));
    }

    public function testFindItem() 
    {
        $item = Item::CreateAsset('Test Item Name', 120, 10, '', 120);
        $item = Item::FindByNameType('Test Item Name', 'Asset');
        $newitem = Item::FindObject($item->id);
        $this->assertTrue($item == $newitem);
    }

    public function testFindAllItems() 
    {
        $items_before = Item::GetAll();
        $item = Item::CreateAsset('Test Item Name Additional', 120, 10, '', 120);
        $items_after = Item::GetAll();
        $this->assertTrue(count($items_after) == (count($items_before) + 1));
        foreach ($items_after as $itm) {
        	$this->assertInstanceOf('Item', $itm);
        }
        Item::Delete($item->id);
        $post_after = Item::GetAll();
        $this->assertTrue(count($post_after) == count($items_before));
    }

    public function testItemReceiveInwards() 
    {
        
    }

    public function testStockUsage() 
    {
        
    }

    public function testStockValuation() 
    {
        
    }

    public function testStockAdjustment() 
    {
        
    }

    public function testItemReturnedOutwards() 
    {
        
    }
}
?>


