<?php
	// Business tier class for reading journal information
	//require_once '../include/config.php';
  	require_once DOMAIN_DIR . 'error_handler.php';
  	ErrorHandler::SetHandler();
  	require_once DOMAIN_DIR . 'database_handler.php';

	class Blog
	{
		// Retrieves all journals
		public static function GetBlogRoll()
		{
			// Build SQL query
			$sql = 'CALL blog_get_blog_list()';
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
		}

		public static function GetComments($blogId)
		{
			// Build SQL query
			//$sql = 'CALL blog_get_comments_list(:blog_id)';
			$sql = 'SELECT * FROM comments WHERE blog_id = ' . $blogId ;

			//return $sql;

			//$params = array(':blog_id' => $blogId);
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
			//return DatabaseHandler::Execute($sql);
		}

		public static function GetAllComments()
		{
			// Build SQL query
			$sql = 'CALL blog_get_all_comments()';
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
		}

		public static function GetBlogRollPage($page)
		{
			// Build SQL query
			$sql = 'CALL blog_get_blog_list()';
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
		}

		/* Calculates how many pages of blogroll could be filled by the
		number of blogs returned by the $countSql query */
		private static function HowManyPages($countSql, $countSqlParams)
		{
			// Create a hash for the sql query
			$queryHashCode = md5($countSql . var_export($countSqlParams, true));
			// Verify if we have the query results in cache
			if (isset ($_SESSION['last_count_hash']) &&	isset ($_SESSION['how_many_pages']) &&
					$_SESSION['last_count_hash'] === $queryHashCode)
			{
				// Retrieve the the cached value
				$how_many_pages = $_SESSION['how_many_pages'];
			}
			else
			{
				// Execute the query
				$items_count = DatabaseHandler::GetOne($countSql, $countSqlParams);
				// Calculate the number of pages
				$how_many_pages = ceil($items_count / PRODUCTS_PER_PAGE);
				// Save the query and its count result in the session
				$_SESSION['last_count_hash'] = $queryHashCode;
				$_SESSION['how_many_pages'] = $how_many_pages;
			}
			// Return the number of pages
			return $how_many_pages;
		}

		// Retrieves list of products that belong to a category
		public static function GetProductsInCategory($categoryId, $pageNo, &$rHowManyPages)
		{
			// Query that returns the number of products in the category
			$sql = 'CALL catalog_count_products_in_category(:category_id)';
			// Build the parameters array
			$params = array (':category_id' => $categoryId);
			// Calculate the number of pages required to display the products
			$rHowManyPages = Catalog::HowManyPages($sql, $params);
			// Calculate the start item
			$start_item = ($pageNo - 1) * PRODUCTS_PER_PAGE;
			// Retrieve the list of products
			$sql = 'CALL catalog_get_products_in_category(
				:category_id, :short_product_description_length,
				:products_per_page, :start_item)';
			// Build the parameters array
			$params = array (':category_id' => $categoryId,
				':short_product_description_length' => SHORT_PRODUCT_DESCRIPTION_LENGTH,
				':products_per_page' => PRODUCTS_PER_PAGE,
				':start_item' => $start_item);
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql, $params);
		}

		public static function GetBlogDetails($blogId)
		{
			// Build SQL query
			$sql = 'CALL blog_get_blog_details(:blog_id)';

			// Build the parameters array
			$params = array (':blog_id' => $blogId);
			// Execute the query and return the results
			return DatabaseHandler::GetRow($sql, $params);
		}

		public static function CreateBlog($date, $title, $author, $authorTitle, 
			$authorImgUrl, $abstractText, $fullText)
		{
			// Build SQL query
			$sql = 'CALL blog_create_blog_entry(:blog_id)';

			// Build the parameters array
			$params = array (':blog_id' => $blogId);
			// Execute the query and return the results
			return DatabaseHandler::Execute($sql, $params);
		}

		public static function UpdateBlog($title, $author, $authorTitle, 
			$authorImgUrl, $abstractText, $fullText)
		{
			// Build SQL query
			$sql = 'CALL blog_update_blog_entry(:blog_id)';

			// Build the parameters array
			$params = array (':blog_id' => $blogId);
			// Execute the query and return the results
			return DatabaseHandler::Execute($sql, $params);
		}

		public static function DeleteBlog($blogId)
		{
			// Build SQL query
			$sql = 'CALL blog_delete_blog(:blog_id)';

			// Build the parameters array
			$params = array (':blog_id' => $blogId);
			// Execute the query and return the results
			return DatabaseHandler::Execute($sql, $params);
		}

	}

	//$response = new Blog();
	//$response->getComments(1);
	//echo json_encode($response->GetComments(1));

	//DatabaseHandler::Close();
?>