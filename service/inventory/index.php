<?php
  	require_once '../../include/config.php';
  	require_once DOMAIN_DIR . 'LSOfficeDomain.php';
	
	class InventoryApp
	{
		/* 	Variable available to calling function */
		public function __construct()
		{ 
			if(isset($_POST['operation'])){
				$operation = $_POST['operation'];
				if($operation == 'createCategory'){
					if(isset($_POST['name'])){
						$name = $_POST['name'];
						$this->createCategory($name);
					}else{
						echo 0;
					}
				}elseif($operation == 'updateCategory'){
					if(isset($_POST['id']) && isset($_POST['name'])){
						$id = $_POST['id'];
						$name = $_POST['name'];
						$this->updateCategory($id, $name);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'deleteCategory'){
					if(isset($_POST['id'])){
						$this->deleteCategory($_POST['id']);
					}else{
						echo 0;
					}
				}elseif($operation == 'createStock'){
					if(isset($_POST['name']) && isset($_POST['margin']) && isset($_POST['scope']) && isset($_POST['vat']) && isset($_POST['category']) && isset($_POST['description'])){
						$name = $_POST['name'];
						$margin = $_POST['margin'];
						$scope = $_POST['scope'];
						$vat = $_POST['vat'];
						$category = $_POST['category'];
						$desc = $_POST['description'];
						$this->createStock($name, $margin, $scope, $vat, $category, $desc);
					}else{
						echo 0;
					}
				}elseif($operation == 'updateStock'){
					if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['margin']) && isset($_POST['scope']) && isset($_POST['vat']) && isset($_POST['category']) && isset($_POST['description'])){
						$id = $_POST['id'];
						$name = $_POST['name'];
						$margin = $_POST['margin'];
						$scope = $_POST['scope'];
						$vat = $_POST['vat'];
						$category = $_POST['category'];
						$desc = $_POST['description'];
						$this->updateStock($id, $name, $margin, $scope, $vat, $category, $desc);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'createService'){
					if(isset($_POST['name']) && isset($_POST['scope']) && isset($_POST['vat']) && isset($_POST['category']) && isset($_POST['description'])){
						$name = $_POST['name'];
						$scope = $_POST['scope'];
						$vat = $_POST['vat'];
						$category = $_POST['category'];
						$desc = $_POST['description'];
						$this->createService($name, $scope, $vat, $category, $desc);
					}else{
						echo 0;
					}
				}elseif($operation == 'updateService'){
					if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['scope']) && isset($_POST['vat']) && isset($_POST['category']) && isset($_POST['description'])){
						$id = $_POST['id'];
						$name = $_POST['name'];
						$scope = $_POST['scope'];
						$vat = $_POST['vat'];
						$category = $_POST['category'];
						$desc = $_POST['description'];
						$this->updateService($id, $name, $scope, $vat, $category, $desc);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'createAsset'){
					if(isset($_POST['name']) && isset($_POST['ledger']) && isset($_POST['category']) && isset($_POST['description'])){
						$name = $_POST['name'];
						$ledger = $_POST['ledger'];
						$category = $_POST['category'];
						$desc = $_POST['description'];
						$this->createAsset($name, $ledger, $category, $desc);
					}else{
						echo 0;
					}
				}elseif($operation == 'updateAsset'){
					if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['ledger']) && isset($_POST['category']) && isset($_POST['description'])){
						$id = $_POST['id'];
						$name = $_POST['name'];
						$ledger = $_POST['ledger'];
						$category = $_POST['category'];
						$desc = $_POST['description'];
						$this->updateAsset($id, $name, $ledger, $category, $desc);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'deleteItem'){
					if(isset($_POST['id'])){
						$this->deleteItem($_POST['id']);
					}else{
						echo 0;
					}
				}else{ 
					echo 0;
				}
			}elseif(isset($_GET['item'])){
				$this->getItem($_GET['item']);
			}elseif(isset($_GET['allItems'])){
				$this->allItems();
			}elseif(isset($_GET['typeItems']) && isset($_GET['type'])){
				$this->typeItems($_GET['type']);
			}elseif(isset($_GET['itemCategories'])){
				$this->itemCategories();
			}else{
				echo 0;
			}
		}

		public function createCategory($name)
		{
			if (ItemCategory::Create($name)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function createStock($name, $margin, $scope, $vat, $category, $desc)
		{
			if (Item::CreateStock($name, $margin, $scope, $vat, $category, $desc)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function createService($name, $scope, $vat, $category, $desc)
		{
			if (Item::CreateService($name, $scope, $vat, $category, $desc)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function createAsset($name, $ledger, $category, $desc)
		{
			if (Item::CreateAsset($name, $ledger, $category, $desc)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function updateCategory($id, $name)
		{
			if (ItemCategory::Update($id, $name)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function updateStock($id, $name, $margin, $scope, $vat, $category, $desc)
		{
			if (Item::UpdateStock($id, $name, $margin, $scope, $vat, $category, $desc)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function updateService($id, $name, $scope, $vat, $category, $desc)
		{
			if (Item::UpdateService($id, $name, $scope, $vat, $category, $desc)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function updateAsset($id, $name, $ledger, $category, $desc)
		{
			if (Item::UpdateAsset($id, $name, $ledger, $category, $desc)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function deleteCategory($id)
		{
			if (ItemCategory::Delete($id)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function deleteItem($id)
		{
			if (Item::Delete($id)) {
				echo 1;
			}else{
				echo 0;
			}
		}

		public function getItem($id)
		{
			echo json_encode(Item::FindObject($id));
		}

		public function allItems()
		{
			echo json_encode(Item::GetAll());
		}

		public function typeItems($type)
		{
			echo json_encode(Item::GetByType($type));
		}

		public function itemCategories()
		{
			echo json_encode(ItemCategory::FindAll());
		}
		
	}

	/*$request_method = strtolower($_SERVER['REQUEST_METHOD']);
	//echo $request_method;
	$data = null;

	switch ($request_method) {
	    case 'post':
	    case 'put':
	        $data = json_decode(file_get_contents('php://input'));
	    break;
	}*/

	$response = new InventoryApp();
	//$response->init();
	//echo json_encode($response->mJournals);
?>