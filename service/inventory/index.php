<?php
// Manages the Journals list
  	require_once '../../include/config.php';
  	require_once DOMAIN_DIR . 'Inventory.php';
  	//require_once DOMAIN_DIR . 'Product.php';
	
	class Inventory
	{
		/* 	Variable available to calling function */
		
		public $mSelectedDepartment = 0;
		private $mJournals, $mArticles, $mIssues, $mCategories;
		// Constructor reads query string parameter
		public function __construct()
		{
			if(isset($_POST['operation'])){
				$operation = $_POST['operation'];
				if($operation == 'createProduct'){
					if(isset($_POST['itemname']) && isset($_POST['rprice']) && isset($_POST['opening-stock'])){
						$name = $_POST['itemname'];
						$type = $_POST['itemtype'];
						$ref = $_POST['reference'];
						$desc = $_POST['description'];
						$cat = $_POST['categories'];
						$manufacturer = $_POST['manufacturer'];
						$avail = $_POST['available'];
						$featured = $_POST['featured'];
						if (isset($_POST['features'])) {
							$features = $_POST['features'];
						}else{
							$features = [];
						}
						$pprice = $_POST['pprice'];
						$rprice = $_POST['rprice'];
						$wprice = $_POST['wprice'];
						$tax = $_POST['tax'];
						$img = $_POST['mainimage'];
						$openstock = $_POST['opening-stock'];
						$optstock = $_POST['optimum-stock'];
						$lowstock = $_POST['low-stock'];
						$pheight = $_POST['height'];
						$pwidth = $_POST['width'];
						$plength = $_POST['length'];
						$pweight = $_POST['weight'];
						$shape = $_POST['shape'];
						$pack = $_POST['pack'];
						if (isset($_POST['images'])) {
							$images = $_POST['images'];
						}else{
							$images = [];
						}					
						$this->createProduct($name, $type, $ref, $desc, $cat, $manufacturer, $avail, $featured, $features, $pprice, $rprice, $wprice, $tax, $img, $openstock, $optstock, $lowstock, $pheight, $pwidth, $plength, $pweight, $shape, $pack, $images);
					}else{
						echo 0;
					}
						
				}elseif($operation == 'updateproduct'){
					if(isset($_POST['journalId']) && isset($_POST['url']) && isset($_POST['category']) && isset($_POST['name']) && isset($_POST['coverImgUrl'])){
						$journalId = $_POST['journalId'];
						$url = $_POST['url'];
						$category = $_POST['category'];
						$name = $_POST['name'];
						$cover_img = $_POST['coverImgUrl'];
						$scrp_fn = $_POST['scrapingFn'];
						$this->updateJournal($journalId, $url, $name, $category, $cover_img, $scrp_fn);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'deleteJournal'){
					if(isset($_POST['journalId'])){
						$journalId = $_POST['journalId'];
						$this->deleteJournal($journalId);
					}else{
						echo 0;
					}
				}elseif($operation == 'logout'){
					$this->logout();
				}elseif($operation == 'checkauth'){
					$this->check_auth();
				}else{ 
					echo 0;
				}
			}elseif(isset($_GET['stockInventory'])){
				if(isset($_POST['page'])){
					$page = $_POST['page'];
					$this->getStockInventory($page);
				}else{
					$this->getStockInventory(1);
				}
			}elseif(isset($_GET['products']) && isset($_GET['featured'])){
				$this->getFeatured();
			}elseif(isset($_GET['products']) && isset($_GET['latest'])){
				$this->getLatest();
			}elseif(isset($_GET['journal']) && isset($_GET['journalId'])){
				$journalId = $_GET['journalId'];
				$this->getJournal($journalId);
			}elseif(isset($_GET['journal']) && isset($_GET['journalName'])){
				$journalName = $_GET['journalName'];
				$this->getJournalByName($journalName);
			}elseif(isset($_GET['latestPrefferedArticles'])){
				$this->getLatestArticlesEmail();
			}elseif(isset($_GET['latestArticles'])){
				$this->getLatestArticles();
			}elseif(isset($_GET['latestIssue']) && isset($_GET['journalId'])){
				$journalId = $_GET['journalId'];
				$this->getLatestIssueArticles($journalId);
			}elseif(isset($_GET['latestIssue']) && isset($_GET['journalName'])){
				$journalName = $_GET['journalName'];
				$this->getLatestIssueArticlesByName($journalName);
			}elseif(isset($_GET['latestJournalIssue']) && isset($_GET['journalId'])){
				$journalId = $_GET['journalId'];
				$this->getLatestJournalIssue($journalId);
			}elseif(isset($_GET['latestJournalIssue']) && isset($_GET['journalName'])){
				$journalName = $_GET['journalName'];
				$this->getLatestJournalIssueByName($journalName);
			}elseif(isset($_GET['issue']) && isset($_GET['journalId'])){
				$journalId = $_GET['journalId'];
				$issue = $_GET['issue'];
				$this->getIssueArticles($journalId, $issue);
			}elseif(isset($_GET['categoryId']) && isset($_GET['journalId'])){
				$journalId = $_GET['journalId'];
				$categoryId = $_GET['categoryId'];
				$this->getCategorisedArticles($journalId, $categoryId);
			}elseif(isset($_GET['categoryLatest'])){
				$categoryId = $_GET['categoryId'];
				$this->getCategoryArticles($categoryId);
			}elseif(isset($_GET['issues']) && isset($_GET['journalId'])){
				$journalId = $_GET['journalId'];
				$this->getIssues($journalId);
			}elseif(isset($_GET['categories'])){
				$this->getCategories();
			}elseif (isset ($_POST['coverUpload'])){
				$this->uploadJournalCover();
			}elseif (isset ($_GET['search'])){
				$operation = $_GET['search'];
				if($operation == 'title'){
					if(isset($_GET['key']) && isset($_GET['category'])){
						$key = $_GET['key'];
						$categoryId = $_GET['category'];
						$this->searchArticleByTitle($key, $categoryId);
					}else{
						echo 0;
					}						
				}elseif($operation == 'category'){
					if(isset($_GET['key']) && isset($_GET['category'])){
						$key = $_GET['key'];
						$categoryId = $_GET['category'];
						$this->searchArticleByCategory($key, $categoryId);
					}else{
						echo 0;
					}
				}elseif($operation == 'author'){
					if(isset($_GET['key']) && isset($_GET['category'])){
						$key = $_GET['key'];
						$categoryId = $_GET['category'];
						$this->searchArticleByAuthor($key, $categoryId);
					}else{
						echo 0;
					}
				}elseif($operation == 'keyword'){
					if(isset($_GET['key']) && isset($_GET['category'])){
						$key = $_GET['key'];
						$categoryId = $_GET['category'];
						$this->searchArticleByKeyword($key, $categoryId);
					}else{
						echo 0;
					}
				}

			}else{
				echo 0;
			}
		}
		/* Calls business tier method to read Journals list and create
		their links */
		
		private function validateAdmin()
		{
			if (isset ($_SESSION['admin_logged']) && $_COOKIE['admin_logged'] == true) {
				return true;
			}else{
				//return false;
				//Development override
				return true;
			}			
		}

		public function getProduct($id)
		{
			$this->mProducts = Product::GetProduct($id);
			echo json_encode($this->mProducts);
			
		}

		public function getProductByName($name)
		{
			$this->mProducts = Product::GetProductByName($name);
			echo json_encode($this->mProducts);
			
		}

		public function getStockInventory($page)
		{
			echo json_encode(StockInventory::GetInventory($page));
			
		}

		public function getFeatured()
		{
			echo json_encode(StockInventory::GetFeatured());
			
		}

		public function getLatest()
		{
			echo json_encode(StockInventory::GetLatest());
			
		}

		public function getLatestArticles()
		{
			$this->mArticles = Product::GetLatestArticles();
			echo json_encode($this->mArticles);		
		}

		private function getUserIdentifier()
		{
			session_start();
			if (isset($_SESSION['oauth_id'])){
	      		return $_SESSION['oauth_id'];
				//echo 1; 
	  		}elseif (isset($_COOKIE['cookie_key'])){
	  			return $_COOKIE['cookie_key'];
	      		//echo $_COOKIE['session_key'].'23';
				//echo 1; 
	  		}elseif (isset($_SESSION['session_key'])){				
	      		return $_SESSION['session_key'];
	      		//echo $_SESSION['session_key'].'46';
				//echo 1; 
	  		}else{
	  			echo 0;
	  		}
					 
		}

		
		private function createProduct($name, $type, $ref, $desc, $cat, $manufacturer, $avail, $featured, $features, $pprice, $rprice, $wprice, $tax, $img, $openstock, $optstock, $lowstock, $pheight, $pwidth, $plength, $pweight, $shape, $pack, $images)
		{
			if ($this->validateAdmin()) {
				echo json_encode(StockInventory::CreateItem($name, $type, $ref, $desc, $cat, $manufacturer, $avail, $featured, $features, $pprice, $rprice, $wprice, $tax, $img, $openstock, $optstock, $lowstock, $pheight, $pwidth, $plength, $pweight, $shape, $pack, $images));
			}else{
				echo 0;
			}			
		}

		public function updateProduct($ProductId, $url, $name, $category, $cover_img, $scrp_fn)
		{
			if ($this->validateAdmin()) {
				Product::UpdateProduct($ProductId, $url, $name, $category, $cover_img, $scrp_fn);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function deleteProduct($ProductId)
		{
			if ($this->validateAdmin()) {
				Product::DeleteProduct($ProductId);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function createCategory($name, $description)
		{
			if ($this->validateAdmin()) {
				Product::CreateCategory($name, $description);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function updateCategory($categoryId, $name, $description)
		{
			if ($this->validateAdmin()) {
				Product::updateCategory($categoryId, $name, $description);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function deleteCategory($categoryId)
		{
			if ($this->validateAdmin()) {
				Product::DeleteCategory($categoryId);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function uploadProductCover()
		{
			if (isset ($_POST['coverUpload']))
			{
			/* Check whether we have write permission on the
			product_images folder */
				if (!is_writeable(SITE_ROOT . '/assets/Productcovers/'))
				{
					echo "Can't write to the Product covers folder";
					exit();
				}
				// If the error code is 0, the file was uploaded ok
				if ($_FILES['ImageUpload']['error'] == 0)
				{	
					/* Use the move_uploaded_file PHP function to move the file
					from its temporary location to the product_images folder */
					move_uploaded_file($_FILES['ImageUpload']['tmp_name'], SITE_ROOT.'/assets/Productcovers/'.$_FILES['ImageUpload']['name']);
					// Update the product's information in the database
					//Catalog::SetImage($this->_mProductId, $_FILES['ImageUpload']['name']);
					echo $_FILES['ImageUpload']['name'];
				}
			}
		}


		public function searchArticleByTitle($key, $categoryId)
		{
			if ($categoryId == 0) {
				$this->mArticles = Product::SearchArticleByTitle($key);
				echo json_encode($this->mArticles);	
			}else{
				$this->mArticles = Product::SearchCategoryArticleByTitle($key, $categoryId);
				echo json_encode($this->mArticles);	
			}
			
		}

		public function searchArticleByAuthor($key, $categoryId)
		{
			if ($categoryId == 0) {
				$this->mArticles = Product::SearchArticleByAuthor($key);
				echo json_encode($this->mArticles);	
			}else{
				$this->mArticles = Product::SearchCategoryArticleByAuthor($key, $categoryId);
				echo json_encode($this->mArticles);	
			}
			
		}

		public function searchArticleByCategory($key, $categoryId)
		{
			if ($categoryId == 0) {
				$this->mArticles = Product::SearchArticleByCategory($key);
				echo json_encode($this->mArticles);
			}else{
				$this->mArticles = Product::SearchCategoryArticleByCategory($key, $categoryId);
				echo json_encode($this->mArticles);	
			}
		}

		public function searchArticleByKeyword($key, $categoryId)
		{
			if ($categoryId == 0) {
				$this->mArticles = Product::SearchArticleByKeyword($key);
				echo json_encode($this->mArticles);	
			}else{
				$this->mArticles = Product::SearchCategoryArticleByKeyword($key, $categoryId);
				echo json_encode($this->mArticles);	
			}
			
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

	$response = new Inventory();
	//$response->init();
	//echo json_encode($response->mJournals);
?>