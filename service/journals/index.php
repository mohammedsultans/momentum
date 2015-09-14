<?php
// Manages the Journals list
  	require_once '../../include/config.php';
  	require_once DOMAIN_DIR . 'journal.php';
	
	class JournalsList
	{
		/* 	Variable available to calling function */
		
		public $mSelectedDepartment = 0;
		private $mJournals, $mArticles, $mIssues, $mCategories;
		// Constructor reads query string parameter
		public function __construct()
		{
			if(isset($_POST['operation'])){
				$operation = $_POST['operation'];
				if($operation == 'createJournal'){
					if(isset($_POST['url']) && isset($_POST['name']) && isset($_POST['category']) && isset($_POST['coverImgUrl'])){
						$url = $_POST['url'];//Some clean up :)
						$name = $_POST['name'];
						$category = $_POST['category'];
						$cover_img = $_POST['coverImgUrl'];//Some clean up :)
						$scrp_fn = $_POST['scrapingFn'];
						$this->createJournal($url, $name, $category, $cover_img, $scrp_fn);
					}else{
						echo 0;
					}
						
				}elseif($operation == 'updateJournal'){
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
				}elseif($operation == 'createIssue'){
					if(isset($_POST['journalId']) && isset($_POST['issue'])){
						$journalId = $_POST['journalId'];
						$issue = $_POST['issue'];
						$this->createIssue($journalId, $issue);
					}else{
						echo 0;
					}
						
				}elseif($operation == 'updateIssue'){
					if(isset($_POST['journalId']) && isset($_POST['issue']) && isset($_POST['previssue'])){
						$journalId = $_POST['journalId'];
						$issue = $_POST['issue'];
						$newissue = $_POST['issue'];
						$oldissue = $_POST['previssue'];
						$this->updateIssue($journalId, $newissue, $oldissue);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'deleteIssue'){
					if(isset($_POST['journalId']) && isset($_POST['issue'])){
						$journalId = $_POST['journalId'];
						$issue = $_POST['issue'];
						$this->deleteIssue($journalId, $issue);
					}else{
						echo 0;
					}
				}elseif($operation == 'createArticle'){
					if(isset($_POST['journalId']) && isset($_POST['issue']) && isset($_POST['title']) && isset($_POST['author']) && isset($_POST['url']) && isset($_POST['abstract']) && isset($_POST['keywords'])){
						$journalId = $_POST['journalId'];
						$issue = $_POST['issue'];
						$title = $_POST['title'];
						$author = $_POST['author'];
						$url = $_POST['url'];
						$abstract = $_POST['abstract'];
						$keywords = $_POST['keywords'];
						$this->createArticle($journalId, $issue, $title, $author, $url, $abstract, $keywords);
					}else{
						echo 0;
					}
						
				}elseif($operation == 'updateArticle'){
					if(isset($_POST['articleId']) && isset($_POST['title']) && isset($_POST['author']) && isset($_POST['url']) && isset($_POST['abstract']) && isset($_POST['keywords'])){
						$articleId = $_POST['articleId'];
						$title = $_POST['title'];
						$author = $_POST['author'];
						$url = $_POST['url'];
						$abstract = $_POST['abstract'];
						$keywords = $_POST['keywords'];
						$this->updateArticle($articleId, $title, $author, $url, $abstract, $keywords);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'deleteArticle'){
					if(isset($_POST['articleId']) && isset($_POST['journalId']) && isset($_POST['issue'])){
						$articleId = $_POST['articleId'];
						$journalId = $_POST['journalId'];
						$issue = $_POST['issue'];
						$this->deleteArticle($articleId, $journalId, $issue);
					}else{
						echo 0;
					}
				}elseif($operation == 'createCategory'){ 
					if(isset($_POST['name']) && isset($_POST['description']) ){
						$name = $_POST['name'];
						$description = $_POST['description'];
						$this->createCategory($name, $description);
					}else{
						echo 0;
					}
						
				}elseif($operation == 'updateCategory'){
					if(isset($_POST['categoryId']) && isset($_POST['name']) && isset($_POST['description']) ){
						$categoryId = $_POST['categoryId'];
						$name = $_POST['name'];
						$description = $_POST['description'];
						$this->updateCategory($categoryId, $name, $description);
					}else{
						echo 0;
					}
				
				}elseif($operation == 'deleteCategory'){
					if(isset($_POST['categoryId'])){
						$categoryId = $_POST['categoryId'];
						$this->deleteCategory($categoryId);
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
			}elseif(isset($_GET['journals'])){
				$this->getJournals();
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

		public function getJournal($id)
		{
			$this->mJournals = Journal::GetJournal($id);
			echo json_encode($this->mJournals);
			
		}

		public function getJournalByName($name)
		{
			$this->mJournals = Journal::GetJournalByName($name);
			echo json_encode($this->mJournals);
			
		}

		public function getJournals()
		{
			$this->mJournals = Journal::GetJournals();
			echo json_encode($this->mJournals);
			
		}

		public function getLatestArticles()
		{
			$this->mArticles = Journal::GetLatestArticles();
			echo json_encode($this->mArticles);		
		}

		public function getLatestArticlesEmail()
		{
			session_start();
			if (isset($_SESSION['oauth_id'])){
	      		echo json_encode(Journal::GetLatestArticlesOauth($_SESSION['oauth_id']));
				//echo 1; 
	  		}elseif (isset($_COOKIE['cookie_key'])){
	  			echo json_encode(Journal::GetLatestArticlesEmail($_COOKIE['cookie_key']));
	      		//echo $_COOKIE['session_key'].'23';
				//echo 1; 
	  		}elseif (isset($_SESSION['session_key'])){				
	      		echo json_encode(Journal::GetLatestArticlesEmail($_SESSION['session_key']));
	      		//echo $_SESSION['session_key'].'46';
				//echo 1; 
	  		}else{
	  			echo 0;
	  		}
					 
		}

		public function getIssueArticles($journalId, $issue)
		{
			$this->mArticles = Journal::GetIssueArticles($journalId, $issue);
			echo json_encode($this->mArticles);		
		}

		public function getLatestIssueArticles($journalId)
		{
			$this->mArticles = Journal::GetLatestIssueArticles($journalId);
			echo json_encode($this->mArticles);		
		}

		public function getLatestIssueArticlesByName($journalName)
		{
			$this->mArticles = Journal::GetLatestIssueArticlesByName($journalName);
			echo json_encode($this->mArticles);		
		}

		public function getLatestJournalIssue($journalId)
		{
			$this->mIssues = Journal::GetLatestJournalIssue($journalId);
			echo $this->mIssues;		
		}

		public function getLatestJournalIssueByName($name)
		{
			$this->mIssues = Journal::GetLatestJournalIssueByName($name);
			echo $this->mIssues;		
		}

		public function getCategoryArticles($categoryId)
		{
			if ($categoryId == 0) {
				$this->getLatestArticles();		
			}else{
				$this->mArticles = Journal::GetCategoryArticles($categoryId);
				echo json_encode($this->mArticles);		
			}
			
		}

		public function getCategorisedArticles($journalId, $categoryId)
		{
			$this->mArticles = Journal::GetCategorisedArticles($journalId, $categoryId);
			echo json_encode($this->mArticles);		
		}

		public function getIssues($journalId)
		{
			$this->mArticles = Journal::GetJournalIssues($journalId);
			echo json_encode($this->mArticles);		
		}

		public function getCategories()
		{
			$this->mCategories = Journal::GetCategories();
			echo json_encode($this->mCategories);		
		}

		public function createJournal($url, $name, $category, $cover_img, $scrp_fn)
		{
			if ($this->validateAdmin()) {
				Journal::CreateJournal($url, $name, $category, $cover_img, $scrp_fn);
				echo 1;
			}else{
				echo 0;
			}			
		}

		public function updateJournal($journalId, $url, $name, $category, $cover_img, $scrp_fn)
		{
			if ($this->validateAdmin()) {
				Journal::UpdateJournal($journalId, $url, $name, $category, $cover_img, $scrp_fn);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function deleteJournal($journalId)
		{
			if ($this->validateAdmin()) {
				Journal::DeleteJournal($journalId);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function createIssue($journalId, $issue)
		{
			if ($this->validateAdmin()) {
				Journal::CreateIssue($journalId, $issue);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function updateIssue($journalId, $newissue, $oldissue)
		{
			if ($this->validateAdmin()) {
				Journal::UpdateIssue($journalId, $newissue, $oldissue);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function deleteIssue($journalId, $issue)
		{
			if ($this->validateAdmin()) {
				Journal::DeleteIssue($journalId, $issue);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function createArticle($journalId, $issue, $title, $author, $url, $abstract, $keywords)
		{
			if ($this->validateAdmin()) {
				Journal::CreateArticle($journalId, $issue, $title, $author, $url, $abstract, $keywords);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function updateArticle($articleId, $title, $author, $url, $abstract, $keywords)
		{
			if ($this->validateAdmin()) {
				Journal::updateArticle($articleId, $title, $author, $url, $abstract, $keywords);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function deleteArticle($articleId, $journalId, $issue)
		{
			if ($this->validateAdmin()) {
				Journal::DeleteArticle($articleId, $journalId, $issue);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function createCategory($name, $description)
		{
			if ($this->validateAdmin()) {
				Journal::CreateCategory($name, $description);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function updateCategory($categoryId, $name, $description)
		{
			if ($this->validateAdmin()) {
				Journal::updateCategory($categoryId, $name, $description);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function deleteCategory($categoryId)
		{
			if ($this->validateAdmin()) {
				Journal::DeleteCategory($categoryId);
				echo 1;
			}else{
				echo 0;
			}	
		}

		public function uploadJournalCover()
		{
			if (isset ($_POST['coverUpload']))
			{
			/* Check whether we have write permission on the
			product_images folder */
				if (!is_writeable(SITE_ROOT . '/assets/journalcovers/'))
				{
					echo "Can't write to the journal covers folder";
					exit();
				}
				// If the error code is 0, the file was uploaded ok
				if ($_FILES['ImageUpload']['error'] == 0)
				{	
					/* Use the move_uploaded_file PHP function to move the file
					from its temporary location to the product_images folder */
					move_uploaded_file($_FILES['ImageUpload']['tmp_name'], SITE_ROOT.'/assets/journalcovers/'.$_FILES['ImageUpload']['name']);
					// Update the product's information in the database
					//Catalog::SetImage($this->_mProductId, $_FILES['ImageUpload']['name']);
					echo $_FILES['ImageUpload']['name'];
				}
			}
		}


		public function searchArticleByTitle($key, $categoryId)
		{
			if ($categoryId == 0) {
				$this->mArticles = Journal::SearchArticleByTitle($key);
				echo json_encode($this->mArticles);	
			}else{
				$this->mArticles = Journal::SearchCategoryArticleByTitle($key, $categoryId);
				echo json_encode($this->mArticles);	
			}
			
		}

		public function searchArticleByAuthor($key, $categoryId)
		{
			if ($categoryId == 0) {
				$this->mArticles = Journal::SearchArticleByAuthor($key);
				echo json_encode($this->mArticles);	
			}else{
				$this->mArticles = Journal::SearchCategoryArticleByAuthor($key, $categoryId);
				echo json_encode($this->mArticles);	
			}
			
		}

		public function searchArticleByCategory($key, $categoryId)
		{
			if ($categoryId == 0) {
				$this->mArticles = Journal::SearchArticleByCategory($key);
				echo json_encode($this->mArticles);
			}else{
				$this->mArticles = Journal::SearchCategoryArticleByCategory($key, $categoryId);
				echo json_encode($this->mArticles);	
			}
		}

		public function searchArticleByKeyword($key, $categoryId)
		{
			if ($categoryId == 0) {
				$this->mArticles = Journal::SearchArticleByKeyword($key);
				echo json_encode($this->mArticles);	
			}else{
				$this->mArticles = Journal::SearchCategoryArticleByKeyword($key, $categoryId);
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

	$response = new JournalsList();
	//$response->init();
	//echo json_encode($response->mJournals);
?>