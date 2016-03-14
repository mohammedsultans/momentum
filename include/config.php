<?php
	// SITE_ROOT contains the full path to the tshirtshop folder
	define('SITE_ROOT', dirname(dirname(__FILE__)));
	// Application directories
	define('DATA_DIR', SITE_ROOT . '/data/');
	define('SERVICE_DIR', SITE_ROOT . '/service/');
	define('DOMAIN_DIR', SITE_ROOT . '/domain/');
	define('CONFIG_DIR', SITE_ROOT . '/include/configs');
	// These should be true while developing the web site
	define('IS_WARNING_FATAL', false);
	define('DEBUGGING', false);
	// The error types to be reported
	define('ERROR_TYPES', E_ALL);
	// Settings about mailing the error messages to admin
	define('SEND_ERROR_MAIL', false);
	define('ADMIN_ERROR_MAIL', 'support@qet.co.ke');
	define('SENDMAIL_FROM', 'errors@qet.co.ke');
	ini_set('sendmail_from', SENDMAIL_FROM);
	// By default we don't log errors to a file
	define('LOG_ERRORS', true);
	define('LOG_ERRORS_FILE', 'C:\\users\\alex\\code\\momentum\\errors\\errors_log.txt'); // Windows
	// define('LOG_ERRORS_FILE', '/home/username/tshirtshop/errors.log'); // Linux
	/* Generic error message to be displayed instead of debug info
	(when DEBUGGING is false) */
	define('SITE_GENERIC_ERROR_MESSAGE', '<h1>Momentum [Quantal ERP] Error!</h1>');
	// Database connectivity setup
	define('DB_PERSISTENCY', 'true');
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'admin');
	define('DB_PASSWORD', 'andromedapx660');
	define('DB_DATABASE', 'geomirror');
	define('PDO_DSN', 'mysql:host='.DB_SERVER.';dbname='.DB_DATABASE);
	// Server HTTP port (can omit if the default 80 is used)
	define('HTTP_SERVER_PORT', '80');
	/* Name of the virtual directory the site runs in, for example:
	'/tshirtshop/' if the site runs at http://www.example.com/tshirtshop/
	'/' if the site runs at http://www.example.com/ */
	define('VIRTUAL_LOCATION', '/momentum/');
?>