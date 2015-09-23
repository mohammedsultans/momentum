<?php

class Logger
{
 	//System logging facility
 	function __construct($class, $category, $message)
 	{
 		$datetime = new DateTime();
		$stamp = $datetime->format('YmdHis');
 		try {
			$sql = 'INSERT INTO logs (class, category, message, stamp) VALUES ("'.$class.'", "'.$category.'", "'.$message.'", '.$stamp.')';
			DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			Logger::Log(get_class($this), 'Exception', $e->getMessage());
		}
 	}

 	public static function Log($class, $category, $message)
 	{
 		$datetime = new DateTime();
		$stamp = $datetime->format('YmdHis');
		try {
			$sql = 'INSERT INTO logs (class, category, message, stamp) VALUES ("'.$class.'", "'.$category.'", "'.$message.'", '.$stamp.')';
			DatabaseHandler::Execute($sql);
		} catch (Exception $e) {
			Logger::Log(get_class($this), 'Exception', $e->getMessage());
		}
 		
 	}
}


?>