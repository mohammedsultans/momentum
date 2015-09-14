<?php
	// Business tier class for reading journal information
	//require_once '../include/config.php';
  	require_once DATA_DIR . 'error_handler.php';
  	ErrorHandler::SetHandler();
  	require_once DATA_DIR . 'database_handler.php';

	class Journal
	{
		// Retrieves all journals
		public static function GetJournals()
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journal_display_details()';
			$sql = 'SELECT journal_id, name, category_id, cover_img_url, URL, scraping_function FROM journals';
			//'SELECT name, cover_img_url FROM journals'
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
		}

		public static function GetJournal($id)
		{
			// Build SQL query
			$sql = 'SELECT name, cover_img_url FROM journals WHERE journal_id = '. $id;
			// Execute the query and return the results
			return DatabaseHandler::GetRow($sql);
		}

		public static function GetLatestJournalIssue($journalId)
		{
			// Build SQL query
			$sql = 'SELECT issue FROM journal_issue WHERE journal_id = '.$journalId.' ORDER BY date_added DESC LIMIT 0,1';
			// Execute the query and return the results
			return DatabaseHandler::GetOne($sql);
		}

		public static function GetJournalIssues($journalId)
		{
			// Build SQL query
			$sql = 'SELECT issue, date_added, article_count FROM journal_issue WHERE journal_id = '.$journalId;
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
		}

		public static function GetLatestIssueArticles($journalId)
		{
			
			$latest = self::GetLatestJournalIssue($journalId);
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql = 'SELECT article_id, URL, title, author, abstract, journal_id, journal_issue, keywords FROM articles WHERE journal_id = '.$journalId.' AND journal_issue = "'.$latest.'" ORDER BY date_added ASC';
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
		}

		public static function GetIssueArticles($journalId, $issue)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql = 'SELECT article_id, URL, title, author, abstract, journal_id, journal_issue, keywords FROM articles WHERE journal_id = '.$journalId.' AND journal_issue = "'.$issue.'" ORDER BY date_added ASC';
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
		}

		public static function GetLatestArticles()
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql = 'SELECT title, URL, author, abstract, journal_id, journal_issue FROM articles ORDER BY date_added DESC LIMIT 0, 30';
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
		}

		public static function GetLatestArticlesEmail($email)
		{
			// Build SQL query
			$sqlone = 'SELECT user_id FROM users WHERE email = "'.$email.'"';
			$userId = DatabaseHandler::GetOne($sqlone);

			$sqltwo = 'SELECT journal_id FROM user_journal WHERE user_id = '.$userId;
			$journalIds = DatabaseHandler::GetAll($sqltwo);

			$sqlfour = 'SELECT COUNT(*) FROM user_journal WHERE user_id = '.$userId;
			$count = DatabaseHandler::GetOne($sqlfour);

			$sqlthree = 'SELECT title, URL, author, abstract, journal_id, journal_issue FROM articles';

			/*$res = array();
			foreach($journalIds as $key=>$value){
				//return $value;
				//array_merge($res, $value['journal_id']);
				array_push($res, $value$res, $value['journal_id']);
				//$sqlthree = $sqlthree.' journal_id = '.$id.'';
			}*/
			//return $count;

			//SQL Query Builder
			if ($count > 0) {
				$sqlthree = $sqlthree.' WHERE';
				for ($i=0; $i < $count ; $i++) { 
					if ($i != ($count - 1)){
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].' OR';
					}else{
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].' ORDER BY date_added DESC LIMIT 0, 30';
					}
				}
			}else{
				$sqlthree = $sqlthree.' ORDER BY date_added DESC LIMIT 0, 30';
			}
			

			//return $sqlthree;
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sqlthree);
		}

		public static function GetLatestArticlesOauth($oauth)
		{
			// Build SQL query
			$sqlone = 'SELECT user_id FROM users WHERE oauth_uid = "'.$oauth.'"';
			$userId = DatabaseHandler::GetOne($sqlone);

			$sqltwo = 'SELECT journal_id FROM user_journal WHERE user_id = '.$userId;
			$journalIds = DatabaseHandler::GetAll($sqltwo);

			$sqlfour = 'SELECT COUNT(*) FROM user_journal WHERE user_id = '.$userId;
			$count = DatabaseHandler::GetOne($sqlfour);

			$sqlthree = 'SELECT title, URL, author, abstract, journal_id, journal_issue FROM articles';

			/*$res = array();
			foreach($journalIds as $key=>$value){
				//return $value;
				//array_merge($res, $value['journal_id']);
				array_push($res, $value$res, $value['journal_id']);
				//$sqlthree = $sqlthree.' journal_id = '.$id.'';
			}*/
			//return $count;

			//SQL Query Builder
			if ($count > 0) {
				$sqlthree = $sqlthree.' WHERE';
				for ($i=0; $i < $count ; $i++) { 
					if ($i != ($count - 1)){
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].' OR';
					}else{
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].' ORDER BY date_added DESC LIMIT 0, 30';
					}
				}
			}else{
				$sqlthree = $sqlthree.' ORDER BY date_added DESC LIMIT 0, 30';
			}
			

			//return $sqlthree;
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sqlthree);
		}

		public static function GetLatestArticlesCategory($id)
		{
			// Build SQL query
			$sqlone = 'SELECT journal_id FROM journals WHERE category_id = '.$id;
			$journalIds = DatabaseHandler::GetAll($sqlone);

			$sqlfour = 'SELECT COUNT(*) FROM journals WHERE category_id = '.$id;
			$count = DatabaseHandler::GetOne($sqlfour);

			$sqlthree = 'SELECT title, URL, author, abstract, journal_id, journal_issue FROM articles';

			/*$res = array();
			foreach($journalIds as $key=>$value){
				//return $value;
				//array_merge($res, $value['journal_id']);
				array_push($res, $value$res, $value['journal_id']);
				//$sqlthree = $sqlthree.' journal_id = '.$id.'';
			}*/
			//return $count;

			//SQL Query Builder
			if ($count > 0) {
				$sqlthree = $sqlthree.' WHERE';
				for ($i=0; $i < $count ; $i++) { 
					if ($i != ($count - 1)){
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].' OR';
					}else{
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].' ORDER BY date_added DESC LIMIT 0, 30';
					}
				}
			}else{
				$sqlthree = $sqlthree.' ORDER BY date_added DESC LIMIT 0, 30';
			}
			

			//return $sqlthree;
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sqlthree);
		}

		public static function GetArticle($articleId)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql = 'SELECT title, URL, author, abstract, journal_id, journal_issue FROM articles WHERE article_id = '.$articleId;
			// Execute the query and return the results
			return DatabaseHandler::GetRow($sql);
		}

		public static function GetCategoryArticles($categoryId)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			//$sql = 'SELECT title, URL, author, abstract, journal_id, journal_issue FROM articles WHERE category_id = '.$categoryId.' ORDER BY date_added ASC LIMIT 0, 30';
			// Execute the query and return the results
			//return DatabaseHandler::GetAll($sql);

			$sqlone = 'SELECT journal_id FROM journals WHERE category_id = '.$categoryId;
			$journalIds = DatabaseHandler::GetAll($sqlone);

			$sqlfour = 'SELECT COUNT(*) FROM journals WHERE category_id = '.$categoryId;
			$count = DatabaseHandler::GetOne($sqlfour);

			$sqlthree = 'SELECT title, URL, author, abstract, journal_id, journal_issue FROM articles';

			/*$res = array();
			foreach($journalIds as $key=>$value){
				//return $value;
				//array_merge($res, $value['journal_id']);
				array_push($res, $value$res, $value['journal_id']);
				//$sqlthree = $sqlthree.' journal_id = '.$id.'';
			}*/
			//return $count;

			//SQL Query Builder
			if ($count > 0) {
				$sqlthree = $sqlthree.' WHERE';
				for ($i=0; $i < $count ; $i++) { 
					if ($i != ($count - 1)){
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].' OR';
					}else{
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].' ORDER BY date_added DESC LIMIT 0, 30';
					}
				}
			}else{
				$sqlthree = $sqlthree.' ORDER BY date_added DESC LIMIT 0, 30';
			}
			

			//return $sqlthree;
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sqlthree);
		}

		public static function GetCategorisedArticles($journalId)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql = 'SELECT title, URL, author, abstract, journal_id, journal_issue FROM articles WHERE journal_id = '.$journalId.' ORDER BY date_added ASC LIMIT 0, 30';
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
		}

		public static function GetCategories()
		{
			// Build SQL query
			//$sql = 'SELECT c.category_id, c.name, c.description, COUNT(a.article_id) AS article_count FROM categories c INNER JOIN articles a WHERE a.category_id = c.category_id GROUP BY c.category_id';
			// Execute the query and return the results
			//return DatabaseHandler::GetAll($sql);

			$sql = 'SELECT category_id, name, description FROM categories';

			$categories =  DatabaseHandler::GetAll($sql);

			$res = array();

			$count = 0;

			foreach($categories as $category){

				$cat = $category['category_id'];

				$sql2 = 'SELECT COUNT(*) FROM journals j INNER JOIN categories c ON j.category_id = c.category_id WHERE j.category_id = '.$cat;

				$category['journal_count'] =  DatabaseHandler::GetOne($sql2);

				$res[$count] = array_merge($res, $category);

				$count++;
			}

			return $res;
		}

		public static function CreateIssue($journalId, $issue)
		{
			$date = new DateTime();
			$date = $date->format('Y-m-d');
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql = 'INSERT INTO journal_issue (journal_id, issue, date_added) 
					VALUES ('.$journalId.', "'.$issue.'", "'.$date.'")';
			// Execute the query and return the results
			DatabaseHandler::Execute($sql);
		}

		public static function UpdateIssue($journalId, $newissue, $oldissue)
		{
			// Build SQL query
			$sql = 'UPDATE journal_issue SET issue = "'.$newissue.'" WHERE journal_id = '.$journalId. ' AND issue = "'.$oldissue.'"';
			// Execute the query
			DatabaseHandler::Execute($sql);
		}

		private static function IssueCount($journalId, $issue)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql = 'SELECT article_count FROM journal_issue WHERE journal_id = '.$journalId.' AND issue = "'.$issue.'"';
			// Execute the query and return the results
			return DatabaseHandler::GetOne($sql);

			//'UPDATE journal_issue SET article_count = '.$url.'  WHERE journal_id = '.$journalId.' AND issue = "'.$issue.'"';
		}


		public static function CreateJournal($url, $name, $categoryId, $cover_img, $scrp_fn)
		{
			// Build SQL query
			$img_url = './assets/journalcovers/'. $cover_img;
			//$sql = 'CALL journal_get_journals_list()';
			$sql = 'INSERT INTO journals (URL, name, scraping_function, cover_img_url) 
					VALUES ("'.$url.'", "'.$name.'", '.$categoryId.', "'.$scrp_fn.'", "'.$img_url.'")';
			// Execute the query and return the results
			DatabaseHandler::Execute($sql);
		}

		public static function UpdateJournal($journalId, $url, $name, $categoryId, $cover_img, $scrp_fn)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$img_url = './assets/journalcovers/'. $cover_img;
			$sql = 'UPDATE journals SET URL = "'.$url.'", name = "'.$name.'",  category_id = '.$categoryId.', scraping_function = "'.$scrp_fn.'", cover_img_url = "'.$img_url.'" WHERE journal_id = '.$journalId;
			// Execute the query and return the results
			DatabaseHandler::Execute($sql);
		}

		public static function DeleteJournal($journalId)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql = 'DELETE FROM journals WHERE journal_id = '.$journalId;
			
			DatabaseHandler::Execute($sql);
		}

		public static function DeleteIssue($journalId, $issue)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql = "DELETE FROM articles WHERE journal_id = ".$journalId. " AND journal_issue = '" .$issue. "'";
			
			DatabaseHandler::Execute($sql);

			$sql2 = "DELETE FROM journal_issue WHERE journal_id = ".$journalId. " AND issue = '" .$issue. "'";
			
			DatabaseHandler::Execute($sql2);
		}

		public static function CreateArticle($journalId, $issue, $title, $author, $url, $abstract, $keywords)
		{
			$date = new DateTime();
			$date = $date->format('Y-m-d');
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql = 'INSERT INTO articles (URL, title, author, journal_id, journal_issue, date_added, abstract, keywords) 
					VALUES ("'.$url.'", "'.$title.'", "'.$author.'", '.$journalId.', "'.$issue.'", "'.$date.'", "'.$abstract.'", "'.$keywords.'")';
			// Execute the query and return the results
			DatabaseHandler::Execute($sql);

			$count = self::IssueCount($journalId, $issue);

			$count++;

			$sql2 = 'UPDATE journal_issue SET article_count = '.$count.'  WHERE journal_id = '.$journalId.' AND issue = "'.$issue.'"';

			DatabaseHandler::Execute($sql2);
		}

		public static function UpdateArticle($articleId, $title, $author, $url, $abstract, $keywords)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_articles_list()';
			$sql = 'UPDATE articles SET URL = "'.$url.'", title = "'.$title.'", author = "'.$author.'", abstract = "'.$abstract.'", keywords = "'.$keywords.'" WHERE article_id = '.$articleId;
			// Execute the query and return the results
			DatabaseHandler::Execute($sql);
		}

		public static function DeleteArticle($articleId, $journalId, $issue)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql = 'DELETE FROM articles WHERE article_id = '.$articleId;
			
			DatabaseHandler::Execute($sql);

			$count = self::IssueCount($journalId, $issue);

			$count--;

			$sql2 = 'UPDATE journal_issue SET article_count = '.$count.'  WHERE journal_id = '.$journalId.' AND issue = "'.$issue.'"';

			DatabaseHandler::Execute($sql2);
		}

		public static function CreateCategory($name, $description)
		{
			// Build SQL query
			//$sql = 'CALL Category_get_Categorys_list()';
			$sql = 'INSERT INTO categories (name, description) VALUES ("'.$name.'", "'.$description.'")';
			// Execute the query and return the results
			DatabaseHandler::Execute($sql);
		}

		public static function UpdateCategory($categoryId, $name, $description)
		{
			// Build SQL query
			//$sql = 'CALL Category_get_Categorys_list()';
			$sql = 'UPDATE categories SET name = "'.$name.'", description = "'.$description.'" WHERE category_id = '.$categoryId;
			// Execute the query and return the results
			DatabaseHandler::Execute($sql);
		}

		public static function DeleteCategory($categoryId)
		{
			// Build SQL query
			//$sql = 'CALL category_get_categorys_list()';
			$sql = 'DELETE FROM categories WHERE category_id = '.$categoryId;
			
			DatabaseHandler::Execute($sql);
		}

		public static function SearchArticleByTitle($title)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql = 'SELECT title, author, URL, abstract, journal_id, journal_issue FROM articles WHERE title LIKE "%'.$title.'%" ORDER BY date_added DESC LIMIT 0,30';
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
		}

		public static function SearchArticleByAuthor($author)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql = 'SELECT title, author, URL, abstract, journal_id, journal_issue FROM articles WHERE author LIKE "%'.$author.'%" ORDER BY date_added DESC LIMIT 0,30';
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
		}

		public static function SearchArticleByCategory($issue)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql = 'SELECT title, author, URL, abstract, journal_id, journal_issue FROM articles WHERE journal_issue LIKE "%'.$issue.'%" ORDER BY date_added DESC LIMIT 0,30';
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
		}

		public static function SearchArticleByKeyword($keyword)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql = 'SELECT title, author, URL, abstract, journal_id, journal_issue FROM articles WHERE keywords LIKE "%'.$keyword.'%" ORDER BY date_added DESC LIMIT 0,30';
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql);
		}

		public static function SearchCategoryArticleByTitle($title, $categoryId)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			//$sql = 'SELECT title, author, URL, abstract, journal_id, journal_issue FROM articles WHERE title LIKE "%'.$title.'%" AND category_id = '.$categoryId.' ORDER BY date_added DESC LIMIT 0,30';
			// Execute the query and return the results
			//return DatabaseHandler::GetAll($sql);

			$sqlone = 'SELECT journal_id FROM journals WHERE category_id = '.$categoryId;
			$journalIds = DatabaseHandler::GetAll($sqlone);

			$sqlfour = 'SELECT COUNT(*) FROM journals WHERE category_id = '.$categoryId;
			$count = DatabaseHandler::GetOne($sqlfour);

			$sqlthree = 'SELECT title, URL, author, abstract, journal_id, journal_issue FROM articles';

			//SQL Query Builder
			if ($count > 0) {
				$sqlthree = $sqlthree.' WHERE (';
				for ($i=0; $i < $count ; $i++) { 
					if ($i != ($count - 1)){
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].' OR';
					}else{
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].') AND title LIKE "%'.$title.'%" ORDER BY date_added DESC LIMIT 0, 30';
					}
				}
			}else{
				return array();
			}
			//return $sqlthree;
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sqlthree);
		}

		public static function SearchCategoryArticleByAuthor($author, $categoryId)
		{
			// Build SQL query
			$sqlone = 'SELECT journal_id FROM journals WHERE category_id = '.$categoryId;
			$journalIds = DatabaseHandler::GetAll($sqlone);

			$sqlfour = 'SELECT COUNT(*) FROM journals WHERE category_id = '.$categoryId;
			$count = DatabaseHandler::GetOne($sqlfour);

			$sqlthree = 'SELECT title, URL, author, abstract, journal_id, journal_issue FROM articles';

			//SQL Query Builder
			if ($count > 0) {
				$sqlthree = $sqlthree.' WHERE (';
				for ($i=0; $i < $count ; $i++) { 
					if ($i != ($count - 1)){
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].' OR';
					}else{
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].') AND author LIKE "%'.$author.'%" ORDER BY date_added DESC LIMIT 0, 30';
					}
				}
			}else{
				return array();
			}
			//return $sqlthree;
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sqlthree);
		}

		public static function SearchCategoryArticleByCategory($issue, $categoryId)
		{
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sqlone = 'SELECT journal_id FROM journals WHERE category_id = '.$categoryId;
			$journalIds = DatabaseHandler::GetAll($sqlone);

			$sqlfour = 'SELECT COUNT(*) FROM journals WHERE category_id = '.$categoryId;
			$count = DatabaseHandler::GetOne($sqlfour);

			$sqlthree = 'SELECT title, URL, author, abstract, journal_id, journal_issue FROM articles';

			//SQL Query Builder
			if ($count > 0) {
				$sqlthree = $sqlthree.' WHERE (';
				for ($i=0; $i < $count ; $i++) { 
					if ($i != ($count - 1)){
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].' OR';
					}else{
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].') AND journal_issue LIKE "%'.$issue.'%" ORDER BY date_added DESC LIMIT 0, 30';
					}
				}
			}else{
				return array();
			}
			//return $sqlthree;
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sqlthree);
		}

		public static function SearchCategoryArticleByKeyword($keyword, $categoryId)
		{
			$sqlone = 'SELECT journal_id FROM journals WHERE category_id = '.$categoryId;
			$journalIds = DatabaseHandler::GetAll($sqlone);

			$sqlfour = 'SELECT COUNT(*) FROM journals WHERE category_id = '.$categoryId;
			$count = DatabaseHandler::GetOne($sqlfour);

			$sqlthree = 'SELECT title, URL, author, abstract, journal_id, journal_issue FROM articles';

			//SQL Query Builder
			if ($count > 0) {
				$sqlthree = $sqlthree.' WHERE (';
				for ($i=0; $i < $count ; $i++) { 
					if ($i != ($count - 1)){
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].' OR';
					}else{
						$sqlthree = $sqlthree.' journal_id = '.$journalIds[$i]['journal_id'].') AND keywords LIKE "%'.$keyword.'%" ORDER BY date_added DESC LIMIT 0, 30';
					}
				}
			}else{
				return array();
			}
			//return $sqlthree;
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sqlthree);
		}

		/*public static function SearchArticleByJournal($journal)
		{
			
			$sql = 'SELECT journal_id FROM journals WHERE name LIKE "%'.$journal.'%"';
			$res = DatabaseHandler::GetAll($sql);
			// Build SQL query
			//$sql = 'CALL journal_get_journals_list()';
			$sql2 = 'SELECT title, author, abstract, journal_id, journal_issue FROM articles WHERE title LIKE "%'.$title.'%" ORDER BY date_added DESC LIMIT 0,30';
			// Execute the query and return the results
			return DatabaseHandler::GetAll($sql2);



			array_merge($res1, $res2)
		}*/

	}


	//$response = new Journal();
	//UpdateJournal($journalId, $url, $name, $cover_img, $scrp_fn)
	//echo json_encode($response::UpdateJournal(35, "http://www.investopedia.com/nalvadlue.asp47", "Journal of Update Testing", "devel econ.jpg", ""));
	//echo json_encode($response->mJournals);
	//echo json_encode($response::GetLatestArticlesEmail('alex@qet.co.ke'));

?>